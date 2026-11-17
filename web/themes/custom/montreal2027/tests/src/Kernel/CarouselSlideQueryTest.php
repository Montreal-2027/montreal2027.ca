<?php

declare(strict_types=1);

namespace Drupal\Tests\montreal2027\Kernel;

use Drupal\page_cache\StackMiddleware\PageCache;
use Drupal\Component\Datetime\TimeInterface;
use Drupal\Core\Cache\CacheableResponse;
use Drupal\Core\Cache\CacheableMetadata;
use Drupal\Core\Cache\Context\CalculatedCacheContextInterface;
use Drupal\Core\Cache\MemoryBackend;
use Drupal\Core\PageCache\RequestPolicyInterface;
use Drupal\Core\PageCache\ResponsePolicyInterface;
use Drupal\Core\Routing\RouteObjectInterface;
use Drupal\Core\Session\UserSession;
use Drupal\file\Entity\File;
use Drupal\field\Entity\FieldConfig;
use Drupal\field\Entity\FieldStorageConfig;
use Drupal\KernelTests\Core\Entity\EntityKernelTestBase;
use Drupal\media\Entity\Media;
use Drupal\node\Entity\Node;
use Drupal\node\Entity\NodeType;
use Drupal\node\NodeInterface;
use Drupal\taxonomy\Entity\Term;
use Drupal\taxonomy\Entity\Vocabulary;
use Drupal\taxonomy\TermInterface;
use Drupal\Tests\media\Traits\MediaTypeCreationTrait;
use Drupal\Tests\workspaces\Kernel\WorkspaceTestTrait;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\RunTestsInSeparateProcesses;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Route;

/**
 * Exercises carousel entity queries against an isolated SQLite kernel database.
 */
#[Group('montreal2027')]
#[RunTestsInSeparateProcesses]
class CarouselSlideQueryTest extends EntityKernelTestBase {

  use MediaTypeCreationTrait;
  use WorkspaceTestTrait;

  /**
   * {@inheritdoc}
   */
  protected static $modules = [
    'system',
    'user',
    'field',
    'text',
    'filter',
    'file',
    'image',
    'media',
    'entity_test',
    'node',
    'taxonomy',
    'datetime',
  ];

  /**
   * The homepage carousel term.
   */
  protected TermInterface $homepageTerm;

  /**
   * The other carousel term.
   */
  protected TermInterface $otherTerm;

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    require_once dirname(__DIR__, 3) . '/montreal2027.theme';

    $this->installConfig(['system', 'node', 'taxonomy', 'datetime', 'filter', 'file', 'image', 'media']);
    $this->config('system.date')->set('timezone.default', 'UTC')->save();
    $this->installSchema('node', ['node_access']);
    $this->installEntitySchema('file');
    $this->installEntitySchema('media');
    $this->installSchema('file', ['file_usage']);
    $this->createMediaType('image', ['id' => 'image']);

    NodeType::create(['type' => 'carousel_slide', 'name' => 'Carousel slide'])->save();
    NodeType::create(['type' => 'home_page', 'name' => 'Home page'])->save();
    NodeType::create(['type' => 'carousel_page', 'name' => 'Legacy carousel page'])->save();
    NodeType::create(['type' => 'article', 'name' => 'Article'])->save();
    $vocabulary = Vocabulary::create([
      'vid' => 'carousel_type',
      'name' => 'Carousel type',
    ]);
    $vocabulary->save();
    $this->homepageTerm = Term::create([
      'vid' => 'carousel_type',
      'name' => 'Homepage',
    ]);
    $this->homepageTerm->save();
    $this->otherTerm = Term::create([
      'vid' => 'carousel_type',
      'name' => 'Other carousel',
    ]);
    $this->otherTerm->save();

    $bundles = ['carousel_slide', 'home_page', 'carousel_page', 'article'];
    $this->createField('field_carousel_type', 'entity_reference', ['target_type' => 'taxonomy_term'], $bundles);
    $this->createField('field_start_date', 'datetime', ['datetime_type' => 'date'], ['carousel_slide', 'carousel_page']);
    $this->createField('field_end_date', 'datetime', ['datetime_type' => 'date'], ['carousel_slide', 'carousel_page']);
    $this->createField('field_slide_title', 'string', [], ['carousel_slide', 'carousel_page']);
    $this->createField('body', 'text_long', [], ['carousel_slide', 'carousel_page']);
    $this->createField('field_hero_image', 'entity_reference', ['target_type' => 'media'], ['carousel_slide']);

    // Use anonymous permissions without touching site content or config.
    $account = $this->createUser(['access content', 'view media']);
    $this->setCurrentUser($account);
  }

  /**
   * Verifies formatted body rendering and media image metadata.
   */
  public function testFormattedBodyAndAccessibleMediaImage(): void {
    $file = File::create([
      'uri' => 'public://carousel-test.png',
      'status' => 1,
      'uid' => $this->container->get('current_user')->id(),
    ]);
    $file->save();
    $media = Media::create([
      'bundle' => 'image',
      'name' => 'Carousel test image',
      'status' => 1,
      'field_media_image' => [
        'target_id' => $file->id(),
        'alt' => 'Carousel image alternative',
      ],
    ]);
    $media->save();
    $slide = $this->createCarouselSlide('Formatted slide', $this->otherTerm);
    $slide->set('field_hero_image', ['target_id' => $media->id()]);
    $slide->set('body', [
      'value' => 'Formatted <script>unsafe</script> text.',
      'format' => 'plain_text',
    ]);
    $slide->save();

    $variables = [];
    $slides = montreal2027_carousel_slides($this->otherTerm, $variables);
    $result = array_values(array_filter($slides, static fn(array $item): bool => $item['title'] === 'Formatted slide'))[0];
    $this->assertSame((string) $file->id(), (string) $result['bground_image_id']);
    $this->assertSame('Carousel image alternative', $result['bground_image_alt']);
    $this->assertIsArray($result['body']);
    $rendered_body = (string) $this->container->get('renderer')->renderInIsolation($result['body']);
    $this->assertStringContainsString('&lt;script&gt;', $rendered_body);
  }

  /**
   * Verifies published carousel slide date and taxonomy filtering.
   */
  public function testQueryVisibilityAndEmptyDates(): void {
    $timezone = montreal2027_carousel_site_timezone();
    $today = montreal2027_carousel_today(\Drupal::time()->getCurrentTime(), $timezone);
    $tomorrow = (new \DateTimeImmutable($today, $timezone))->modify('+1 day')->format('Y-m-d');
    $yesterday = (new \DateTimeImmutable($today, $timezone))->modify('-1 day')->format('Y-m-d');

    $this->createCarouselSlide('No dates', $this->otherTerm);
    $this->createCarouselSlide('Active window', $this->otherTerm, $today, $tomorrow);
    $this->createCarouselSlide('Start empty', $this->otherTerm, NULL, $tomorrow);
    $this->createCarouselSlide('Future start', $this->otherTerm, $tomorrow);
    $this->createCarouselSlide('End today', $this->otherTerm, NULL, $today);
    $this->createCarouselSlide('Expired', $this->otherTerm, NULL, $yesterday);
    $this->createCarouselSlide('Wrong term', $this->homepageTerm);
    $this->createCarouselSlide('Unpublished', $this->otherTerm, NULL, NULL, FALSE);
    $this->createLegacyCarouselPage('Legacy type must not match', $this->otherTerm);

    $variables = [];
    $slides = montreal2027_carousel_slides($this->otherTerm, $variables);
    $titles = array_column($slides, 'title');
    sort($titles);

    $this->assertSame(['Active window', 'No dates', 'Start empty'], $titles);
    $empty_dates_slide = array_values(array_filter($slides, static fn(array $slide): bool => $slide['title'] === 'No dates'))[0];
    $this->assertNull($empty_dates_slide['bground_image_id']);
    $this->assertNull($empty_dates_slide['body']);
    $this->assertContains('node_list:carousel_slide', $variables['#cache']['tags']);

    $permitted_cache_key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $this->container->get('current_user')->setAccount(new UserSession());
    $restricted_cache_key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $this->assertNotSame($permitted_cache_key, $restricted_cache_key);
    $restricted_variables = [];
    $restricted_slides = montreal2027_carousel_slides($this->otherTerm, $restricted_variables);
    $this->assertSame([], $restricted_slides, 'Slides are omitted when current user lacks content access.');
  }

  /**
   * Verifies empty results cache for one day and slide changes invalidate them.
   */
  public function testCachedEmptyIdsExpireAndInvalidateOnSlideChanges(): void {
    $variables = [];
    $this->assertSame([], montreal2027_carousel_slides($this->otherTerm, $variables));

    $cache = $this->container->get('cache.default');
    $cache_key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $cached_ids = $cache->get($cache_key);
    $this->assertNotFalse($cached_ids);
    $this->assertSame([], $cached_ids->data);
    $now = \Drupal::time()->getCurrentTime();
    $this->assertGreaterThanOrEqual($now, $cached_ids->expire);
    $this->assertLessThanOrEqual(
      \Drupal::time()->getCurrentTime() + 86400,
      $cached_ids->expire,
    );
    $this->assertLessThanOrEqual(
      86400,
      $variables['#cache']['max-age'],
    );

    // A repeat uses cached empty IDs, not newly saved data.
    $cached_variables = [];
    $this->assertSame([], montreal2027_carousel_slides($this->otherTerm, $cached_variables));
    $this->createCarouselSlide('Invalidates cached empty list', $this->otherTerm);
    $this->assertFalse($cache->get($cache_key), 'Saving a slide invalidates the cached ID list.');

    $updated_variables = [];
    $slides = montreal2027_carousel_slides($this->otherTerm, $updated_variables);
    $this->assertSame(['Invalidates cached empty list'], array_column($slides, 'title'));
  }

  /**
   * Verifies workspace-specific queries cannot reuse another workspace's IDs.
   */
  public function testWorkspaceIdCacheIsolation(): void {
    $live_slide = $this->createCarouselSlide('Live slide', $this->otherTerm);
    $this->initializeWorkspacesModule();
    $this->setCurrentUser($this->createUser(['access content', 'view media']));

    $live_variables = [];
    $this->assertSame(['Live slide'], array_column(montreal2027_carousel_slides($this->otherTerm, $live_variables), 'title'));
    $live_key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $this->switchToWorkspace('stage');
    $live_slide = Node::load($live_slide->id());
    $live_slide->setNewRevision(TRUE);
    $live_slide->setUnpublished();
    $live_slide->save();
    $stage_variables = [];
    $this->assertSame([], montreal2027_carousel_slides($this->otherTerm, $stage_variables));
    $stage_key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $this->assertNotSame($live_key, $stage_key);
    $this->assertContains('workspace', $stage_variables['#cache']['contexts']);

    $this->switchToLive();
    $live_variables = [];
    $this->assertSame(['Live slide'], array_column(montreal2027_carousel_slides($this->otherTerm, $live_variables), 'title'));
    $this->assertNotFalse($this->container->get('cache.default')->get($live_key));
    $this->assertNotFalse($this->container->get('cache.default')->get($stage_key));
  }

  /**
   * Verifies real node grants disable both ID and anonymous page caching.
   */
  public function testNodeGrantsDisableIdAndPageCaching(): void {
    $this->createCarouselSlide('Visible slide', $this->otherTerm);
    $this->enableModules(['node_access_test_empty']);
    $this->container = \Drupal::getContainer();
    $metadata = $this->container->get('cache_context.user.node_grants')->getCacheableMetadata('view');
    $this->assertSame(0, $metadata->getCacheMaxAge());
    $key = montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id());
    $backend = $this->container->get('cache.default');
    $backend->set($key, [], \Drupal::time()->getCurrentTime() + 86400);

    $variables = [];
    $slides = montreal2027_carousel_slides($this->otherTerm, $variables);
    $this->assertSame(['Visible slide'], array_column($slides, 'title'));
    $this->assertSame(0, $variables['#cache']['max-age']);
    $this->assertContains('user:' . $this->container->get('current_user')->id(), $variables['#cache']['tags']);
    $this->assertSame([], $backend->get($key)->data, 'Existing ID cache entry is neither used nor replaced.');
    $policy = $this->container->get('page_cache_kill_switch');
    $this->assertSame(ResponsePolicyInterface::DENY, $policy->check(new Response(), Request::create('/')));
  }

  /**
   * Verifies finite grant metadata caps ID and page expiry and adds tags.
   */
  public function testFiniteGrantsContextMetadata(): void {
    $context = $this->createMock(CalculatedCacheContextInterface::class);
    $context->method('getContext')->willReturn('test-grant');
    $context->method('getCacheableMetadata')->willReturn((new CacheableMetadata())
      ->setCacheMaxAge(60)
      ->setCacheTags(['user:123']));
    $this->container->set('cache_context.user.node_grants', $context);

    $variables = [];
    montreal2027_carousel_slides($this->otherTerm, $variables);
    $item = $this->container->get('cache.default')->get(montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id()));
    $this->assertNotFalse($item);
    $this->assertLessThanOrEqual(\Drupal::time()->getCurrentTime() + 60, $item->expire);
    $this->assertContains('user:123', $item->tags);
    $this->assertContains('user:123', $variables['#cache']['tags']);
    $this->assertLessThanOrEqual(60, $variables['#cache']['max-age']);
    $expires = montreal2027_carousel_earliest_expiration(PHP_INT_MAX, $variables['#attached']['http_header']);
    $this->assertSame($item->expire, $expires);
  }

  /**
   * Verifies only carousel slide and homepage routes select a carousel.
   */
  public function testRoutedNodeSelectsItsCarouselTerm(): void {
    $this->createCarouselSlide('Selected term slide', $this->otherTerm);
    $this->createCarouselSlide('Homepage-only slide', $this->homepageTerm);
    $article = Node::create([
      'type' => 'article',
      'title' => 'Routed article',
      'status' => 1,
      'field_carousel_type' => ['target_id' => $this->otherTerm->id()],
    ]);
    $article->save();

    $variables = $this->preprocessForRoute($article);

    $this->assertArrayNotHasKey('carousel_slides', $variables);

    $slide_route = $this->preprocessForRoute($this->createCarouselSlide('Routed slide', $this->otherTerm));
    $routed_titles = array_column($slide_route['carousel_slides'], 'title');
    sort($routed_titles);
    $this->assertSame(['Routed slide', 'Selected term slide'], $routed_titles);

    $legacy_route = $this->preprocessForRoute($this->createLegacyCarouselPage('Legacy route', $this->otherTerm));
    $this->assertArrayNotHasKey('carousel_slides', $legacy_route);

    $home_page = Node::create([
      'type' => 'home_page',
      'title' => 'Homepage',
      'status' => 1,
      'field_carousel_type' => ['target_id' => $this->otherTerm->id()],
    ]);
    $home_page->save();
    $home_variables = $this->preprocessForRoute($home_page);
    $this->assertSame(['Homepage-only slide'], array_column($home_variables['carousel_slides'], 'title'));
  }

  /**
   * Verifies homepage fallback, site timezone, and response expiry precedence.
   */
  public function testHomepageTimezoneAndEarlierExpiry(): void {
    $this->config('system.date')->set('timezone.default', 'Pacific/Kiritimati')->save();
    date_default_timezone_set('America/Los_Angeles');
    $timestamp = (new \DateTimeImmutable('2026-11-16 12:30:00', new \DateTimeZone('UTC')))->getTimestamp();
    $timezone = montreal2027_carousel_site_timezone();
    $this->assertSame('2026-11-17', montreal2027_carousel_today($timestamp, $timezone));

    $this->createCarouselSlide('Homepage slide', $this->homepageTerm);
    $home_page = Node::create(['type' => 'home_page', 'title' => 'Homepage', 'status' => 1]);
    $home_page->save();
    $variables = $this->preprocessForRoute($home_page);

    $this->assertSame(['Homepage slide'], array_column($variables['carousel_slides'], 'title'));
    $this->assertContains('config:system.date', $variables['#cache']['tags']);

    $existing_expiration = \Drupal::time()->getCurrentTime() + 60;
    $effective_expiration = montreal2027_carousel_earliest_expiration(
      \Drupal::time()->getCurrentTime() + 86400,
      [['Expires', gmdate('D, d M Y H:i:s \G\M\T', $existing_expiration), TRUE]],
    );
    $this->assertSame($existing_expiration, $effective_expiration);
  }

  /**
   * Verifies missing site timezone configuration is surfaced.
   */
  public function testMissingSiteTimezoneConfigurationThrows(): void {
    $this->config('system.date')->set('timezone.default', NULL)->save();

    $this->expectException(\UnexpectedValueException::class);
    $this->expectExceptionMessage('system.date:timezone.default');
    montreal2027_carousel_site_timezone();
  }

  /**
   * Verifies carousel keeps its existing Bootstrap autoplay behavior.
   */
  public function testCarouselTemplateRetainsAutoplay(): void {
    $template = file_get_contents(dirname(__DIR__, 3) . '/templates/content/page.html.twig');

    $this->assertIsString($template);
    $this->assertStringContainsString('data-bs-ride="carousel"', $template);
  }

  /**
   * Verifies PageCache cannot outlive the cached slide IDs.
   */
  public function testAnonymousPageCacheExpiresWithCachedSlideIds(): void {
    require_once DRUPAL_ROOT . '/core/modules/page_cache/src/StackMiddleware/PageCache.php';
    $timezone = montreal2027_carousel_site_timezone();
    $today = montreal2027_carousel_today(\Drupal::time()->getCurrentTime(), $timezone);
    $tomorrow = (new \DateTimeImmutable($today, $timezone))->modify('+1 day')->format('Y-m-d');
    $node = $this->createCarouselSlide('Expires tomorrow', $this->otherTerm, NULL, $tomorrow);
    $variables = $this->preprocessForRoute($node);

    $expires = NULL;
    foreach ($variables['#attached']['http_header'] as $header) {
      if (strcasecmp((string) ($header[0] ?? ''), 'Expires') === 0) {
        $expires = $header[1];
      }
    }
    $this->assertIsString($expires);
    $id_cache = $this->container->get('cache.default')->get(montreal2027_carousel_slide_ids_cache_key($this->otherTerm->id()));
    $this->assertNotFalse($id_cache);
    $expiration = (new \DateTimeImmutable($expires))->getTimestamp();
    $this->assertSame($id_cache->expire, $expiration);
    $this->assertLessThanOrEqual(\Drupal::time()->getCurrentTime() + 86400, $expiration);

    $clock = new class($expiration - 120) implements TimeInterface {

      public function __construct(public int $now) {}

      /**
       * {@inheritdoc}
       */
      public function getRequestTime(): int {
        return $this->now;
      }

      /**
       * {@inheritdoc}
       */
      public function getRequestMicroTime(): float {
        return (float) $this->now;
      }

      /**
       * {@inheritdoc}
       */
      public function getCurrentTime(): int {
        return $this->now;
      }

      /**
       * {@inheritdoc}
       */
      public function getCurrentMicroTime(): float {
        return (float) $this->now;
      }

    };
    $backend = new MemoryBackend($clock);
    $kernel = new class($expires) implements HttpKernelInterface {
      /**
       * Number of kernel requests handled.
       *
       * @var int
       */
      public int $calls = 0;

      public function __construct(private string $expires) {}

      /**
       * {@inheritdoc}
       */
      public function handle(Request $request, $type = self::MAIN_REQUEST, $catch = TRUE): Response {
        $this->calls++;
        $response = new CacheableResponse('rendered-' . $this->calls);
        $response->headers->set('Expires', $this->expires);
        return $response;
      }

    };
    $request_policy = new class implements RequestPolicyInterface {

      /**
       * {@inheritdoc}
       */
      public function check(Request $request): string {
        return self::ALLOW;
      }

    };
    $response_policy = new class implements ResponsePolicyInterface {

      /**
       * {@inheritdoc}
       */
      public function check(Response $response, Request $request): ?string {
        return NULL;
      }

    };
    $request_page = static function (int $at) use ($backend, $kernel, $request_policy, $response_policy, $clock): Response {
      $clock->now = $at;
      $request = Request::create('https://example.test/carousel', 'GET');
      $request->server->set('REQUEST_TIME', $at);
      $page_cache = new PageCache(
        static fn() => $kernel,
        $backend,
        $request_policy,
        $response_policy,
      );
      return $page_cache->handle($request);
    };

    $this->assertSame('MISS', $request_page($expiration - 120)->headers->get('X-Drupal-Cache'));
    $this->assertSame('HIT', $request_page($expiration - 1)->headers->get('X-Drupal-Cache'));
    $this->assertSame('MISS', $request_page($expiration + 1)->headers->get('X-Drupal-Cache'));
    $this->assertSame(2, $kernel->calls);
  }

  /**
   * Verifies an earlier response expiry is preserved on rendered pages.
   */
  public function testExistingExpiresHeaderIsNeverExtended(): void {
    $timezone = montreal2027_carousel_site_timezone();
    $today = montreal2027_carousel_today(\Drupal::time()->getCurrentTime(), $timezone);
    $tomorrow = (new \DateTimeImmutable($today, $timezone))->modify('+1 day')->format('Y-m-d');
    $node = $this->createCarouselSlide('Earlier expiry', $this->otherTerm, NULL, $tomorrow);
    $earlier_expiration = \Drupal::time()->getCurrentTime() + 60;
    $variables = $this->preprocessForRoute($node, [
      'is_front' => FALSE,
      '#attached' => [
        'http_header' => [
          ['Expires', gmdate('D, d M Y H:i:s \G\M\T', $earlier_expiration)],
        ],
      ],
    ]);

    $expires_values = [];
    foreach ($variables['#attached']['http_header'] as $header) {
      if (strcasecmp((string) ($header[0] ?? ''), 'Expires') === 0) {
        $expires_values[] = (new \DateTimeImmutable($header[1]))->getTimestamp();
      }
    }
    $this->assertNotEmpty($expires_values);
    $this->assertLessThanOrEqual($earlier_expiration, max($expires_values));
  }

  /**
   * Creates and attaches a field to the specified node bundles.
   *
   * @param string $name
   *   Field machine name.
   * @param string $type
   *   Field type.
   * @param array<string, mixed> $settings
   *   Field storage settings.
   * @param list<string> $bundles
   *   Node bundle IDs.
   */
  private function createField(string $name, string $type, array $settings = [], array $bundles = ['carousel_slide']): void {
    FieldStorageConfig::create([
      'field_name' => $name,
      'entity_type' => 'node',
      'type' => $type,
      'settings' => $settings,
    ])->save();
    foreach ($bundles as $bundle) {
      FieldConfig::create([
        'field_name' => $name,
        'entity_type' => 'node',
        'bundle' => $bundle,
        'label' => $name,
      ])->save();
    }
  }

  /**
   * Creates a carousel slide fixture.
   */
  private function createCarouselSlide(string $title, TermInterface $term, ?string $start = NULL, ?string $end = NULL, bool $published = TRUE): NodeInterface {
    $values = [
      'type' => 'carousel_slide',
      'title' => $title,
      'status' => $published,
      'field_carousel_type' => ['target_id' => $term->id()],
      'field_slide_title' => $title,
    ];
    if ($start !== NULL) {
      $values['field_start_date'] = ['value' => $start];
    }
    if ($end !== NULL) {
      $values['field_end_date'] = ['value' => $end];
    }
    $node = Node::create($values);
    $node->save();
    return $node;
  }

  /**
   * Creates a legacy bundle fixture to ensure it is not queried.
   */
  private function createLegacyCarouselPage(string $title, TermInterface $term): NodeInterface {
    $node = Node::create([
      'type' => 'carousel_page',
      'title' => $title,
      'status' => 1,
      'field_carousel_type' => ['target_id' => $term->id()],
    ]);
    $node->save();
    return $node;
  }

  /**
   * Runs page preprocessing for an arbitrary canonical node route.
   *
   * @param \Drupal\node\NodeInterface $node
   *   Routed node.
   * @param array<string, mixed> $variables
   *   Initial page variables.
   *
   * @return array<string, mixed>
   *   Preprocessed page variables.
   */
  private function preprocessForRoute(NodeInterface $node, array $variables = []): array {
    $request = Request::create('/node/' . $node->id());
    $request->attributes->set(RouteObjectInterface::ROUTE_OBJECT, new Route('/node/{node}'));
    $request->attributes->set(RouteObjectInterface::ROUTE_NAME, 'entity.node.canonical');
    $request->attributes->set('node', $node);
    $request_stack = $this->container->get('request_stack');
    $request_stack->push($request);
    try {
      $variables['is_front'] = FALSE;
      montreal2027_preprocess_page($variables);
      return $variables;
    }
    finally {
      $request_stack->pop();
    }
  }

}
