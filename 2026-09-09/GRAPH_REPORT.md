# Graph Report - montreal2027.ca  (2026-09-09)

## Corpus Check
- 22 files · ~20,277 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 238 nodes · 272 edges · 22 communities (12 shown, 7 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.85)
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9bd5a07c`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- installer-paths
- composer.json
- package.json
- allow-plugins
- LanguageToggleBlock.php
- StaffContactController.php
- montreal2027.theme
- StaffPageController.php
- MontrealConfigurationForm
- ContactImportCommands
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
3. `installer-paths` - 11 edges
4. `LanguageToggleBlock` - 10 edges
5. `StaffContactController` - 9 edges
6. `StaffPageController` - 7 edges
7. `extra` - 6 edges
8. `ContactImportCommands` - 6 edges
9. `MontrealConfigurationForm` - 6 edges
10. `config` - 4 edges

## Surprising Connections (you probably didn't know these)
- `StaffContactController` --references--> `Drupal\Core\Language\LanguageManagerInterface`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffContactController.php →   _Bridges community 6 → community 5_
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 8 → community 6_

## Import Cycles
- None detected.

## Communities (22 total, 7 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (65): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+57 more)

### Community 1 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+18 more)

### Community 2 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 3 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 4 - "allow-plugins"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 5 - "LanguageToggleBlock.php"
Cohesion: 0.20
Nodes (10): Drupal\Core\Block\Attribute\Block, Drupal\Core\Block\BlockBase, Drupal\Core\Language\LanguageManagerInterface, Drupal\Core\Plugin\ContainerFactoryPluginInterface, Drupal\Core\Routing\RouteMatchInterface, Drupal\Core\StringTranslation\TranslatableMarkup, Drupal\Core\Url, Url (+2 more)

### Community 6 - "StaffContactController.php"
Cohesion: 0.25
Nodes (10): Drupal\Core\Controller\ControllerBase, Drupal\Core\Entity\EntityTypeManagerInterface, Drupal\Core\Logger\LoggerChannelFactoryInterface, Drupal\Core\Mail\MailManagerInterface, JsonResponse, Symfony\Component\DependencyInjection\ContainerInterface, Symfony\Component\HttpFoundation\JsonResponse, Symfony\Component\HttpFoundation\Request (+2 more)

### Community 7 - "montreal2027.theme"
Cohesion: 0.21
Nodes (10): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+2 more)

### Community 8 - "StaffPageController.php"
Cohesion: 0.29
Nodes (6): Drupal\Component\Utility\Html, Drupal\Core\Language\LanguageInterface, Drupal\Core\Render\Markup, Drupal\taxonomy\TermInterface, Drupal\taxonomy\TermStorageInterface, StaffPageController

### Community 9 - "MontrealConfigurationForm"
Cohesion: 0.36
Nodes (3): Drupal\Core\Form\ConfigFormBase, Drupal\Core\Form\FormStateInterface, MontrealConfigurationForm

### Community 10 - "ContactImportCommands"
Cohesion: 0.36
Nodes (3): Drupal\user\UserInterface, Drush\Commands\DrushCommands, ContactImportCommands

### Community 11 - "montreal2027_tools.module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

## Knowledge Gaps
- **123 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+118 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 153 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **7 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.207) - this node is a cross-community bridge._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.095) - this node is a cross-community bridge._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.059) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _123 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03076923076923077 - nodes in this community are weakly interconnected._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11764705882352941 - nodes in this community are weakly interconnected._