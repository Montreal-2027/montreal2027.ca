<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Commands;

use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Entity\EntityFieldManagerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\KeyValueStore\KeyValueFactoryInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountSwitcherInterface;
use Drupal\Core\StringTranslation\TranslationInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\Plugin\MigrationPluginManagerInterface;
use Drupal\migrate_tools\DrushLogMigrateMessage;
use Drupal\migrate_tools\MigrateExecutable;
use Drush\Attributes\Argument;
use Drush\Attributes\Command;
use Drush\Attributes\ValidateModulesEnabled;
use Drush\Commands\DrushCommands;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Symfony\Component\Console\Helper\ProgressBar;

/**
 * Imports In Memoriam entries from an Excel workbook.
 */
final class InMemoriamImportCommands extends DrushCommands {

  /**
   * ID of the Migrate Plus configuration entity used for each batch.
   */
  private const MIGRATION_ID = 'in_memoriam_import';

  /**
   * Exact, case-sensitive spreadsheet headers required by the import.
   */
  private const REQUIRED_HEADERS = [
    'First Name',
    'Last Name',
    'Year of Birth',
    'Date of Death',
    'Category',
    'Bio',
  ];

  /**
   * Destination field definitions that must exist for this migration.
   *
   * This checks field presence only; it does not mean each field is required
   * when creating or updating an entity.
   */
  private const REQUIRED_DESTINATION_FIELD_DEFINITIONS = [
    'title',
    'status',
    'field_first_name',
    'field_year_of_birth',
    'field_date_of_death',
    'field_category',
    'field_reason',
  ];

  /**
   * Creates the command and injects services used during import.
   *
   * @param \Drupal\Core\Entity\EntityFieldManagerInterface $entityFieldManager
   *   Provides destination bundle field definitions for startup validation.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Loads the destination content type and taxonomy vocabulary.
   * @param \Drupal\Core\Session\AccountSwitcherInterface $accountSwitcher
   *   Switches to the site administrator while validating configured text formats.
   * @param \Drupal\migrate\Plugin\MigrationPluginManagerInterface|null $migrationPluginManager
   *   Creates configured migration instances for source discovery and batches.
   *   NULL while the Migrate module is disabled. The service is optional so
   *   Drush can bootstrap, and run "drush deploy", before config import
   *   enables Migrate.
   * @param \Drupal\Core\KeyValueStore\KeyValueFactoryInterface $keyValueFactory
   *   Provides key/value storage required by the migrate executable.
   * @param \Drupal\Component\Datetime\TimeInterface $time
   *   Provides time services required by the migrate executable.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $translation
   *   Provides translation services required by the migrate executable.
   */
  public function __construct(
    private readonly EntityFieldManagerInterface $entityFieldManager,
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly AccountSwitcherInterface $accountSwitcher,
    private readonly ?MigrationPluginManagerInterface $migrationPluginManager,
    private readonly KeyValueFactoryInterface $keyValueFactory,
    private readonly TimeInterface $time,
    private readonly TranslationInterface $translation,
  ) {
    parent::__construct();
  }

  /**
   * Imports or updates unpublished In Memoriam nodes from an XLSX workbook.
   *
   * The command validates the input and destination before processing. It
   * discovers source row IDs once, then runs the same configured migration in
   * chunks of 25 IDs, displaying progress and stopping on migration failures.
   *
   * @param string $file
   *   Absolute or working-directory-relative path to the XLSX file. In Docker,
   *   this path must be visible inside the web container.
   *
   * @return int
   *   Drush success or failure exit status.
   *
   * @throws \Throwable
   *   Validation, source-reading, or migration exceptions are surfaced to
   *   Drush so failures are not reported as successful imports.
   */
  #[Command(name: 'montreal2027:import-in-memoriam', aliases: ['m2027:iim'])]
  #[Argument(name: 'file', description: 'Readable XLSX file path, as seen by the web container.')]
  #[ValidateModulesEnabled(modules: ['migrate', 'migrate_plus', 'migrate_spreadsheet', 'migrate_tools'])]
  public function import(string $file): int {
    // Resolve relative paths before validating or passing the file to the
    // spreadsheet source plugin, so every batch uses the same canonical path.
    $path = realpath($file);
    if ($path === FALSE || !is_file($path) || !is_readable($path)) {
      $this->logger()->error(sprintf('Cannot read XLSX file: %s', $file));
      return self::EXIT_FAILURE;
    }

    // Check the resolved filename rather than the user-supplied path, which
    // may be a symlink or contain relative path segments.
    if (strtolower(pathinfo($path, PATHINFO_EXTENSION)) !== 'xlsx') {
      $this->logger()->error('Input file must have the .xlsx extension.');
      return self::EXIT_FAILURE;
    }

    // Validate site structure and workbook headers before creating migration
    // executables, preventing partial imports caused by setup errors.
    $this->validateDestinationFields();
    $worksheet = $this->validateWorkbook($path);
    $migration = $this->createMigration($path, $worksheet);
    $source = $migration->getSourcePlugin();

    // Do not change map statuses for an empty workbook.
    if ($source->count() === 0) {
      $this->logger()->warning('The workbook contains no data rows.');
      return self::EXIT_SUCCESS;
    }

    // Failed or previously imported rows may have unchanged source hashes.
    // Mark existing IDs for processing; the node lookup skips unchanged nodes.
    $migration->getIdMap()->prepareUpdate();
    $source->rewind();

    // Scan the source once to discover stable row identifiers, not to import
    // row data. Each batch's migration source reads its selected rows again.
    $sourceIds = [];
    while ($source->valid()) {
      $sourceIds[] = $source->current()->getSourceIdValues();
      $source->next();
    }

    // The source skips blank rows, so a workbook with only formatted empty
    // rows has a positive raw count but no importable source IDs.
    $total = count($sourceIds);
    if ($total === 0) {
      $this->logger()->warning('The workbook contains no data rows.');
      return self::EXIT_SUCCESS;
    }
    $importAccount = $this->entityTypeManager->getStorage('user')->load(1);
    if (!$importAccount instanceof AccountInterface || !$importAccount->hasPermission('use text format basic_html')) {
      throw new \RuntimeException('The site administrator must have permission to use the basic_html text format.');
    }

    // Progress represents selected spreadsheet rows, including rows later
    // skipped by the update lookup when their destination is unchanged.
    $progress = new ProgressBar($this->output(), $total);
    $progress->start();

    // Use fresh migration instances per batch; MigrateExecutable marks each
    // instance busy and resets its source and listeners during import.
    foreach (array_chunk($sourceIds, 25) as $batch) {
      $batchMigration = $this->createMigration($path, $worksheet);
      // Spreadsheet row indexes form the migration source IDs. Serialize
      // each possibly composite ID with colons, then pass the batch as the
      // comma-separated ID list expected by migrate_tools' SourceFilter.
      $idList = [];
      foreach ($batch as $sourceId) {
        $idList[] = implode(':', $sourceId);
      }

      $executable = new MigrateExecutable(
        $batchMigration,
        new DrushLogMigrateMessage($this->logger()),
        $this->keyValueFactory,
        $this->time,
        $this->translation,
        [
          'idlist' => implode(',', $idList),
          'feedback' => 0,
        ],
      );

      // CLI runs as anonymous by default; entity validation requires permission
      // to use the configured text format. Restore the original account even
      // when a migration exception escapes.
      $this->accountSwitcher->switchTo($importAccount);
      try {
        $result = $executable->import();
      }
      finally {
        $this->accountSwitcher->switchBack();
      }

      // Treat an incomplete migration result as fatal before advancing the
      // display; failed-row counts are checked separately below.
      if ($result !== MigrationInterface::RESULT_COMPLETED) {
        $progress->clear();
        $this->logger()->error(sprintf(
          'Migration stopped before completing rows %d-%d.',
          $progress->getProgress() + 1,
          min($progress->getProgress() + count($batch), $total),
        ));
        return self::EXIT_FAILURE;
      }

      // Advance for all selected IDs. Unchanged rows are deliberate skips,
      // while failed rows are reported by the executable's failure counter.
      $progress->advance(count($batch));
      if ($executable->getFailedCount() > 0) {
        $progress->finish();
        $this->logger()->error(sprintf(
          'Migration failed to import %d row(s) in this batch.',
          $executable->getFailedCount(),
        ));
        return self::EXIT_FAILURE;
      }
    }

    $progress->finish();
    $this->output()->writeln(sprintf('Processed %d rows.', $total));
    return self::EXIT_SUCCESS;
  }

  /**
   * Creates a migration with the workbook source supplied at runtime.
   *
   * Both source discovery and batch execution require an available migration
   * definition. Core returns FALSE when no definition matches the requested
   * ID, so report the setup requirement instead of calling methods on FALSE.
   *
   * @param string $path
   *   Absolute path to the XLSX workbook.
   * @param string $worksheet
   *   Name of the worksheet validated by the command.
   *
   * @return \Drupal\migrate\Plugin\MigrationInterface
   *   A fresh migration instance configured for the supplied workbook.
   *
   * @throws \RuntimeException
   *   When Migrate is disabled or the migration definition is unavailable.
   */
  private function createMigration(string $path, string $worksheet): MigrationInterface {
    // ValidateModulesEnabled normally stops the command first; this guard
    // covers a stale container built before Migrate was enabled.
    if ($this->migrationPluginManager === NULL) {
      throw new \RuntimeException('The Migrate module is not enabled. Run "drush cim" or "drush deploy", then "drush cr", before retrying.');
    }

    $migration = $this->migrationPluginManager->createInstance(self::MIGRATION_ID, [
      'source' => [
        'file' => $path,
        'worksheet' => $worksheet,
      ],
    ]);
    if (!$migration instanceof MigrationInterface) {
      throw new \RuntimeException(sprintf(
        'Migration "%s" is unavailable. Import the migrate_plus.migration.%s configuration with "drush cim" before retrying.',
        self::MIGRATION_ID,
        self::MIGRATION_ID,
      ));
    }

    return $migration;
  }

  /**
   * Confirms migration destination fields are defined on the bundle.
   *
   * "Required" here means present in the entity definition, not configured as
   * required-value fields.
   *
   * @return void
   *
   * @throws \RuntimeException
   *   When the content type, vocabulary, mapped field definitions, or category
   *   field type does not match migration requirements.
   */
  private function validateDestinationFields(): void {
    if (!$this->entityTypeManager->getStorage('node_type')->load('in_memoriam')) {
      throw new \RuntimeException('The in_memoriam content type does not exist.');
    }
    if (!$this->entityTypeManager->getStorage('taxonomy_vocabulary')->load('in_memoriam')) {
      throw new \RuntimeException('The in_memoriam taxonomy vocabulary does not exist.');
    }

    $definitions = $this->entityFieldManager->getFieldDefinitions('node', 'in_memoriam');
    $missing = array_diff(self::REQUIRED_DESTINATION_FIELD_DEFINITIONS, array_keys($definitions));
    if ($missing) {
      throw new \RuntimeException(sprintf(
        'The in_memoriam content type is missing destination field definitions: %s.',
        implode(', ', $missing),
      ));
    }

    if ($definitions['field_category']->getType() !== 'entity_reference') {
      throw new \RuntimeException('field_category must be an entity reference field.');
    }
  }

  /**
   * Validates exact header names and returns the first worksheet name.
   *
   * Header comparison is case-sensitive and checks for every expected source
   * column. Additional workbook columns are permitted and ignored by the
   * migration source's configured `columns` list.
   *
   * @param string $path
   *   Absolute path to a readable XLSX workbook.
   *
   * @return string
   *   Name of the workbook's first worksheet.
   *
   * @throws \RuntimeException
   *   When a required header is absent.
   */
  private function validateWorkbook(string $path): string {
    $reader = IOFactory::createReaderForFile($path);
    $reader->setReadDataOnly(TRUE);
    $workbook = $reader->load($path);
    $worksheet = $workbook->getSheet(0);
    $worksheetName = $worksheet->getTitle();
    $highestDataColumn = $worksheet->getHighestDataColumn();
    $lastColumn = $highestDataColumn === NULL
      ? 0
      : Coordinate::columnIndexFromString($highestDataColumn);
    $headers = [];

    for ($column = 1; $column <= $lastColumn; $column++) {
      $columnName = Coordinate::stringFromColumnIndex($column);
      $headers[] = $worksheet->getCell($columnName . '1')->getValue();
    }

    foreach (self::REQUIRED_HEADERS as $header) {
      if (!in_array($header, $headers, TRUE)) {
        throw new \RuntimeException(sprintf('Workbook is missing the exact header "%s".', $header));
      }
    }

    return $worksheetName;
  }

}