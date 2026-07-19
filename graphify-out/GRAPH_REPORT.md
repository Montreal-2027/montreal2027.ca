# Graph Report - .  (2026-07-19)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 269 nodes · 310 edges · 21 communities (16 shown, 5 thin omitted)
- Extraction: 99% EXTRACTED · 1% INFERRED · 0% AMBIGUOUS · INFERRED: 2 edges (avg confidence: 0.78)
- Token cost: 1,044 input · 209 output

## Graph Freshness
- Built from commit: `df8fefec`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Drupal Admin Tools
- Project Setup Configuration
- Drupal Core Interfaces
- Installer Paths and Recipes
- Composer Project Metadata
- Node Package Configuration
- Composer Plugin Management
- Drupal Form Management
- Montreal 2027 Theme Functions
- Montreal 2027 Tools Configuration
- Staff Page Management
- Contact Import Commands
- Montreal 2027 Tools Module
- Montreal 2027 Theme Assets
- Entrypoint Script
- Pre-commit Hook
- Install Script
- Sync Script

## God Nodes (most connected - your core abstractions)
1. `require` - 64 edges
2. `allow-plugins` - 12 edges
3. `post-create-project-cmd-message` - 12 edges
4. `installer-paths` - 11 edges
5. `LanguageToggleBlock` - 10 edges
6. `StaffContactController` - 9 edges
7. `Montreal 2027 Tools Module Info` - 8 edges
8. `StaffPageController` - 7 edges
9. `extra` - 6 edges
10. `ContactImportCommands` - 6 edges

## Surprising Connections (you probably didn't know these)
- `Docker Compose Configuration` --references--> `Montreal 2027 Tools Module Info`  [EXTRACTED]
  docker-compose.yml → web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml
- `Docker Compose Configuration` --references--> `Drush Services Configuration`  [EXTRACTED]
  docker-compose.yml → web/modules/custom/montreal2027_tools/drush.services.yml
- `Drush Services Configuration` --shares_data_with--> `Montreal 2027 Tools Module Info`  [INFERRED]
  web/modules/custom/montreal2027_tools/drush.services.yml → web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml
- `Montreal 2027 Tools Module Info` --references--> `Montreal 2027 Tools Staff Contact Settings`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml → web/modules/custom/montreal2027_tools/config/install/montreal2027_tools.staff_contact_settings.yml
- `Montreal 2027 Tools Module Info` --references--> `Montreal 2027 Tools Schema`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml → web/modules/custom/montreal2027_tools/config/schema/montreal2027_tools.schema.yml

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Montreal 2027 Tools Module Components** — web_modules_custom_montreal2027_tools_drush_services, web_modules_custom_montreal2027_tools_montreal2027_tools_info, web_modules_custom_montreal2027_tools_montreal2027_tools_routing, web_modules_custom_montreal2027_tools_montreal2027_tools_links_menu, web_modules_custom_montreal2027_tools_montreal2027_tools_config_translation, web_modules_custom_montreal2027_tools_config_install_montreal2027_tools_staff_contact_settings, web_modules_custom_montreal2027_tools_config_schema_montreal2027_tools_schema, web_modules_custom_montreal2027_tools_montreal2027_tools_libraries [EXTRACTED 0.75]

## Communities (21 total, 5 thin omitted)

### Community 0 - "Drupal Admin Tools"
Cohesion: 0.03
Nodes (64): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+56 more)

### Community 1 - "Project Setup Configuration"
Cohesion: 0.07
Nodes (30): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+22 more)

### Community 2 - "Drupal Core Interfaces"
Cohesion: 0.14
Nodes (17): Drupal\Core\Block\BlockBase, Drupal\Core\Controller\ControllerBase, Drupal\Core\Entity\EntityTypeManagerInterface, Drupal\Core\Language\LanguageManagerInterface, Drupal\Core\Logger\LoggerChannelFactoryInterface, Drupal\Core\Mail\MailManagerInterface, Drupal\Core\Plugin\ContainerFactoryPluginInterface, Drupal\Core\Routing\RouteMatchInterface (+9 more)

### Community 3 - "Installer Paths and Recipes"
Cohesion: 0.10
Nodes (22): installer-paths, drush/Commands/contrib/{$name}, recipes/{$name}, web/core, web/libraries/{$name}, web/modules/contrib/{$name}, web/modules/custom/{$name}, web/profiles/contrib/{$name} (+14 more)

### Community 4 - "Composer Project Metadata"
Cohesion: 0.12
Nodes (17): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+9 more)

### Community 5 - "Node Package Configuration"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "Composer Plugin Management"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 7 - "Drupal Form Management"
Cohesion: 0.22
Nodes (4): Drupal\Core\Form\ConfigFormBase, Drupal\Core\Form\FormStateInterface, StaffPageSettingsForm, StaffPageSettingsForm

### Community 8 - "Montreal 2027 Theme Functions"
Cohesion: 0.21
Nodes (10): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+2 more)

### Community 9 - "Montreal 2027 Tools Configuration"
Cohesion: 0.25
Nodes (9): Docker Compose Configuration, Montreal 2027 Tools Staff Contact Settings, Montreal 2027 Tools Schema, Drush Services Configuration, Montreal 2027 Tools Config Translation, Montreal 2027 Tools Module Info, Montreal 2027 Tools Libraries, Montreal 2027 Tools Menu Links (+1 more)

### Community 10 - "Staff Page Management"
Cohesion: 0.44
Nodes (3): Drupal\taxonomy\TermInterface, Drupal\taxonomy\TermStorageInterface, StaffPageController

### Community 12 - "Montreal 2027 Tools Module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

### Community 13 - "Montreal 2027 Theme Assets"
Cohesion: 0.67
Nodes (3): Montreal 2027 Theme Schema, Montreal 2027 Theme Info, Montreal 2027 Theme Libraries

## Knowledge Gaps
- **137 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+132 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **5 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `Drupal Admin Tools` to `Composer Project Metadata`?**
  _High betweenness centrality (0.206) - this node is a cross-community bridge._
- **Why does `extra` connect `Project Setup Configuration` to `Installer Paths and Recipes`, `Composer Project Metadata`?**
  _High betweenness centrality (0.165) - this node is a cross-community bridge._
- **Why does `installer-paths` connect `Installer Paths and Recipes` to `Project Setup Configuration`?**
  _High betweenness centrality (0.081) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _137 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Drupal Admin Tools` be split into smaller, more focused modules?**
  _Cohesion score 0.03125 - nodes in this community are weakly interconnected._
- **Should `Project Setup Configuration` be split into smaller, more focused modules?**
  _Cohesion score 0.06666666666666667 - nodes in this community are weakly interconnected._
- **Should `Drupal Core Interfaces` be split into smaller, more focused modules?**
  _Cohesion score 0.1396011396011396 - nodes in this community are weakly interconnected._