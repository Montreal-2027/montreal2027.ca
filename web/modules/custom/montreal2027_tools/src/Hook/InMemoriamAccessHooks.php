<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Hook;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Access\AccessResultInterface;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Hook\Attribute\Hook;
use Drupal\Core\Session\AccountInterface;
use Drupal\node\NodeInterface;

/**
 * Grants bundle-scoped access to unpublished In Memoriam entries.
 */
final class InMemoriamAccessHooks {

  /**
   * Implements hook_node_grants().
   */
  #[Hook('node_grants')]
  public function nodeGrants(AccountInterface $account, string $operation): array {
    if ($operation === 'view' && $account->isAuthenticated() && $account->hasPermission('view any unpublished in_memoriam content')) {
      return ['montreal2027_in_memoriam_unpublished' => [1]];
    }

    return [];
  }

  /**
   * Implements hook_node_access_records().
   */
  #[Hook('node_access_records')]
  public function nodeAccessRecords(NodeInterface $node): array {
    if ($node->bundle() !== 'in_memoriam' || $node->isPublished()) {
      return [];
    }

    // Grants cover both individual node access and access-checked listings.
    return [
      [
        'realm' => 'montreal2027_in_memoriam_unpublished',
        'gid' => 1,
        'grant_view' => 1,
        'grant_update' => 0,
        'grant_delete' => 0,
      ],
    ];
  }

  /**
   * Implements hook_entity_field_access().
   */
  #[Hook('entity_field_access')]
  public function entityFieldAccess(string $operation, FieldDefinitionInterface $field_definition, AccountInterface $account, ?FieldItemListInterface $items = NULL): AccessResultInterface {
    if ($operation !== 'edit' || $field_definition->getTargetEntityTypeId() !== 'node' || $field_definition->getName() !== 'status') {
      return AccessResult::neutral();
    }

    $node = $items?->getEntity();
    if (!$node instanceof NodeInterface || $node->bundle() !== 'in_memoriam') {
      return AccessResult::neutral();
    }

    return AccessResult::allowedIfHasPermission($account, 'publish in_memoriam content')
      ->addCacheableDependency($node);
  }

}
