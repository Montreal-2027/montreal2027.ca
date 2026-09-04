# Graph Report - montreal2027.ca  (2026-09-04)

## Corpus Check
- 23 files · ~20,724 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 272 nodes · 311 edges · 22 communities (12 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `99cf93df`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- post-create-project-cmd-message
- installer-paths
- StaffContactController.php
- composer.json
- package.json
- allow-plugins
- LanguageToggleBlock.php
- Drupal\Core\Form\FormStateInterface
- montreal2027.theme
- StaffPageController.php
- montreal2027_tools.module
- entrypoint.sh
- post-merge
- pre-commit
- install.sh
- setup-graphify.sh
- sync.sh
- Symfony\Component\Dotenv\Dotenv

## God Nodes (most connected - your core abstractions)
1. `require` - 65 edges
2. `allow-plugins` - 12 edges
3. `post-create-project-cmd-message` - 12 edges
4. `installer-paths` - 11 edges
5. `LanguageToggleBlock` - 10 edges
6. `StaffContactController` - 9 edges
7. `StaffPageController` - 7 edges
8. `extra` - 6 edges
9. `ContactImportCommands` - 6 edges
10. `StaffPageSettingsForm` - 6 edges

## Surprising Connections (you probably didn't know these)
- `StaffContactController` --references--> `Drupal\Core\Language\LanguageManagerInterface`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffContactController.php →   _Bridges community 3 → community 7_
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 10 → community 3_

## Import Cycles
- None detected.

## Communities (22 total, 7 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (65): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+57 more)

### Community 1 - "post-create-project-cmd-message"
Cohesion: 0.07
Nodes (30): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+22 more)

### Community 2 - "installer-paths"
Cohesion: 0.09
Nodes (23): installer-paths, drush/Commands/contrib/{$name}, recipes/{$name}, web/core, web/libraries/{$name}, web/modules/contrib/{$name}, web/modules/custom/{$name}, web/profiles/contrib/{$name} (+15 more)

### Community 3 - "StaffContactController.php"
Cohesion: 0.15
Nodes (13): Drupal\Core\Controller\ControllerBase, Drupal\Core\Entity\EntityTypeManagerInterface, Drupal\Core\Logger\LoggerChannelFactoryInterface, Drupal\Core\Mail\MailManagerInterface, Drupal\user\UserInterface, Drush\Commands\DrushCommands, JsonResponse, Symfony\Component\DependencyInjection\ContainerInterface (+5 more)

### Community 4 - "composer.json"
Cohesion: 0.12
Nodes (17): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+9 more)

### Community 5 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "allow-plugins"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 7 - "LanguageToggleBlock.php"
Cohesion: 0.20
Nodes (10): Drupal\Core\Block\Attribute\Block, Drupal\Core\Block\BlockBase, Drupal\Core\Language\LanguageManagerInterface, Drupal\Core\Plugin\ContainerFactoryPluginInterface, Drupal\Core\Routing\RouteMatchInterface, Drupal\Core\StringTranslation\TranslatableMarkup, Drupal\Core\Url, Url (+2 more)

### Community 8 - "Drupal\Core\Form\FormStateInterface"
Cohesion: 0.22
Nodes (4): Drupal\Core\Form\ConfigFormBase, Drupal\Core\Form\FormStateInterface, StaffPageSettingsForm, StaffPageSettingsForm

### Community 9 - "montreal2027.theme"
Cohesion: 0.21
Nodes (10): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+2 more)

### Community 10 - "StaffPageController.php"
Cohesion: 0.29
Nodes (6): Drupal\Component\Utility\Html, Drupal\Core\Language\LanguageInterface, Drupal\Core\Render\Markup, Drupal\taxonomy\TermInterface, Drupal\taxonomy\TermStorageInterface, StaffPageController

### Community 11 - "montreal2027_tools.module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

## Knowledge Gaps
- **133 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+128 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 165 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.207) - this node is a cross-community bridge._
- **Why does `extra` connect `post-create-project-cmd-message` to `installer-paths`, `composer.json`?**
  _High betweenness centrality (0.166) - this node is a cross-community bridge._
- **Why does `installer-paths` connect `installer-paths` to `post-create-project-cmd-message`?**
  _High betweenness centrality (0.083) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _133 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03076923076923077 - nodes in this community are weakly interconnected._
- **Should `post-create-project-cmd-message` be split into smaller, more focused modules?**
  _Cohesion score 0.06666666666666667 - nodes in this community are weakly interconnected._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.08695652173913043 - nodes in this community are weakly interconnected._