<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\Plugin\MigrateProcessInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Builds a complete `YYYY-MM-DD` sort date for an In Memoriam entry.
 *
 * Source must be a two-item list: the Sort Date cell, then the Date of Death
 * cell. The first non-blank value is normalized with the same rules as Date of
 * Death (see InMemoriamDate::normalize()). The result is then completed:
 * - `YYYY-MM-DD` is returned unchanged.
 * - `YYYY-MM` becomes the last day of that month, e.g. `2026-10-31`.
 *
 * Descriptive Date of Death text, such as `Summer`, cannot be sorted; with no
 * explicit Sort Date the field stays empty.
 *
 * An invalid date in either column is an editor typo, not a system failure.
 * The row is skipped with a warning-level migrate message naming the bad
 * column, which the Drush command prints, so the rest of the workbook still
 * imports. The skip is not saved to the map: an existing node keeps its
 * mapping, and the corrected row is processed on the next run.
 *
 * Example:
 * @code
 * field_sort_date:
 *   plugin: in_memoriam_sort_date
 *   source:
 *     - 'Sort Date'
 *     - 'Date of Death'
 * @endcode
 */
#[MigrateProcess('in_memoriam_sort_date')]
final class InMemoriamSortDate extends ProcessPluginBase implements MigrateProcessInterface {

  /**
   * {@inheritdoc}
   *
   * @param mixed $value
   *   A list containing the Sort Date and Date of Death source values.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable; records skip warnings.
   * @param \Drupal\migrate\Row $row
   *   The source row currently being processed.
   * @param string $destination_property
   *   The destination property receiving this value.
   *
   * @return string|null
   *   A complete `YYYY-MM-DD` date, or NULL when no sortable date exists.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When the source is misconfigured.
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   When the Sort Date, or the fallback Date of Death, is not a valid date.
   *   A warning is saved first.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): ?string {
    if (!is_array($value) || count($value) !== 2) {
      throw new MigrateException('in_memoriam_sort_date requires a source list of Sort Date and Date of Death.');
    }
    [$sortDate, $dateOfDeath] = array_values($value);

    // An explicit Sort Date always wins. Blank cells arrive as NULL or
    // whitespace, so trim before deciding to fall back to Date of Death.
    $explicit = trim((string) $sortDate) !== '';
    $column = $explicit ? 'Sort Date' : 'Date of Death';

    try {
      return $this->complete($explicit ? $sortDate : $dateOfDeath, $explicit);
    }
    catch (MigrateException $e) {
      // Both columns are soft failures: warn, skip this row, keep importing.
      // The message names whichever column actually holds the bad value.
      InMemoriamDate::skipWithWarning($migrate_executable, $e, $column);
    }
  }

  /**
   * Normalizes a date value and completes month-level dates.
   *
   * @param mixed $value
   *   The Sort Date or Date of Death source value.
   * @param bool $explicit
   *   TRUE when the value came from the Sort Date column. Non-date text is an
   *   error only for explicit Sort Dates.
   *
   * @return string|null
   *   A complete `YYYY-MM-DD` date, or NULL when no sortable date exists.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When the value is an invalid date, or explicit non-date text.
   */
  private function complete(mixed $value, bool $explicit): ?string {
    $date = InMemoriamDate::normalize($value, $explicit ? 'Sort Date' : 'Date of Death');
    if ($date === NULL) {
      return NULL;
    }

    // Already complete. normalize() has validated the calendar date.
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $date)) {
      return $date;
    }

    // Month-level date: append the month's last day. normalize() has validated
    // the month. The `t` format character accounts for month length and leap
    // years (2028-02 => 2028-02-29).
    if (preg_match('/^(\d{4})-(\d{2})$/', $date, $matches)) {
      $firstDay = new \DateTimeImmutable(sprintf('%s-%s-01', $matches[1], $matches[2]));
      return $firstDay->format('Y-m-t');
    }

    // Non-date text. Only an explicit Sort Date is an error; a descriptive
    // Date of Death simply has no sortable value.
    if ($explicit) {
      throw new MigrateException(sprintf('Sort Date is not a recognized date: %s', $date));
    }

    return NULL;
  }

}
