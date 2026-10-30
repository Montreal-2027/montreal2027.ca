<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
use Drupal\migrate\Plugin\MigrateProcessInterface;
use Drupal\migrate\Plugin\MigrationInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Normalizes supported In Memoriam death-date values.
 *
 * The spreadsheet can contain a month and year (`7/2026`), a complete
 * month/day/year date (`7/8/2026`), an ISO-style date, or descriptive text such
 * as `Summer`. Month and year values are returned as `YYYY-MM`; complete dates
 * are returned as `YYYY-MM-DD`. ISO dates and descriptive text pass through
 * unchanged.
 *
 * Blank values become NULL so optional destination fields remain empty.
 * Malformed numeric month or date values (including ISO-style values such as
 * `2027-02-30`) are never imported as if they were valid dates. Date of Death
 * is optional and a bad value is an editor typo, so the row is skipped with a
 * warning-level migrate message, which the Drush command prints. The rest of
 * the workbook still imports, and the corrected row is processed next run.
 */
#[MigrateProcess('in_memoriam_date')]
final class InMemoriamDate extends ProcessPluginBase implements MigrateProcessInterface {

  /**
   * {@inheritdoc}
   *
   * @param mixed $value
   *   The source Date of Death cell value.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable; records skip warnings.
   * @param \Drupal\migrate\Row $row
   *   The source row currently being processed.
   * @param string $destination_property
   *   The destination property receiving this value.
   *
   * @return string|null
   *   The normalized date or original descriptive text, or NULL for blank
   *   source values.
   *
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   When the Date of Death is an invalid date. A warning is saved first.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): ?string {
    try {
      return self::normalize($value);
    }
    catch (MigrateException $e) {
      self::skipWithWarning($migrate_executable, $e, 'Date of Death');
    }
  }

  /**
   * Records a warning for a bad date cell, then skips the row.
   *
   * Shared by both date plugins so Date of Death and Sort Date typos behave
   * identically: a soft failure that leaves the rest of the workbook alone.
   *
   * Core saves a non-empty skip message at INFORMATIONAL level, which the
   * command does not print. Saving a WARNING here and skipping with an empty
   * message avoids that duplicate. Passing FALSE keeps any existing map entry,
   * so an already imported node stays mapped and updates once corrected.
   *
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable.
   * @param \Drupal\migrate\MigrateException $e
   *   The validation error from normalize().
   * @param string $column
   *   Spreadsheet column the editor must correct.
   *
   * @throws \Drupal\migrate\MigrateSkipRowException
   *   Always.
   */
  public static function skipWithWarning(MigrateExecutableInterface $migrate_executable, MigrateException $e, string $column): never {
    $migrate_executable->saveMessage(sprintf(
      'Row skipped: %s. Correct the %s cell and re-run the import.',
      $e->getMessage(),
      $column,
    ), MigrationInterface::MESSAGE_WARNING);
    throw new MigrateSkipRowException('', FALSE);
  }

  /**
   * Normalizes one In Memoriam date value.
   *
   * Shared with the `in_memoriam_sort_date` process plugin so Date of Death
   * and Sort Date always follow identical parsing rules.
   *
   * @param mixed $value
   *   A spreadsheet cell value.
   * @param string $label
   *   Column name used in error messages, so each error names the cell that
   *   actually holds the bad value.
   *
   * @return string|null
   *   `YYYY-MM`, `YYYY-MM-DD`, the original descriptive text, or NULL for
   *   blank input.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When a slash or ISO-style month or complete date is invalid.
   */
  public static function normalize(mixed $value, string $label = 'Date of Death'): ?string {
    $value = trim((string) $value);
    if ($value === '') {
      return NULL;
    }

    // Interpret month/year input as a month-level date, without inventing a day.
    if (preg_match('/^(\d{1,2})\/(\d{4})$/', $value, $matches)) {
      $month = (int) $matches[1];
      if ($month < 1 || $month > 12) {
        throw new MigrateException(sprintf('Invalid month in %s: %s', $label, $value));
      }

      return sprintf('%s-%02d', $matches[2], $month);
    }

    // Validate the calendar date before formatting it in ISO order.
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
      $month = (int) $matches[1];
      $day = (int) $matches[2];
      $year = (int) $matches[3];
      if (!checkdate($month, $day, $year)) {
        throw new MigrateException(sprintf('Invalid %s: %s', $label, $value));
      }

      return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    // ISO dates are kept as entered, but only if they are real calendar dates.
    // Validating here, with the caller's label, keeps error wording correct
    // for both Date of Death and Sort Date.
    if (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value, $matches)) {
      if (!checkdate((int) $matches[2], (int) $matches[3], (int) $matches[1])) {
        throw new MigrateException(sprintf('Invalid %s: %s', $label, $value));
      }

      return $value;
    }

    // ISO month-level values: kept as entered once the month is checked.
    if (preg_match('/^(\d{4})-(\d{2})$/', $value, $matches)) {
      $month = (int) $matches[2];
      if ($month < 1 || $month > 12) {
        throw new MigrateException(sprintf('Invalid month in %s: %s', $label, $value));
      }

      return $value;
    }

    // Preserve non-date labels such as "Summer" as entered.
    return $value;
  }

}
