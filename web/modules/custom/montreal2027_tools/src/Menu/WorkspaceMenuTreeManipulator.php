<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Menu;

use Drupal\workspaces\WorkspaceManagerInterface;

/**
 * Repairs Superfish child flags for links visible only in a workspace.
 */
final class WorkspaceMenuTreeManipulator {

  /**
   * Constructs the workspace menu tree manipulator.
   *
   * @param \Drupal\workspaces\WorkspaceManagerInterface|null $workspaceManager
   *   The workspace manager, or NULL when Workspaces is not installed.
   */
  public function __construct(
    protected readonly ?WorkspaceManagerInterface $workspaceManager,
  ) {}

  /**
   * Repairs loaded child flags after disabled-link filtering and access checks.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeElement[] $tree
   *   The access-checked Superfish menu tree.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeElement[]
   *   The tree with workspace-aware child flags.
   */
  public function repairChildFlags(array $tree): array {
    if (!$this->workspaceManager?->hasActiveWorkspace()) {
      return $tree;
    }

    foreach ($tree as $element) {
      // An empty subtree can mean children were excluded by the depth limit.
      if (!$element->subtree) {
        continue;
      }

      $element->subtree = $this->repairChildFlags($element->subtree);
      $element->hasChildren = FALSE;
      foreach ($element->subtree as $child) {
        if ($child->link->isEnabled() && $child->access->isAllowed()) {
          $element->hasChildren = TRUE;
          break;
        }
      }
    }

    return $tree;
  }

}
