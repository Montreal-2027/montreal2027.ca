<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Hook;

use Drupal\Core\Hook\Attribute\Hook;
use Drupal\montreal2027_tools\Menu\WorkspaceMenuTreeManipulator;

/**
 * Integrates workspace-aware child flags into Superfish menu trees.
 */
final class SuperfishHooks {

  /**
   * Constructs the Superfish hooks.
   *
   * @param \Drupal\montreal2027_tools\Menu\WorkspaceMenuTreeManipulator $manipulator
   *   The workspace menu tree manipulator.
   */
  public function __construct(
    protected readonly WorkspaceMenuTreeManipulator $manipulator,
  ) {}

  /**
   * Implements hook_superfish_tree_manipulators_alter().
   *
   * @param array $manipulators
   *   The Superfish menu tree manipulators.
   */
  #[Hook('superfish_tree_manipulators_alter')]
  public function treeManipulatorsAlter(array &$manipulators): void {
    // Superfish has already queued disabled-link filtering and access checks.
    $manipulators[] = [
      'callable' => [$this->manipulator, 'repairChildFlags'],
    ];
  }

}
