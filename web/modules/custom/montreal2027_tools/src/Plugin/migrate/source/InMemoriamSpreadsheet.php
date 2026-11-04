<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\source;

use Drupal\migrate\Attribute\MigrateSource;
use Drupal\migrate\MigrateException;
use Drupal\migrate\Row;
use Drupal\migrate_spreadsheet\Plugin\migrate\source\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Shared\Date;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Spreadsheet source that returns date cells as their displayed text.
 *
 * The contrib `spreadsheet` source loads workbooks in read-data-only mode and
 * returns raw cell values. Excel stores dates as serial numbers (for example,
 * `46214` for 11 July 2026), and the number format decides whether the cell
 * displays `7/11/2026` or `7/2026`. Read-data-only mode discards those
 * formats, so month-only and full dates become indistinguishable.
 *
 * This source loads cell styles and, for columns listed in
 * `formatted_columns`, replaces date-formatted cell values with the text Excel
 * displays. Process plugins then receive `7/2026` or `7/11/2026` exactly as
 * the editor sees them. Non-date cells, such as `Summer` or ISO text, are left
 * untouched.
 *
 * Additional configuration:
 * - formatted_columns: Header names whose date cells should be returned as
 *   displayed text.
 */
#[MigrateSource(id: 'in_memoriam_spreadsheet')]
final class InMemoriamSpreadsheet extends Spreadsheet {

  /**
   * {@inheritdoc}
   *
   * Adds the `formatted_columns` option to the contrib defaults.
   */
  public function defaultConfiguration(): array {
    return parent::defaultConfiguration() + [
      'formatted_columns' => [],
    ];
  }

  /**
   * {@inheritdoc}
   *
   * Replaces raw date serials with displayed text before the parent computes
   * the change-tracking hash, so the hash reflects the imported value.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When a configured formatted column is missing from the header row.
   */
  public function prepareRow(Row $row) {
    // Formatting a whole column in Excel extends the worksheet's used range,
    // so the iterator returns trailing rows with no values. Skip them before
    // processing; otherwise required-field plugins mark them as failed.
    if ($this->isBlankRow($row)) {
      // Remove map entries left by earlier runs that tried to import this
      // blank row, so they no longer report as failed in migrate:status.
      $this->idMap->delete($row->getSourceIdValues());
      return FALSE;
    }

    $columns = $this->getConfiguration()['formatted_columns'];
    if ($columns) {
      $iterator = $this->initializeIterator();
      $worksheet = $iterator->getWorksheet();
      $headers = $iterator->getHeaders();

      // The row index column holds the absolute worksheet row number.
      $rowNumber = $row->getSourceProperty($iterator->getRowIndexColumn());

      foreach ($columns as $column) {
        if (!isset($headers[$column])) {
          throw new MigrateException(sprintf('Formatted column "%s" is not a worksheet header.', $column));
        }

        // Do not create missing cells; blank cells keep their NULL value.
        $cell = $worksheet->getCell($headers[$column] . $rowNumber, FALSE);
        if ($cell !== NULL && is_numeric($cell->getValue()) && Date::isDateTime($cell)) {
          $row->setSourceProperty($column, $cell->getFormattedValue());
        }
      }
    }

    return parent::prepareRow($row);
  }

  /**
   * Determines whether every configured data column in a row is blank.
   *
   * The synthetic row-index column is excluded because it always has a value.
   *
   * @param \Drupal\migrate\Row $row
   *   The source row being prepared.
   *
   * @return bool
   *   TRUE when all configured columns are NULL or whitespace-only strings.
   */
  private function isBlankRow(Row $row): bool {
    foreach ($this->getConfiguration()['columns'] as $column) {
      $value = $row->getSourceProperty($column);
      if ($value !== NULL && trim((string) $value) !== '') {
        return FALSE;
      }
    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   *
   * Mirrors the parent loader but keeps cell styles. The parent enables
   * read-data-only mode, which drops the number formats needed to tell
   * `m/yyyy` from `m/d/yyyy` date cells.
   */
  protected function loadWorksheet(): Worksheet {
    $config = $this->getConfiguration();

    if (!file_exists($config['file'])) {
      throw new MigrateException("File with path '{$config['file']}' doesn't exist.");
    }
    if (empty($config['worksheet'])) {
      throw new MigrateException('No worksheet was passed.');
    }

    try {
      $filePath = $this->fileSystem->realpath($config['file']);
      $reader = IOFactory::createReader(IOFactory::identify($filePath));

      // Matches the contrib workaround for CSV delimiter auto-detection.
      if ($reader instanceof Csv) {
        $reader->setTestAutoDetect(FALSE);
      }

      // Styles are required for date-format detection; only load one sheet.
      $reader->setReadDataOnly(FALSE);
      $reader->setLoadSheetsOnly($config['worksheet']);

      return $reader->load($filePath)->getSheet(0);
    }
    catch (\Exception $e) {
      throw new MigrateException(sprintf("Got '%s', message '%s'.", get_class($e), $e->getMessage()));
    }
  }

}
