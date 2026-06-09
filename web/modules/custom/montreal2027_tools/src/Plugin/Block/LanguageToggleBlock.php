<?php

declare(strict_types=1);

namespace Drupal\montreal2027_tools\Plugin\Block;

use Drupal\Core\Block\Attribute\Block;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Language\LanguageInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Routing\RouteMatchInterface;
use Drupal\Core\StringTranslation\TranslatableMarkup;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides a client-side EN/FR language toggle block.
 */
#[Block(
  id: 'montreal2027_language_toggle',
  admin_label: new TranslatableMarkup('Language Toggle (EN/FR)'),
  category: new TranslatableMarkup('Montreal 2027'),
)]
class LanguageToggleBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * Display names shown in the language's own script.
   */
  private const LANG_DISPLAY = [
    'en' => ['flag' => '🇬🇧', 'name' => 'English'],
    'fr' => ['flag' => '🇫🇷', 'name' => 'Français'],
  ];

  public function __construct(
    array $configuration,
    string $plugin_id,
    mixed $plugin_definition,
    private readonly LanguageManagerInterface $languageManager,
    private readonly RouteMatchInterface $routeMatch,
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition): static {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('language_manager'),
      $container->get('current_route_match'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function build(): array {
    $current_lang = $this->languageManager
      ->getCurrentLanguage(LanguageInterface::TYPE_INTERFACE)
      ->getId();

    ['elements' => $link_elements, 'settings' => $settings_links] = $this->buildLinkData($current_lang);

    return [
      '#type' => 'container',
      '#attributes' => [
        'class' => ['language-toggle', 'js-language-toggle'],
        'role' => 'navigation',
        'aria-label' => $this->t('Language toggle'),
      ],
      'links' => $link_elements,
      '#attached' => [
        'library' => ['montreal2027_tools/language_toggle'],
        'drupalSettings' => [
          'languageToggle' => [
            'links' => $settings_links,
            'currentLang' => $current_lang,
          ],
        ],
      ],
      '#cache' => [
        'contexts' => [
          'languages:' . LanguageInterface::TYPE_INTERFACE,
          'url.path',
        ],
      ],
    ];
  }

  /**
   * Builds render elements and drupalSettings data for all available languages.
   *
   * @param string $current_lang
   *   The active language code.
   *
   * @return array{elements: array, settings: array}
   */
  private function buildLinkData(string $current_lang): array {
    $elements = [];
    $settings = [];

    $current_url = Url::fromRouteMatch($this->routeMatch);
    $switch_links_obj = $this->languageManager->getLanguageSwitchLinks(
      LanguageInterface::TYPE_INTERFACE,
      $current_url
    );

    if ($switch_links_obj && !empty($switch_links_obj->links)) {
      foreach ($switch_links_obj->links as $langcode => $link) {
        $langcode = (string) $langcode;
        $is_active = ($langcode === $current_lang);
        
        // Hide the active language (only show the toggle to the other language).
        if ($is_active) {
          continue;
        }

        $display = self::LANG_DISPLAY[$langcode] ?? ['flag' => '', 'name' => $link['title'] ?? $langcode];
        $label = trim($display['name'] . ' ' . $display['flag']);
        
        $url_obj = clone $link['url'];
        $options = $url_obj->getOptions();
        if (isset($link['language'])) {
          $options['language'] = $link['language'];
        }
        if (isset($link['query'])) {
          $options['query'] = $link['query'];
        }
        $url_obj->setOptions($options);

        $elements[$langcode] = $this->buildLinkElement($label, $url_obj, $langcode, $is_active);
        $settings[$langcode] = ['url' => $url_obj->toString()];
      }
    }
    else {
      // Fallback: language module not configured — use URL language option.
      foreach ($this->languageManager->getLanguages() as $langcode => $language) {
        $langcode = (string) $langcode;
        $is_active = ($langcode === $current_lang);
        
        // Hide the active language (only show the toggle to the other language).
        if ($is_active) {
          continue;
        }

        $display = self::LANG_DISPLAY[$langcode] ?? ['flag' => '', 'name' => $language->getName()];
        $label = trim($display['name'] . ' ' . $display['flag']);
        
        $url_obj = Url::fromRoute('<current>', [], ['language' => $language, 'absolute' => TRUE]);

        $elements[$langcode] = $this->buildLinkElement($label, $url_obj, $langcode, $is_active);
        $settings[$langcode] = ['url' => $url_obj->toString()];
      }
    }

    return ['elements' => $elements, 'settings' => $settings];
  }

  /**
   * Returns a single #type => link render element for a language.
   */
  private function buildLinkElement(string $label, Url $url, string $langcode, bool $is_active): array {
    $classes = ['language-toggle__link'];
    if ($is_active) {
      $classes[] = 'is-active';
    }

    return [
      '#type' => 'link',
      '#title' => $label,
      '#url' => $url,
      '#attributes' => [
        'class' => $classes,
        'lang' => $langcode,
        'hreflang' => $langcode,
        'data-langcode' => $langcode,
        'aria-current' => $is_active ? 'true' : 'false',
      ],
    ];
  }

}