<?php

declare(strict_types=1);

namespace Drupal\Tests\montreal2027_tools\Unit;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Menu\MenuActiveTrailInterface;
use Drupal\Core\Menu\MenuLinkInterface;
use Drupal\Core\Menu\MenuLinkTreeElement;
use Drupal\Core\Path\PathMatcherInterface;
use Drupal\Core\Routing\UrlGeneratorInterface;
use Drupal\Core\Url;
use Drupal\montreal2027_tools\Hook\SuperfishHooks;
use Drupal\montreal2027_tools\Menu\WorkspaceMenuTreeManipulator;
use Drupal\workspaces\WorkspaceManagerInterface;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Tests workspace-aware Superfish menu child flags.
 */
#[Group('montreal2027_tools')]
final class WorkspaceMenuTreeManipulatorTest extends TestCase {

  /**
   * Tests the first workspace-only child repairs a stale parent flag.
   */
  public function testWorkspaceOnlyChild(): void {
    $child = $this->element();
    $parent = $this->element(['child' => $child]);
    $tree = $this->manipulator()->repairChildFlags(['parent' => $parent]);

    $this->assertTrue($tree['parent']->hasChildren);
    $this->assertFalse($child->hasChildren);
    $this->assertSame($child, $tree['parent']->subtree['child']);
  }

  /**
   * Tests nested workspace-only submenus are repaired recursively.
   */
  public function testNestedChildren(): void {
    $child = $this->element(['grandchild' => $this->element()]);
    $parent = $this->element(['child' => $child]);
    $this->manipulator()->repairChildFlags(['parent' => $parent]);

    $this->assertTrue($parent->hasChildren);
    $this->assertTrue($child->hasChildren);
  }

  /**
   * Tests inaccessible and disabled links cannot create a visible submenu.
   */
  public function testHiddenChildren(): void {
    $denied = $this->element();
    $denied->access = $this->createMock(AccessResult::class);
    $denied->access->method('isAllowed')->willReturn(FALSE);
    $denied->access->method('getCacheContexts')->willReturn(['user.permissions']);
    $neutral = $this->element();
    $neutral->access = AccessResult::neutral();
    $disabled = $this->element(enabled: FALSE);
    $parent = $this->element([
      'denied' => $denied,
      'neutral' => $neutral,
      'disabled' => $disabled,
    ], TRUE);
    $this->manipulator()->repairChildFlags(['parent' => $parent]);

    $this->assertFalse($parent->hasChildren);
    $this->assertSame($denied, $parent->subtree['denied']);
    $this->assertSame(['user.permissions'], $denied->access->getCacheContexts());
    $this->assertCount(3, $parent->subtree);
  }

  /**
   * Tests an accessible sibling still allows the submenu.
   */
  public function testAccessibleSibling(): void {
    $denied = $this->element();
    $denied->access = AccessResult::forbidden();
    $parent = $this->element([
      'denied' => $denied,
      'allowed' => $this->element(),
    ]);
    $this->manipulator()->repairChildFlags(['parent' => $parent]);

    $this->assertTrue($parent->hasChildren);
    $this->assertCount(2, $parent->subtree);
  }

  /**
   * Tests Live trees are unchanged.
   */
  public function testLiveTreeUnchanged(): void {
    $parent = $this->element(['child' => $this->element()]);
    $tree = ['parent' => $parent];

    $this->assertSame($tree, $this->manipulator(FALSE)->repairChildFlags($tree));
    $this->assertFalse($parent->hasChildren);
  }

  /**
   * Tests the module also works without Workspaces installed.
   */
  public function testWithoutWorkspaces(): void {
    $parent = $this->element(['child' => $this->element()]);
    $tree = ['parent' => $parent];

    $this->assertSame($tree, (new WorkspaceMenuTreeManipulator(NULL))->repairChildFlags($tree));
    $this->assertFalse($parent->hasChildren);
  }

  /**
   * Tests depth-limited child hints and empty trees are preserved.
   */
  public function testDepthLimitAndEmptyTree(): void {
    $parent = $this->element(has_children: TRUE);
    $manipulator = $this->manipulator();
    $manipulator->repairChildFlags(['parent' => $parent]);

    $this->assertTrue($parent->hasChildren);
    $this->assertSame([], $parent->subtree);
    $this->assertSame([], $manipulator->repairChildFlags([]));
  }

  /**
   * Tests the hook queues repair after Superfish's existing manipulators.
   */
  public function testHookIntegration(): void {
    $manipulator = $this->manipulator();
    $existing = [
      ['callable' => 'superfish.menu_tree_manipulator:filterDisabledLinks'],
      ['callable' => 'menu.default_tree_manipulators:checkAccess'],
      ['callable' => 'menu.default_tree_manipulators:generateIndexAndSort'],
    ];
    $manipulators = $existing;
    (new SuperfishHooks($manipulator))->treeManipulatorsAlter($manipulators);

    $this->assertSame($existing, array_slice($manipulators, 0, 3));
    $this->assertSame([$manipulator, 'repairChildFlags'], $manipulators[3]['callable']);
    $parent = $this->element(['child' => $this->element()]);
    $tree = $manipulators[3]['callable'](['parent' => $parent]);
    $this->assertTrue($tree['parent']->hasChildren);
  }

  /**
   * Tests Superfish actually produces a submenu after the flag is repaired.
   */
  public function testSuperfishSubmenuOutput(): void {
    require_once dirname(__DIR__, 5) . '/contrib/superfish/superfish.theme.inc';

    $container = new ContainerBuilder();
    $active_trail = $this->createMock(MenuActiveTrailInterface::class);
    $active_trail->method('getActiveTrailIds')->willReturn([]);
    $container->set('menu.active_trail', $active_trail);
    $path_matcher = $this->createMock(PathMatcherInterface::class);
    $path_matcher->method('isFrontPage')->willReturn(FALSE);
    $container->set('path.matcher', $path_matcher);
    $url_generator = $this->createMock(UrlGeneratorInterface::class);
    $url_generator->method('generateFromRoute')->willReturn('/');
    $container->set('url_generator', $url_generator);
    $request_stack = new RequestStack();
    $request_stack->push(Request::create('https://example.com/'));
    $container->set('request_stack', $request_stack);
    \Drupal::setContainer($container);

    try {
      $child = $this->element();
      $parent = $this->element(['child' => $child]);
      $variables = [
        'element' => [
          '#menu_name' => 'main',
          '#tree' => ['parent' => $parent],
          '#cloned_parent' => FALSE,
          '#settings' => [
            'expanded' => 0,
            'expand_all_items' => FALSE,
            'itemdepth' => 0,
            'liclass' => '',
            'hlclass' => '',
            'add_linkdescription' => 0,
            'hide_linkdescription' => 0,
            'multicolumn' => 0,
            'clone_parent' => 0,
            'link_text_prefix' => '',
            'link_text_suffix' => '',
          ],
        ],
      ];
      template_preprocess_superfish_menu_items($variables);
      $this->assertSame('', $variables['menu_items'][0]['children']);

      $this->manipulator()->repairChildFlags($variables['element']['#tree']);
      template_preprocess_superfish_menu_items($variables);
      $this->assertSame(
        ['child' => $child],
        $variables['menu_items'][0]['children']['#tree'],
      );

      $variables['element']['#settings']['expanded'] = 1;
      template_preprocess_superfish_menu_items($variables);
      $this->assertSame('', $variables['menu_items'][0]['children']);
    }
    finally {
      \Drupal::unsetContainer();
    }
  }

  /**
   * Creates a manipulator with a mocked active workspace state.
   *
   * @param bool $active
   *   Whether a workspace is active.
   *
   * @return \Drupal\montreal2027_tools\Menu\WorkspaceMenuTreeManipulator
   *   The workspace menu tree manipulator.
   */
  private function manipulator(bool $active = TRUE): WorkspaceMenuTreeManipulator {
    $manager = $this->createMock(WorkspaceManagerInterface::class);
    $manager->method('hasActiveWorkspace')->willReturn($active);
    return new WorkspaceMenuTreeManipulator($manager);
  }

  /**
   * Creates an enabled, accessible menu element by default.
   *
   * @param \Drupal\Core\Menu\MenuLinkTreeElement[] $children
   *   The loaded children.
   * @param bool $has_children
   *   The stored child flag.
   * @param bool $enabled
   *   Whether the link is enabled.
   *
   * @return \Drupal\Core\Menu\MenuLinkTreeElement
   *   The menu tree element.
   */
  private function element(array $children = [], bool $has_children = FALSE, bool $enabled = TRUE): MenuLinkTreeElement {
    $link = $this->createMock(MenuLinkInterface::class);
    $link->method('isEnabled')->willReturn($enabled);
    $link->method('getPluginDefinition')->willReturn([
      'id' => 'test',
      'enabled' => $enabled,
      'expanded' => FALSE,
      'options' => [],
    ]);
    $link->method('getTitle')->willReturn('Test link');
    $link->method('getDescription')->willReturn('');
    $link->method('getUrlObject')->willReturn(Url::fromUri('https://example.com/test'));
    $element = new MenuLinkTreeElement($link, $has_children, 1, FALSE, $children);
    $element->access = AccessResult::allowed();
    return $element;
  }

}
