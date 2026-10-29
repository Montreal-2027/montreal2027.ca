<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\MigrateSkipRowException;
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
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    private readonly EntityTypeManagerInterface $entityTypeManager,
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
   *
   * @return static
   *   A process plugin instance with entity storage manager injected.
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('entity_type.manager'),
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
   *   The active migration executable. Included to satisfy the process-plugin
   *   interface; no executable methods are needed here.
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
   *   When a matching node already has all tracked destination values.
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
    foreach ($nodeStorage->loadMultiple($candidateIds) as $candidate) {
      // Confirm case-sensitive equality independently of database collation.
      if ($candidate->getTitle() !== $lastName) {
        continue;
      }

      $candidateFirstName = trim((string) $candidate->get('field_first_name')->value);
      if ($candidateFirstName === $firstName) {
        $matches[] = $candidate;
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
      // No destination ID tells the entity migration destination to create.
      return NULL;
    }

    $node = reset($matches);
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
