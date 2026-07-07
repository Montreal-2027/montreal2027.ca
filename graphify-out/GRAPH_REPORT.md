# Graph Report - .  (2026-07-07)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 294 nodes · 344 edges · 35 communities (18 shown, 17 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `46271fea`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- installer-paths
- LanguageToggleBlock
- Key Files for AI Agents
- composer.json
- allow-plugins
- montreal2027.theme
- StaffPageSettingsForm
- package.json
- StaffContactController.php
- Web Service
- Project Core Rules
- drush.services.yml
- ContactImportCommands
- montreal2027_tools.module
- Drupal README
- entrypoint.sh
- pre-commit
- install.sh
- sync.sh
- Code Style
- Documentation
- Project Structure
- Quick Start
- Docker Compose Configuration
- New Content Issue Template
- Montréal 2027 build and deploy
- Auto-Update Graphify Map
- Recipes README
- montreal2027_tools.staff_contact_settings
- montreal2027_tools.staff_page_settings
- Montreal 2027 theme settings

## God Nodes (most connected - your core abstractions)
1. `require` - 64 edges
2. `allow-plugins` - 12 edges
3. `installer-paths` - 11 edges
4. `LanguageToggleBlock` - 10 edges
5. `StaffContactController` - 9 edges
6. `Montreal 2027 Tools` - 8 edges
7. `Web Service` - 7 edges
8. `extra` - 6 edges
9. `ContactImportCommands` - 6 edges
10. `StaffPageController` - 6 edges

## Surprising Connections (you probably didn't know these)
- `Changelog` --cites--> `Overview`  [EXTRACTED]
  CHANGELOG.md → AGENTS.md
- `Montreal 2027 WorldCon Website` --references--> `Database Service`  [EXTRACTED]
  README.md → docker-compose.yml
- `Montreal 2027 WorldCon Website` --references--> `Web Service`  [EXTRACTED]
  README.md → docker-compose.yml
- `Architect Persona` --references--> `Copilot Instructions`  [EXTRACTED]
  .continue/prompts/architect.md → .github/copilot-instructions.md
- `Backend Role` --references--> `Copilot Instructions`  [EXTRACTED]
  .continue/prompts/backend.md → .github/copilot-instructions.md

## Import Cycles
- None detected.

## Hyperedges (group relationships)
- **Drupal 11 Standards** — continue_prompts_architect, continue_prompts_backend, continue_prompts_frontend, continue_rules_project_rules [EXTRACTED 0.75]
- **Docker Commands** — agents_common_commands, agents_deployment, agents_git_hooks [EXTRACTED 0.75]
- **Drush Commands** — agents_common_commands, agents_development_conventions, agents_testing_changes [EXTRACTED 0.75]
- **Technology Stack Components** — agents_technology_stack, drupal, docker, composer, npm, mariadb, scss [EXTRACTED 0.75]
- **Docker Services** — docker_web, docker_db [EXTRACTED 0.75]
- **Montreal 2027 Tools Module** — web_modules_custom_montreal2027_tools_readme, web_modules_custom_montreal2027_tools_drush_services_yml, web_modules_custom_montreal2027_tools_montreal2027_tools_info_yml, web_modules_custom_montreal2027_tools_montreal2027_tools_routing_yml, web_modules_custom_montreal2027_tools_montreal2027_tools_config_translation_yml, web_modules_custom_montreal2027_tools_config_install_montreal2027_tools_staff_contact_settings_yml, web_modules_custom_montreal2027_tools_config_schema_montreal2027_tools_schema_yml, web_modules_custom_montreal2027_tools_montreal2027_tools_libraries_yml, web_modules_custom_montreal2027_tools_montreal2027_tools_links_menu_yml [EXTRACTED 0.75]
- **Montreal 2027 Settings Group** — montreal2027_slideshow_display, montreal2027_slide_count, montreal2027_include_fontawesome [EXTRACTED 0.75]
- **Deployment Workflow Steps** — github_workflows_deploy [EXTRACTED 0.75]
- **Graphify Workflow Steps** — github_workflows_graphify [EXTRACTED 0.75]
- **Drupal Documentation References** — web_readme, web_development_services_yml, web_themes_custom_montreal2027_readme [EXTRACTED 0.75]

## Communities (35 total, 17 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (64): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+56 more)

### Community 1 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, Refactor to AliasManagerInterface, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message (+18 more)

### Community 2 - "LanguageToggleBlock"
Cohesion: 0.18
Nodes (10): BlockBase, ContainerFactoryPluginInterface, RouteMatchInterface, TermStorageInterface, Url, StaffPageController, LanguageToggleBlock, ContainerInterface (+2 more)

### Community 3 - "Key Files for AI Agents"
Cohesion: 0.16
Nodes (18): Common Commands, Common Pitfalls, Deployment, Development Conventions, Git Hooks, Key Files for AI Agents, Overview, Technology Stack (+10 more)

### Community 4 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 5 - "allow-plugins"
Cohesion: 0.12
Nodes (16): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+8 more)

### Community 6 - "montreal2027.theme"
Cohesion: 0.16
Nodes (11): Include FontAwesome icons (CDN), Montreal 2027 settings, Number of slides, Show slideshow, get_image_info(), montreal2027_form_alter(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser() (+3 more)

### Community 7 - "StaffPageSettingsForm"
Cohesion: 0.19
Nodes (5): ConfigFormBase, FormStateInterface, StaffPageSettingsForm, FormStateInterface, StaffPageSettingsForm

### Community 8 - "package.json"
Cohesion: 0.13
Nodes (14): dependencies, @parcel/watcher, description, devDependencies, sass, engines, node, npm (+6 more)

### Community 9 - "StaffContactController.php"
Cohesion: 0.27
Nodes (10): ControllerBase, JsonResponse, LoggerChannelFactoryInterface, MailManagerInterface, Request, ContainerInterface, EntityTypeManagerInterface, LanguageManagerInterface (+2 more)

### Community 10 - "Web Service"
Cohesion: 0.22
Nodes (11): Montreal 2027 WorldCon Website, Backend Network, Docker Compose, Database Service, Database Volume, Frontend Network, Healthcheck Command, MariaDB (+3 more)

### Community 11 - "Project Core Rules"
Cohesion: 0.28
Nodes (9): Architect Persona, Backend Role, Frontend Role, Project Core Rules, Architect Agent, Backend Agent, Frontend Agent, Copilot Instructions (+1 more)

### Community 13 - "ContactImportCommands"
Cohesion: 0.39
Nodes (3): DrushCommands, ContactImportCommands, EntityTypeManagerInterface

### Community 14 - "montreal2027_tools.module"
Cohesion: 0.47
Nodes (3): FormStateInterface, montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter()

### Community 15 - "Drupal README"
Cohesion: 0.50
Nodes (4): Development Services Configuration, Drupal README, Montreal2027 Theme Info, Montreal2027 Theme README

## Knowledge Gaps
- **149 isolated node(s):** `name`, `description`, `type`, `license`, `homepage` (+144 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **17 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.159) - this node is a cross-community bridge._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.072) - this node is a cross-community bridge._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.045) - this node is a cross-community bridge._
- **What connects `name`, `description`, `type` to the rest of the system?**
  _149 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03125 - nodes in this community are weakly interconnected._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11764705882352941 - nodes in this community are weakly interconnected._