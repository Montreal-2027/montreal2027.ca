<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;

/**
 * Trims leading and trailing whitespace from In Memoriam text values.
 *
 * Spreadsheet cells can contain padding around names and year strings. This
 * plugin casts NULL and other scalar-like values to strings before trimming,
 * so a blank optional cell becomes an empty string rather than causing a type
 * error. Required-value validation, such as the Last Name check, is handled
 * separately by the relevant migration process plugin.
 */
#[MigrateProcess('in_memoriam_trim')]
final class InMemoriamTrim extends ProcessPluginBase {

  /**
   * {@inheritdoc}
   *
   * @param mixed $value
   *   The source text value to normalize.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable. Included to satisfy the process-plugin
   *   interface; no executable services are needed for trimming.
   * @param \Drupal\migrate\Row $row
   *   The source row currently being processed.
   * @param string $destination_property
   *   The destination property receiving the trimmed value.
   *
   * @return string
   *   The value converted to a string and trimmed at both ends.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): string {
    return trim((string) $value);
  }

}
