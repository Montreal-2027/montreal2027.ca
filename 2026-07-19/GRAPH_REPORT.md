# Graph Report - .  (2026-07-19)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 272 nodes · 307 edges · 26 communities (16 shown, 10 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS · INFERRED: 1 edges (avg confidence: 0.8)
- Token cost: 1,077 input · 260 output

## Graph Freshness
- Built from commit: `a81fe924`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- Drupal Admin Tools
- Project Setup Utilities
- Drupal Core Components
- Installation Paths
- Composer Configuration
- Node Package Management
- Composer Plugin Management
- Staff Page Forms
- Montreal 2027 Theme
- Staff Page Taxonomy
- Montreal 2027 Configuration
- Contact Import Commands
- Montreal 2027 Tools Module
- Montreal 2027 Theme Info
- Entrypoint Script
- Pre-commit Hook
- Install Script
- Sync Script
- Montreal 2027 Tools Schema
- Montreal 2027 Config Translation
- Staff Page Routing
- Staff Contact Submission Route
- Staff Page Settings Route

## God Nodes (most connected - your core abstractions)
1. `require` - 64 edges
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
- `Docker Compose Configuration` --references--> `Drush Services Configuration`  [EXTRACTED]
  docker-compose.yml → web/modules/custom/montreal2027_tools/drush.services.yml
- `Drush Services Configuration` --references--> `Montreal 2027 Tools Info`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/drush.services.yml → web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml
- `Montreal 2027 Tools Info` --references--> `Montreal 2027 Tools Libraries`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml → web/modules/custom/montreal2027_tools/montreal2027_tools.libraries.yml
- `Montreal 2027 Tools Info` --references--> `Montreal 2027 Tools Routing`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/montreal2027_tools.info.yml → web/modules/custom/montreal2027_tools/montreal2027_tools.routing.yml
- `Montreal 2027 Tools Routing` --references--> `Montreal 2027 Tools Configuration Install`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/montreal2027_tools.routing.yml → web/modules/custom/montreal2027_tools/config/install/montreal2027_tools.staff_contact_settings.yml

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Montreal 2027 Tools Module Components** — web_modules_custom_montreal2027_tools_drush_services, web_modules_custom_montreal2027_tools_montreal2027_tools_info, web_modules_custom_montreal2027_tools_montreal2027_tools_routing, web_modules_custom_montreal2027_tools_montreal2027_tools_links_menu, web_modules_custom_montreal2027_tools_config_install [EXTRACTED 0.75]

## Communities (26 total, 10 thin omitted)

### Community 0 - "Drupal Admin Tools"
Cohesion: 0.03
Nodes (64): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+56 more)

### Community 1 - "Project Setup Utilities"
Cohesion: 0.07
Nodes (30): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+22 more)

### Community 2 - "Drupal Core Components"
Cohesion: 0.14
Nodes (17): Drupal\Core\Block\BlockBase, Drupal\Core\Controller\ControllerBase, Drupal\Core\Entity\EntityTypeManagerInterface, Drupal\Core\Language\LanguageManagerInterface, Drupal\Core\Logger\LoggerChannelFactoryInterface, Drupal\Core\Mail\MailManagerInterface, Drupal\Core\Plugin\ContainerFactoryPluginInterface, Drupal\Core\Routing\RouteMatchInterface (+9 more)

### Community 3 - "Installation Paths"
Cohesion: 0.10
Nodes (22): installer-paths, drush/Commands/contrib/{$name}, recipes/{$name}, web/core, web/libraries/{$name}, web/modules/contrib/{$name}, web/modules/custom/{$name}, web/profiles/contrib/{$name} (+14 more)

### Community 4 - "Composer Configuration"
Cohesion: 0.12
Nodes (17): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+9 more)

### Community 5 - "Node Package Management"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "Composer Plugin Management"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 7 - "Staff Page Forms"
Cohesion: 0.22
Nodes (4): Drupal\Core\Form\ConfigFormBase, Drupal\Core\Form\FormStateInterface, StaffPageSettingsForm, StaffPageSettingsForm

### Community 8 - "Montreal 2027 Theme"
Cohesion: 0.21
Nodes (10): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+2 more)

### Community 9 - "Staff Page Taxonomy"
Cohesion: 0.44
Nodes (3): Drupal\taxonomy\TermInterface, Drupal\taxonomy\TermStorageInterface, StaffPageController

### Community 10 - "Montreal 2027 Configuration"
Cohesion: 0.29
Nodes (7): Docker Compose Configuration, Montreal 2027 Tools Configuration Install, Drush Services Configuration, Montreal 2027 Tools Info, Montreal 2027 Tools Libraries, Montreal 2027 Tools Menu Links, Montreal 2027 Tools Routing

### Community 12 - "Montreal 2027 Tools Module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

### Community 13 - "Montreal 2027 Theme Info"
Cohesion: 0.67
Nodes (3): Montreal 2027 Theme Configuration Schema, Montreal 2027 Theme Info, Montreal 2027 Theme Libraries

## Knowledge Gaps
- **140 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+135 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **10 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `Drupal Admin Tools` to `Composer Configuration`?**
  _High betweenness centrality (0.201) - this node is a cross-community bridge._
- **Why does `extra` connect `Project Setup Utilities` to `Installation Paths`, `Composer Configuration`?**
  _High betweenness centrality (0.161) - this node is a cross-community bridge._
- **Why does `installer-paths` connect `Installation Paths` to `Project Setup Utilities`?**
  _High betweenness centrality (0.079) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _140 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Drupal Admin Tools` be split into smaller, more focused modules?**
  _Cohesion score 0.03125 - nodes in this community are weakly interconnected._
- **Should `Project Setup Utilities` be split into smaller, more focused modules?**
  _Cohesion score 0.06666666666666667 - nodes in this community are weakly interconnected._
- **Should `Drupal Core Components` be split into smaller, more focused modules?**
  _Cohesion score 0.1396011396011396 - nodes in this community are weakly interconnected._