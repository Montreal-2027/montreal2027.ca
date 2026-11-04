<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\migrate\process;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\migrate\Attribute\MigrateProcess;
use Drupal\migrate\MigrateException;
use Drupal\migrate\MigrateExecutableInterface;
use Drupal\migrate\ProcessPluginBase;
use Drupal\migrate\Row;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Resolves semicolon-delimited category names to taxonomy term references.
 *
 * The plugin looks for terms in the `in_memoriam` vocabulary and creates a
 * term when no exact, case-sensitive label match exists. It preserves source
 * order, considers only the first four non-empty names, and removes duplicate
 * names within that limited set. The result uses the `target_id` item structure
 * expected by an entity-reference field.
 *
 * An empty source value, a list containing no usable names, or multiple exact
 * matches for a name is treated as a migration error. This avoids silently
 * attaching an ambiguous term or creating a node without its required
 * category.
 */
#[MigrateProcess('in_memoriam_categories', handle_multiples: TRUE)]
final class InMemoriamCategories extends ProcessPluginBase implements ContainerFactoryPluginInterface {

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
   *   Provides taxonomy term storage for lookup and creation.
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
   * Resolves category names and returns taxonomy reference items.
   *
   * The first four non-empty, trimmed names are selected before duplicate
   * removal. Therefore repeated names among those four can yield fewer than
   * four references; later names do not replace duplicates.
   *
   * @param mixed $value
   *   The source Category cell, expected to contain names separated by
   *   semicolons.
   * @param \Drupal\migrate\MigrateExecutableInterface $migrate_executable
   *   The active migration executable. Included to satisfy the process-plugin
   *   interface; this transformation does not need to call it.
   * @param \Drupal\migrate\Row $row
   *   The source row currently being processed.
   * @param string $destination_property
   *   The destination property receiving the references.
   *
   * @return array
   *   A list of entity-reference field items, each containing a taxonomy term
   *   ID as `target_id`.
   *
   * @throws \Drupal\migrate\MigrateException
   *   When the source is empty, contains no usable names, or a name matches
   *   multiple exact labels in the target vocabulary.
   */
  public function transform($value, MigrateExecutableInterface $migrate_executable, Row $row, $destination_property): array {
    if (!is_string($value) || trim($value) === '') {
      throw new MigrateException('Category is required but the source row is empty.');
    }

    // Use Drupal's entity storage so term writes follow normal entity handling.
    $storage = $this->entityTypeManager->getStorage('taxonomy_term');
    $references = [];
    // Empty segments are ignored; whitespace around names is not significant.
    $names = array_filter(
      array_map('trim', explode(';', $value)),
      static fn (string $name): bool => $name !== '',
    );
    // Deduplicate first so repeated names do not consume the four-term limit.
    // array_unique preserves first-seen order, keeping the spreadsheet order.
    foreach (array_slice(array_unique($names), 0, 4) as $name) {
      // loadByProperties may match according to database collation; filter
      // labels again so the migration uses exact, case-sensitive name matches.
      $matches = array_filter(
        $storage->loadByProperties([
          'vid' => 'in_memoriam',
          'name' => $name,
        ]),
        static fn ($term): bool => $term->label() === $name,
      );

      if (count($matches) > 1) {
        throw new MigrateException(sprintf('Multiple In Memoriam terms match "%s".', $name));
      }

      // Reuse a unique match; otherwise create the missing vocabulary term.
      $term = $matches ? reset($matches) : $storage->create([
        'vid' => 'in_memoriam',
        'name' => $name,
      ]);
      if (!$term->id()) {
        $term->save();
      }

      // Entity-reference fields expect each target as a target_id item.
      $references[] = ['target_id' => $term->id()];
    }

    if (!$references) {
      throw new MigrateException('Category must contain at least one taxonomy term name.');
    }

    return $references;
  }

}
