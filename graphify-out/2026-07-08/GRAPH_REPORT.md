# Graph Report - .  (2026-07-08)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 261 nodes · 298 edges · 31 communities (16 shown, 15 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `9904103f`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- installer-paths
- composer.json
- allow-plugins
- montreal2027.theme
- StaffPageSettingsForm
- package.json
- LanguageToggleBlock
- StaffContactController.php
- Web Service
- StaffPageController
- ContactImportCommands
- drush.services.yml
- montreal2027_tools.module
- entrypoint.sh
- pre-commit
- install.sh
- sync.sh
- Docker Compose Configuration
- New Content Issue Template
- Montréal 2027 build and deploy
- Auto-Update Graphify Map
- Recipes README
- Development Services Configuration
- montreal2027_tools.staff_contact_settings
- montreal2027_tools.staff_page_settings
- Montreal 2027 theme settings
- Montreal2027 Theme Info

## God Nodes (most connected - your core abstractions)
1. `require` - 64 edges
2. `allow-plugins` - 12 edges
3. `installer-paths` - 11 edges
4. `LanguageToggleBlock` - 10 edges
5. `StaffContactController` - 9 edges
6. `StaffPageController` - 7 edges
7. `extra` - 6 edges
8. `ContactImportCommands` - 6 edges
9. `StaffPageSettingsForm` - 6 edges
10. `StaffPageSettingsForm` - 6 edges

## Surprising Connections (you probably didn't know these)
- `StaffPageController` --inherits--> `ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 8 → community 10_

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Deployment Workflow Steps** — github_workflows_deploy [EXTRACTED 0.75]
- **Graphify Workflow Steps** — github_workflows_graphify [EXTRACTED 0.75]
- **Docker Services** — docker_web, docker_db [EXTRACTED 0.75]
- **Montreal 2027 Settings Group** — montreal2027_slideshow_display, montreal2027_slide_count, montreal2027_include_fontawesome [EXTRACTED 0.75]

## Communities (31 total, 15 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (64): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+56 more)

### Community 1 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+18 more)

### Community 2 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 3 - "allow-plugins"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 4 - "montreal2027.theme"
Cohesion: 0.16
Nodes (11): Include FontAwesome icons (CDN), Montreal 2027 settings, Number of slides, Show slideshow, get_image_info(), montreal2027_form_alter(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser() (+3 more)

### Community 5 - "StaffPageSettingsForm"
Cohesion: 0.19
Nodes (5): ConfigFormBase, FormStateInterface, StaffPageSettingsForm, FormStateInterface, StaffPageSettingsForm

### Community 6 - "package.json"
Cohesion: 0.13
Nodes (14): dependencies, @parcel/watcher, description, devDependencies, sass, engines, node, npm (+6 more)

### Community 7 - "LanguageToggleBlock"
Cohesion: 0.26
Nodes (8): BlockBase, ContainerFactoryPluginInterface, RouteMatchInterface, Url, LanguageToggleBlock, ContainerInterface, LanguageManagerInterface, static

### Community 8 - "StaffContactController.php"
Cohesion: 0.27
Nodes (10): ControllerBase, JsonResponse, LoggerChannelFactoryInterface, MailManagerInterface, Request, ContainerInterface, EntityTypeManagerInterface, LanguageManagerInterface (+2 more)

### Community 9 - "Web Service"
Cohesion: 0.22
Nodes (10): Backend Network, Docker Compose, Database Service, Database Volume, Frontend Network, Healthcheck Command, MariaDB, Nginx (+2 more)

### Community 10 - "StaffPageController"
Cohesion: 0.44
Nodes (3): TermInterface, TermStorageInterface, StaffPageController

### Community 11 - "ContactImportCommands"
Cohesion: 0.39
Nodes (3): DrushCommands, ContactImportCommands, EntityTypeManagerInterface

### Community 13 - "montreal2027_tools.module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

## Knowledge Gaps
- **139 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+134 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **15 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.168) - this node is a cross-community bridge._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.078) - this node is a cross-community bridge._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.049) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _139 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03125 - nodes in this community are weakly interconnected._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11764705882352941 - nodes in this community are weakly interconnected._