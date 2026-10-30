<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\MigrateIdMapInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Resolves existing In Memoriam nodes by exact first and last names.
 *
 * The lookup only searches nodes in the `in_memoriam` bundle. An exact match
 * returns its node ID so the entity destination updates that node; no match
 * returns NULL so the destination creates a node. Multiple exact matches are
 * considered ambiguous and stop the row with a migration error.
 *
 * Before returning an existing ID, the plugin compares tracked destination
 * fields with the node's current field values. Equal values cause a silent row
 * skip, while any difference allows the migration destination to update the
 * existing node. Submission fields are excluded from change detection.
 *
 * Names that differ only by letter case are treated as the same person, never
 * as a new one, and the row is skipped with a warning-level migrate message
 * that the Drush command prints. Site edits are kept; the editor corrects the
 * spreadsheet and re-runs. This covers two situations:
 * - The row has no mapping, but a node matches ignoring case. Creating a node
 *   would duplicate that person (e.g. a first import on another site).
 * - The row is mapped to a node that matches ignoring case (see below).
 *
 * Migrate reuses a source row's previous destination node whenever the row is
 * already mapped, even if this plugin returns NULL. Without a name match, that
 * would overwrite an unrelated node's ID and crash on its UUID. Rows with no
 * exact name match but a previously mapped node are therefore handled first:
 * - Same name ignoring letter case and outer whitespace: an editor corrected
 *   the name in Drupal. The row is skipped with a warning, keeping the edit
 *   and the mapping.
 * - Different name: spreadsheet rows moved. The stale mapping is removed so a
 *   new node is created and the previously mapped node is left unchanged.
 */
#[MigrateProcess('in_memoriam_node_lookup')]
final class InMemoriamNodeLookup extends ProcessPluginBase implements ContainerFactoryPluginInterface {

  /**
   * Destination properties that determine whether an existing node changed.
   *
   * Track mapped spreadsheet fields and status because imports set status to
   * unpublished. Submission fields must not trigger an update, regardless of
   * their current values.
   */
  private const COMPARISON_FIELDS = [
    'title',
    'status',
    'field_first_name',
    'field_year_of_birth',
    'field_date_of_death',
    'field_sort_date',
    'field_category',
    'field_reason',
  ];

  /**
   * Creates the plugin with Drupal's entity storage manager.
   *
   * @param array $configuration
   *   The plugin configuration supplied by the migration process pipeline.
   * @param string $plugin_id
   *   The unique plugin ID.
   * @param mixed $plugin_definition
   *   The discovered plugin definition.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Provides node storage for matching existing entries.
   * @param \Drupal\migrate\Plugin\MigrationInterface $migration
   *   The running migration; its ID map holds previous row destinations.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly EntityTypeManagerInterface $entityTypeManager,
    private readonly MigrationInterface $migration,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * Creates an instance using Drupal's dependency injection container.
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   The service container.
   * @param array $configuration
   *   The plugin configuration supplied by the migration process pipeline.
   * @param string $plugin_id
   *   The unique plugin ID.
   * @param mixed $plugin_definition
   *   The discovered plugin definition.
   * @param \Drupal\migrate\Plugin\MigrationInterface|null $migration
   *   The running migration, passed by the migrate process plugin manager.
   *
   * @return static
   *   A process plugin instance with entity storage manager injected.
   *
   * @throws \LogicException
   *   When instantiated outside a migration.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition, ?MigrationInterface $migration = NULL): static {
    if ($migration === NULL) {
      throw new \LogicException('The in_memoriam_node_lookup process plugin requires a migration.');
    }

    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
      $migration,
    );
  }

  /**
   * Finds an exact existing node or skips an unchanged source row.
   *
   * The title and first-name query narrows candidate nodes. Exact string
   * comparisons are repeated in PHP to avoid database collation rules from
   * treating differently-cased titles as identical.
   *
   * @param mixed $value
   *   The initial process value. This plugin uses source-row values directly,
   *   so the value itself is not used.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable; records name-mismatch skip warnings.
   * @param \Drupal\migrate\Row $row
   *   The current source row with destination properties already processed.
   * @param string $destination_property
   *   The destination property receiving a matching node ID.
   *
   * @return int|null
   *   The matching node ID when fields changed, or NULL when no node matches.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When Last Name is empty or multiple existing nodes match the exact name.
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   When a matching node already has all tracked destination values, or a
   *   node matches only when letter case is ignored (warning saved first).
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): ?int {
    $lastName = trim((string) $row->getSourceProperty('Last Name'));
    $firstName = trim((string) $row->getSourceProperty('First Name'));
    if ($lastName === '') {
      throw new MigrateException('Last Name is required.');
    }

    // Match by bundle and title first; first name is checked below because it
    // is optional and its exact, trimmed value is part of the identity rule.
    $nodeStorage = $this->entityTypeManager->getStorage('node');
    $candidateIds = $nodeStorage->getQuery()
      ->accessCheck(FALSE)
      ->condition('type', 'in_memoriam')
      ->condition('title', $lastName)
      ->execute();

    $matches = [];
    $caseOnlyMatches = [];
    foreach ($nodeStorage->loadMultiple($candidateIds) as $candidate) {
      $candidateFirstName = trim((string) $candidate->get('field_first_name')->value);

      // Confirm case-sensitive equality independently of database collation.
      if ($candidate->getTitle() === $lastName && $candidateFirstName === $firstName) {
        $matches[] = $candidate;
        continue;
      }

      // Drupal's entity query compares string fields case-insensitively on
      // every supported database driver, so the candidates also include nodes
      // whose name differs only by letter case. Track those separately: they
      // are almost certainly the same person, edited in Drupal or typed with
      // different capitalisation in the spreadsheet.
      if (mb_strtolower(trim((string) $candidate->getTitle())) === mb_strtolower($lastName)
        && mb_strtolower($candidateFirstName) === mb_strtolower($firstName)) {
        $caseOnlyMatches[] = $candidate;
      }
    }

    if (count($matches) > 1) {
      throw new MigrateException(sprintf(
        'More than one In Memoriam node matches "%s %s". Resolve duplicate nodes before importing.',
        $firstName,
        $lastName,
      ));
    }

    if (!$matches) {
      $this->handlePreviousDestination($migrate_executable, $row, $firstName, $lastName);

      // No exact match and no mapped node for this row, but an existing node
      // has the same name ignoring case. Creating a node here would duplicate
      // that person (e.g. a first import on a site whose nodes were edited),
      // so skip with a visible warning and let the editor fix the spreadsheet.
      if ($caseOnlyMatches) {
        $this->skipWithWarning($migrate_executable, sprintf(
          'Row skipped: name "%s %s" differs only by letter case from %s. Correct the First Name or Last Name cell to match and re-run the import.',
          $firstName,
          $lastName,
          implode(', ', array_map(
            static fn($node): string => sprintf(
              'node %d ("%s %s")',
              $node->id(),
              trim((string) $node->get('field_first_name')->value),
              trim((string) $node->getTitle()),
            ),
            $caseOnlyMatches,
          )),
        ));
      }

      // No destination ID tells the entity migration destination to create.
      return NULL;
    }

    $node = reset($matches);
    $this->repointStaleMapping($row, (int) $node->id());

    // Compare normalized field item values with already-processed migration
    // values, so empty values and scalar representations compare consistently.
    foreach (self::COMPARISON_FIELDS as $fieldName) {
      $expected = $this->normalize($row->getDestinationProperty($fieldName));
      $actual = $this->normalize($node->get($fieldName)->getValue());
      if ($expected !== $actual) {
        return (int) $node->id();
      }
    }

    // Do not write an ignored mapping: existing migrations must retain their
    // destination ID so a later changed spreadsheet row can update the node.
    throw new MigrateSkipRowException(sprintf(
      'No changes for In Memoriam node "%s %s".',
      $firstName,
      $lastName,
    ), FALSE);
  }

  /**
   * Points this row's map entry at the node its name exactly matches.
   *
   * Source IDs are spreadsheet row numbers. If an editor sorts, inserts or
   * deletes rows, a row's map entry can still point at the node of whoever
   * used to occupy that row. MigrateExecutable passes that old destination ID
   * to the entity destination, which loads that node in preference to the
   * `nid` returned by this plugin, then writes this row's values (including
   * the matched `nid`) onto it. The wrong person's node would be overwritten,
   * or the save would fail.
   *
   * Re-pointing the map entry before the destination runs makes the matched
   * node the old destination, so the name, not the row number, decides which
   * node is updated. It also keeps the mapping correct when the row is then
   * skipped as unchanged. The node previously mapped is not modified.
   *
   * @param \Drupal\migrate\Row $row
   *   The current source row; its ID map holds any previous destination.
   * @param int $nodeId
   *   The node whose first and last names exactly match the row.
   */
  private function repointStaleMapping(Row $row, int $nodeId): void {
    $idMapRow = $row->getIdMap();
    $previousId = $idMapRow['destid1'] ?? NULL;

    // Unmapped rows let the destination use the returned `nid`; rows already
    // mapped to the matched node need nothing.
    if ($previousId === NULL || $previousId === '' || (int) $previousId === $nodeId) {
      return;
    }

    // Keep the existing status and rollback action; only the destination
    // changes. prepareUpdate() resets every status on the next run anyway.
    $this->migration->getIdMap()->saveIdMapping(
      $row,
      [$nodeId],
      (int) ($idMapRow['source_row_status'] ?? MigrateIdMapInterface::STATUS_NEEDS_UPDATE),
      (int) ($idMapRow['rollback_action'] ?? MigrateIdMapInterface::ROLLBACK_DELETE),
    );
  }

  /**
   * Resolves a stale row mapping when no node matches the row's exact name.
   *
   * MigrateExecutable looks up the row's previous destination after the
   * process pipeline runs and passes it to the entity destination, which then
   * loads and updates that node regardless of the NULL `nid` returned here.
   * Because the process pipeline has replaced `nid` with NULL, saving that
   * node would attempt an insert with the existing UUID and fail.
   *
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable; records skip warnings.
   * @param \Drupal\migrate\Row $row
   *   The current source row; its ID map holds any previous destination.
   * @param string $firstName
   *   The trimmed spreadsheet first name, possibly empty.
   * @param string $lastName
   *   The trimmed spreadsheet last name.
   *
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   When the previously mapped node is the same person with a name that
   *   differs only by letter case. A warning is saved first and the mapping
   *   is kept for future runs.
   */
  private function handlePreviousDestination(MigrateExecutableInterface $migrate_executable, Row $row, string $firstName, string $lastName): void {
    $previousId = $row->getIdMap()['destid1'] ?? NULL;
    if ($previousId === NULL || $previousId === '') {
      return;
    }

    $previous = $this->entityTypeManager->getStorage('node')->load($previousId);
    if ($previous === NULL) {
      // The destination will create a node because the old one cannot load.
      return;
    }

    $previousFirstName = trim((string) $previous->get('field_first_name')->value);
    $previousLastName = trim((string) $previous->getTitle());
    if (mb_strtolower($previousFirstName) === mb_strtolower($firstName)
      && mb_strtolower($previousLastName) === mb_strtolower($lastName)) {
      // The helper skips without saving to the map; keeping the destination
      // preserves the link if the spreadsheet is later corrected to match.
      $this->skipWithWarning($migrate_executable, sprintf(
        'Row skipped: name "%s %s" differs only by letter case from node %d ("%s %s"). Site edits kept; correct the First Name or Last Name cell to match and re-run the import.',
        $firstName,
        $lastName,
        $previous->id(),
        $previousFirstName,
        $previousLastName,
      ));
    }

    // A different person now occupies this spreadsheet row. Remove the old
    // mapping so the destination creates a node instead of updating one.
    $this->migration->getIdMap()->delete($row->getSourceIdValues());
  }

  /**
   * Records an on-screen warning for a name mismatch, then skips the row.
   *
   * Core saves a non-empty skip message at INFORMATIONAL level, which the
   * Drush command does not print. Saving a WARNING here and skipping with an
   * empty message makes the skip visible without a duplicate entry. Passing
   * FALSE keeps any existing map entry so a corrected row still updates.
   *
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable.
   * @param string $message
   *   The complete warning text shown to the editor.
   *
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   Always.
   */
  private function skipWithWarning(MigrateExecutableInterface $migrate_executable, string $message): never {
    $migrate_executable->saveMessage($message, MigrationInterface::MESSAGE_WARNING);
    throw new MigrateSkipRowException('', FALSE);
  }

  /**
   * Normalizes process and field item values for strict comparison.
   *
   * Process values may be scalars or lists of field-item arrays, while entity
   * fields return lists of item arrays. This method converts both forms into
   * ordered arrays, removes empty properties, stringifies scalar values, and
   * sorts item keys. That avoids false change detections caused only by
   * representation differences such as an integer versus its string form.
   *
   * @param mixed $value
   *   A destination value from the migration row or a field's `getValue()`.
   *
   * @return array
   *   A normalized list of non-empty field item values.
   */
  private function normalize(mixed $value): array {
    if ($value === NULL || $value === '') {
      return [];
    }

    // Wrap scalar values as a single field item for common handling below.
    if (!is_array($value)) {
      $value = ['value' => $value];
    }
    // A single associative field item becomes a one-item list.
    if (!array_is_list($value)) {
      $value = [$value];
    }

    $normalized = [];
    foreach ($value as $item) {
      if (!is_array($item)) {
        $item = ['value' => $item];
      }

      foreach ($item as $key => $itemValue) {
        if ($itemValue === NULL || $itemValue === '') {
          unset($item[$key]);
        }
        elseif (is_scalar($itemValue)) {
          // Entity field storage and process pipelines can use different
          // scalar types for equivalent values; compare their string forms.
          $item[$key] = (string) $itemValue;
        }
      }
      if ($item) {
        // Field item key order is not meaningful.
        ksort($item);
        $normalized[] = $item;
      }
    }

    return $normalized;
  }

}
