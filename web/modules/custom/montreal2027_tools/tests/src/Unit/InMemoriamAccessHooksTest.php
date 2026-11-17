<?php

declare(strict_types=1);

namespace Drupal\Tests\montreal2027_tools\Unit;

use Drupal\Core\Cache\Context\CacheContextsManager;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\montreal2027_tools\Hook\InMemoriamAccessHooks;
use Drupal\node\NodeInterface;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Tests bundle-scoped unpublished access and publication controls.
 */
#[Group('montreal2027_tools')]
final class InMemoriamAccessHooksTest extends TestCase {

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    $container = new ContainerBuilder();
    $container->set('cache_contexts_manager', new CacheContextsManager($container, ['user.permissions']));
    \Drupal::setContainer($container);
  }

  /**
   * {@inheritdoc}
   */
  protected function tearDown(): void {
    \Drupal::unsetContainer();
    parent::tearDown();
  }

  /**
   * Tests that grants require an authenticated viewer with permission.
   */
  #[DataProvider('grantCases')]
  public function testNodeGrants(string $operation, bool $authenticated, bool $permitted, bool $expected): void {
    $account = $this->createMock(AccountInterface::class);
    $account->method('isAuthenticated')->willReturn($authenticated);
    $account->method('hasPermission')->with('view any unpublished in_memoriam content')->willReturn($permitted);

    $grants = (new InMemoriamAccessHooks())->nodeGrants($account, $operation);
    $this->assertSame($expected ? ['montreal2027_in_memoriam_unpublished' => [1]] : [], $grants);
  }

  /**
   * Provides supported and unsupported grant requests.
   */
  public static function grantCases(): array {
    return [
      'maintainer view' => ['view', TRUE, TRUE, TRUE],
      'missing permission' => ['view', TRUE, FALSE, FALSE],
      'anonymous' => ['view', FALSE, TRUE, FALSE],
      'update' => ['update', TRUE, TRUE, FALSE],
      'delete' => ['delete', TRUE, TRUE, FALSE],
    ];
  }

  /**
   * Tests that only unpublished In Memoriam entries receive view grants.
   */
  #[DataProvider('recordCases')]
  public function testNodeAccessRecords(string $bundle, bool $published, bool $expected): void {
    $node = $this->createMock(NodeInterface::class);
    $node->method('bundle')->willReturn($bundle);
    $node->method('isPublished')->willReturn($published);
    $node->expects($this->never())->method('getOwnerId');

    $records = (new InMemoriamAccessHooks())->nodeAccessRecords($node);
    $this->assertSame($expected ? [
      [
        'realm' => 'montreal2027_in_memoriam_unpublished',
        'gid' => 1,
        'grant_view' => 1,
        'grant_update' => 0,
        'grant_delete' => 0,
      ],
    ] : [], $records);
  }

  /**
   * Provides node bundles and publication states.
   */
  public static function recordCases(): array {
    return [
      'unpublished entry' => ['in_memoriam', FALSE, TRUE],
      'published entry' => ['in_memoriam', TRUE, FALSE],
      'other unpublished content' => ['page', FALSE, FALSE],
      'other published content' => ['page', TRUE, FALSE],
    ];
  }

  /**
   * Tests publication access is limited to the node status field and bundle.
   */
  #[DataProvider('fieldCases')]
  public function testEntityFieldAccess(string $operation, string $entity_type, string $field_name, string $bundle, bool $permitted, bool $expected): void {
    $definition = $this->createMock(FieldDefinitionInterface::class);
    $definition->method('getTargetEntityTypeId')->willReturn($entity_type);
    $definition->method('getName')->willReturn($field_name);
    $node = $this->createMock(NodeInterface::class);
    $node->method('bundle')->willReturn($bundle);
    $node->method('getCacheContexts')->willReturn([]);
    $node->method('getCacheTags')->willReturn(['node:42']);
    $node->method('getCacheMaxAge')->willReturn(-1);
    $items = $this->createMock(FieldItemListInterface::class);
    $items->method('getEntity')->willReturn($node);
    $account = $this->createMock(AccountInterface::class);
    $account->method('hasPermission')->with('publish in_memoriam content')->willReturn($permitted);

    $result = (new InMemoriamAccessHooks())->entityFieldAccess($operation, $definition, $account, $items);
    $this->assertSame($expected, $result->isAllowed());
    $this->assertFalse($result->isForbidden());
    if ($operation === 'edit' && $entity_type === 'node' && $field_name === 'status' && $bundle === 'in_memoriam') {
      $this->assertContains('user.permissions', $result->getCacheContexts());
      $this->assertContains('node:42', $result->getCacheTags());
    }
  }

  /**
   * Provides publication permission and field boundary cases.
   */
  public static function fieldCases(): array {
    return [
      'publish entry' => ['edit', 'node', 'status', 'in_memoriam', TRUE, TRUE],
      'missing permission' => ['edit', 'node', 'status', 'in_memoriam', FALSE, FALSE],
      'other bundle' => ['edit', 'node', 'status', 'page', TRUE, FALSE],
      'other entity type' => ['edit', 'media', 'status', 'in_memoriam', TRUE, FALSE],
      'ownership field' => ['edit', 'node', 'uid', 'in_memoriam', TRUE, FALSE],
      'view field' => ['view', 'node', 'status', 'in_memoriam', TRUE, FALSE],
    ];
  }

  /**
   * Tests definition-only field checks cannot grant cross-bundle access.
   */
  public function testFieldAccessWithoutItems(): void {
    $definition = $this->createMock(FieldDefinitionInterface::class);
    $definition->method('getTargetEntityTypeId')->willReturn('node');
    $definition->method('getName')->willReturn('status');
    $account = $this->createMock(AccountInterface::class);
    $account->expects($this->never())->method('hasPermission');

    $result = (new InMemoriamAccessHooks())->entityFieldAccess('edit', $definition, $account);
    $this->assertTrue($result->isNeutral());
  }

}
