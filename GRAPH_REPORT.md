# Graph Report - montreal2027.ca  (2026-11-11)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 807 nodes · 1010 edges · 72 communities (57 shown, 15 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f46412c6`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- WorkspaceMenuTreeManipulatorTest.php
- Drupal\migrate\Row
- StaffContactController
- composer.json
- db
- InMemoriamImportCommands.php
- steps
- regions
- installer-paths
- label
- web
- language_toggle
- package.json
- montreal2027.theme
- montreal2027_tools.staff_contact_submit
- twig.config
- global
- mapping
- montreal2027_tools.guest_settings
- format
- montreal2027_tools.commands
- mapping
- empty_position_link
- services
- montreal2027_tools.module
- MontrealConfigurationForm
- .mcp.json
- montreal2027_tools.info.yml
- montreal2027_tools.settings
- publish in_memoriam content
- montreal2027_tools.settings
- montreal2027.settings
- slide_image_4
- slide_image_5
- slide_image_7
- slide_image_8
- heading_prefix
- page_title
- success_message
- include_fontawesome
- slide_title_10
- slide_title_2
- slide_title_3
- slide_title_4
- slide_title_5
- slide_title_6
- slide_title_7
- slide_title_8
- slide_title_9
- slide_url_10
- slide_url_2
- slide_url_3
- slide_url_4
- slide_url_5
- slide_url_7
- slide_url_8
- slide_url_9
- slideshow_display
- entrypoint.sh
- install.sh
- setup-graphify.sh
- sync.sh

## God Nodes (most connected - your core abstractions)
1. `require` - 68 edges
2. `mapping` - 34 edges
3. `StaffContactController` - 16 edges
4. `regions` - 16 edges
5. `InMemoriamImportCommands` - 15 edges
6. `WorkspaceMenuTreeManipulatorTest` - 13 edges
7. `allow-plugins` - 13 edges
8. `InMemoriamNodeLookup` - 12 edges
9. `InMemoriamAccessHooksTest` - 11 edges
10. `installer-paths` - 11 edges

## Surprising Connections (you probably didn't know these)
- `SuperfishHooks` --references--> `WorkspaceMenuTreeManipulator`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Hook/SuperfishHooks.php → web/modules/custom/montreal2027_tools/src/Menu/WorkspaceMenuTreeManipulator.php

## Import Cycles
- None detected.

## Communities (72 total, 15 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (68): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+60 more)

### Community 1 - "WorkspaceMenuTreeManipulatorTest.php"
Cohesion: 0.07
Nodes (5): InMemoriamAccessHooks, SuperfishHooks, WorkspaceMenuTreeManipulator, InMemoriamAccessHooksTest, WorkspaceMenuTreeManipulatorTest

### Community 2 - "Drupal\migrate\Row"
Cohesion: 0.08
Nodes (6): InMemoriamCategories, InMemoriamDate, InMemoriamNodeLookup, InMemoriamSortDate, InMemoriamTrim, InMemoriamSpreadsheet

### Community 3 - "StaffContactController"
Cohesion: 0.07
Nodes (3): StaffContactController, StaffPageController, LanguageToggleBlock

### Community 4 - "composer.json"
Cohesion: 0.05
Nodes (36): ignore, composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender (+28 more)

### Community 5 - "db"
Cohesion: 0.07
Nodes (29): internal, name, command, container_name, name, environment, healthcheck, image (+21 more)

### Community 7 - "steps"
Cohesion: 0.07
Nodes (28): permissions, runs-on, steps, BRANCH, FOLDER, GITHUB_TOKEN, MESSAGE, REPO (+20 more)

### Community 8 - "regions"
Cohesion: 0.07
Nodes (28): base theme, core_version_requirement, layout, description, css/views-responsive-grid.css, libraries, libraries-override, views/views.responsive-grid (+20 more)

### Community 9 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message, drupal-scaffold (+18 more)

### Community 10 - "label"
Cohesion: 0.09
Nodes (26): slide_image_1, slide_image_10, slide_image_2, slide_image_3, slide_image_6, slide_image_9, label, type (+18 more)

### Community 11 - "web"
Cohesion: 0.09
Nodes (23): PHP_VERSION, WEB_INTERNAL_PORT, args, context, dockerfile, condition, db, DB_HOST (+15 more)

### Community 12 - "language_toggle"
Cohesion: 0.10
Nodes (20): css/language-toggle.css, css/staff-contact-modal.css, component, js/language-toggle.js, js/staff-contact-modal.js, js/staff-jump-menu.js, language_toggle, css (+12 more)

### Community 13 - "package.json"
Cohesion: 0.12
Nodes (18): @parcel/watcher, sass, allowScripts, @parcel/watcher@2.5.6, dependencies, @parcel/watcher@^2.5.6, description, devDependencies (+10 more)

### Community 14 - "montreal2027.theme"
Cohesion: 0.15
Nodes (9): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+1 more)

### Community 15 - "montreal2027_tools.staff_contact_submit"
Cohesion: 0.14
Nodes (17): _controller, _form, _title, montreal2027_tools.settings, defaults, path, requirements, montreal2027_tools.staff (+9 more)

### Community 16 - "twig.config"
Cohesion: 0.11
Nodes (17): class, arguments, class, tags, arguments, parent, parameters, http.response.debug_cacheability_headers (+9 more)

### Community 17 - "global"
Cohesion: 0.12
Nodes (15): css/montreal2027.min.css, https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css, base, global, css, dependencies, js, minified (+7 more)

### Community 18 - "mapping"
Cohesion: 0.15
Nodes (13): slide_count, slide_title_1, slide_url_1, slide_url_6, mapping, label, type, label (+5 more)

### Community 19 - "montreal2027_tools.guest_settings"
Cohesion: 0.18
Nodes (10): label, type, guest_read_more_text, montreal2027_tools.guest_settings, label, mapping, type, montreal2027_tools.staff_page_settings (+2 more)

### Community 20 - "format"
Cohesion: 0.20
Nodes (11): label, type, format, page_body, value, label, mapping, type (+3 more)

### Community 21 - "montreal2027_tools.commands"
Cohesion: 0.20
Nodes (10): arguments, class, tags, arguments, class, tags, services, montreal2027_tools.commands (+2 more)

### Community 22 - "mapping"
Cohesion: 0.20
Nodes (10): label, type, label, type, contact_link_text, failure_message, subject_prefix, mapping (+2 more)

### Community 23 - "empty_position_link"
Cohesion: 0.20
Nodes (10): label, mapping, type, empty_position_link, title, uri, label, type (+2 more)

### Community 24 - "services"
Cohesion: 0.20
Nodes (9): class, arguments, class, arguments, class, services, Drupal\montreal2027_tools\Hook\InMemoriamAccessHooks, Drupal\montreal2027_tools\Hook\SuperfishHooks (+1 more)

### Community 25 - "montreal2027_tools.module"
Cohesion: 0.31
Nodes (3): montreal2027_tools_form_taxonomy_overview_terms_alter(), montreal2027_tools_form_taxonomy_term_form_alter(), montreal2027_tools_form_user_register_form_alter()

### Community 27 - ".mcp.json"
Cohesion: 0.25
Nodes (7): graphify-mcp, npx, obscura, fetch, graphify, obscura, @edictum/captatum

### Community 28 - "montreal2027_tools.info.yml"
Cohesion: 0.29
Nodes (6): core_version_requirement, dependencies, description, name, package, type

### Community 29 - "montreal2027_tools.settings"
Cohesion: 0.29
Nodes (6): montreal2027_tools.settings, description, parent, route_name, title, weight

### Community 30 - "publish in_memoriam content"
Cohesion: 0.29
Nodes (6): publish in_memoriam content, description, title, view any unpublished in_memoriam content, description, title

### Community 31 - "montreal2027_tools.settings"
Cohesion: 0.40
Nodes (4): montreal2027_tools.settings, base_route_name, names, title

### Community 32 - "montreal2027.settings"
Cohesion: 0.50
Nodes (3): montreal2027.settings, label, type

### Community 33 - "slide_image_4"
Cohesion: 0.50
Nodes (4): slide_image_4, label, sequence, type

### Community 34 - "slide_image_5"
Cohesion: 0.50
Nodes (4): slide_image_5, label, sequence, type

### Community 35 - "slide_image_7"
Cohesion: 0.50
Nodes (4): slide_image_7, label, sequence, type

### Community 36 - "slide_image_8"
Cohesion: 0.50
Nodes (4): slide_image_8, label, sequence, type

### Community 38 - "heading_prefix"
Cohesion: 0.67
Nodes (3): label, type, heading_prefix

### Community 39 - "page_title"
Cohesion: 0.67
Nodes (3): page_title, label, type

### Community 40 - "success_message"
Cohesion: 0.67
Nodes (3): success_message, label, type

### Community 41 - "include_fontawesome"
Cohesion: 0.67
Nodes (3): label, type, include_fontawesome

### Community 42 - "slide_title_10"
Cohesion: 0.67
Nodes (3): slide_title_10, label, type

### Community 43 - "slide_title_2"
Cohesion: 0.67
Nodes (3): slide_title_2, label, type

### Community 44 - "slide_title_3"
Cohesion: 0.67
Nodes (3): slide_title_3, label, type

### Community 45 - "slide_title_4"
Cohesion: 0.67
Nodes (3): slide_title_4, label, type

### Community 46 - "slide_title_5"
Cohesion: 0.67
Nodes (3): slide_title_5, label, type

### Community 47 - "slide_title_6"
Cohesion: 0.67
Nodes (3): slide_title_6, label, type

### Community 48 - "slide_title_7"
Cohesion: 0.67
Nodes (3): slide_title_7, label, type

### Community 49 - "slide_title_8"
Cohesion: 0.67
Nodes (3): slide_title_8, label, type

### Community 50 - "slide_title_9"
Cohesion: 0.67
Nodes (3): slide_title_9, label, type

### Community 51 - "slide_url_10"
Cohesion: 0.67
Nodes (3): slide_url_10, label, type

### Community 52 - "slide_url_2"
Cohesion: 0.67
Nodes (3): slide_url_2, label, type

### Community 53 - "slide_url_3"
Cohesion: 0.67
Nodes (3): slide_url_3, label, type

### Community 54 - "slide_url_4"
Cohesion: 0.67
Nodes (3): slide_url_4, label, type

### Community 55 - "slide_url_5"
Cohesion: 0.67
Nodes (3): slide_url_5, label, type

### Community 56 - "slide_url_7"
Cohesion: 0.67
Nodes (3): slide_url_7, label, type

### Community 57 - "slide_url_8"
Cohesion: 0.67
Nodes (3): slide_url_8, label, type

### Community 58 - "slide_url_9"
Cohesion: 0.67
Nodes (3): slide_url_9, label, type

### Community 59 - "slideshow_display"
Cohesion: 0.67
Nodes (3): slideshow_display, label, type

## Knowledge Gaps
- **4 isolated node(s):** `graphify-mcp`, `npx`, `obscura`, `@edictum/captatum`
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 445 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **15 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.020) - this node is a cross-community bridge._
- **What connects `graphify-mcp`, `npx`, `obscura` to the rest of the system?**
  _4 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.029411764705882353 - nodes in this community are weakly interconnected._
- **Why does `mapping` connect `mapping` to `label`, `montreal2027.settings`, `slide_image_4`, `slide_image_5`, `slide_image_7`, `slide_image_8`, `include_fontawesome`, `slide_title_10`, `slide_title_2`, `slide_title_3`, `slide_title_4`, `slide_title_5`, `slide_title_6`, `slide_title_7`, `slide_title_8`, `slide_title_9`, `slide_url_10`, `slide_url_2`, `slide_url_3`, `slide_url_4`, `slide_url_5`, `slide_url_7`, `slide_url_8`, `slide_url_9`, `slideshow_display`?**
  _High betweenness centrality (0.019) - this node is a cross-community bridge._
- **Should `WorkspaceMenuTreeManipulatorTest.php` be split into smaller, more focused modules?**
  _Cohesion score 0.06623376623376623 - nodes in this community are weakly interconnected._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.009) - this node is a cross-community bridge._
- **Should `Drupal\migrate\Row` be split into smaller, more focused modules?**
  _Cohesion score 0.08246753246753247 - nodes in this community are weakly interconnected._