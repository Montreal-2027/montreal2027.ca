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
 * Normalizes supported In Memoriam death-date values.
 *
 * The spreadsheet can contain a month and year (`7/2026`), a complete
 * month/day/year date (`7/8/2026`), an ISO-style date, or descriptive text such
 * as `Summer`. Month and year values are returned as `YYYY-MM`; complete dates
 * are returned as `YYYY-MM-DD`. ISO dates and descriptive text pass through
 * unchanged.
 *
 * Blank values become NULL so optional destination fields remain empty.
 * Malformed numeric month or date values throw a migration exception instead
 * of being silently imported as if they were valid dates.
 */
#[MigrateProcess('in_memoriam_date')]
final class InMemoriamDate extends ProcessPluginBase implements MigrateProcessInterface {

  /**
   * {@inheritdoc}
   *
   * @param mixed $value
   *   The source Date of Death cell value.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable. The transformation does not need to
   *   call it, but it is required by the process-plugin interface.
   * @param \Drupal\migrate\Row $row
   *   The source row currently being processed.
   * @param string $destination_property
   *   The destination property receiving this value.
   *
   * @return string|null
   *   The normalized date or original descriptive text, or NULL for blank
   *   source values.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When a month or complete numeric date is invalid.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): ?string {
    $value = trim((string) $value);
    if ($value === '') {
      return NULL;
    }

    // Interpret month/year input as a month-level date, without inventing a day.
    if (preg_match('/^(\d{1,2})\/(\d{4})$/', $value, $matches)) {
      $month = (int) $matches[1];
      if ($month < 1 || $month > 12) {
        throw new MigrateException(sprintf('Invalid month in Date of Death: %s', $value));
      }

      return sprintf('%s-%02d', $matches[2], $month);
    }

    // Validate the calendar date before formatting it in ISO order.
    if (preg_match('/^(\d{1,2})\/(\d{1,2})\/(\d{4})$/', $value, $matches)) {
      $month = (int) $matches[1];
      $day = (int) $matches[2];
      $year = (int) $matches[3];
      if (!checkdate($month, $day, $year)) {
        throw new MigrateException(sprintf('Invalid Date of Death: %s', $value));
      }

      return sprintf('%04d-%02d-%02d', $year, $month, $day);
    }

    // Preserve ISO dates and non-date labels such as "Summer" as entered.
    return $value;
  }

}
