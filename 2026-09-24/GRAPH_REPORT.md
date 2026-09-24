# Graph Report - montreal2027.ca  (2026-09-24)

## Corpus Check
- 23 files · ~20,336 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 26 file(s) not represented in the graph (top: .twig 12, (none) 5, .patch 2)

## Summary
- 244 nodes · 277 edges · 22 communities (11 shown, 11 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `2d232956`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- LanguageToggleBlock.php
- installer-paths
- composer.json
- package.json
- allow-plugins
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
1. `require` - 68 edges
2. `allow-plugins` - 12 edges
3. `installer-paths` - 11 edges
4. `LanguageToggleBlock` - 10 edges
5. `StaffContactController` - 9 edges
6. `extra` - 7 edges
7. `StaffPageController` - 7 edges
8. `ContactImportCommands` - 6 edges
9. `MontrealConfigurationForm` - 6 edges
10. `config` - 4 edges

## Surprising Connections (you probably didn't know these)
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 7 → community 1_

## Import Cycles
- None detected.

## Communities (22 total, 11 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (68): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+60 more)

### Community 1 - "LanguageToggleBlock.php"
Cohesion: 0.13
Nodes (18): Drupal\Core\Block\Attribute\Block, Drupal\Core\Block\BlockBase, Drupal\Core\Controller\ControllerBase, Drupal\Core\Entity\EntityTypeManagerInterface, Drupal\Core\Language\LanguageInterface, Drupal\Core\Language\LanguageManagerInterface, Drupal\Core\Logger\LoggerChannelFactoryInterface, Drupal\Core\Mail\MailManagerInterface (+10 more)

### Community 2 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message, drupal-scaffold (+18 more)

### Community 3 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 4 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 5 - "allow-plugins"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 6 - "montreal2027.theme"
Cohesion: 0.19
Nodes (11): html, get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page() (+3 more)

### Community 7 - "StaffPageController.php"
Cohesion: 0.29
Nodes (6): Drupal\Component\Utility\Html, Drupal\Core\Render\Markup, Drupal\Core\Url, Drupal\taxonomy\TermInterface, Drupal\taxonomy\TermStorageInterface, StaffPageController

### Community 8 - "MontrealConfigurationForm"
Cohesion: 0.36
Nodes (3): Drupal\Core\Form\ConfigFormBase, Drupal\Core\Form\FormStateInterface, MontrealConfigurationForm

### Community 9 - "ContactImportCommands"
Cohesion: 0.36
Nodes (3): Drupal\user\UserInterface, Drush\Commands\DrushCommands, ContactImportCommands

### Community 10 - "montreal2027_tools.module"
Cohesion: 0.32
Nodes (5): entityforminterface, translatablemarkup, FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

## Knowledge Gaps
- **126 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+121 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 160 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **11 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.210) - this node is a cross-community bridge._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.094) - this node is a cross-community bridge._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.058) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _126 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.029411764705882353 - nodes in this community are weakly interconnected._
- **Should `LanguageToggleBlock.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12962962962962962 - nodes in this community are weakly interconnected._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._