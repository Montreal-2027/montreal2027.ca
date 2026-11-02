# Graph Report - montreal2027.ca  (2026-11-02)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 630 nodes · 684 edges · 59 communities (44 shown, 15 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `3d8b95ee`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- label
- mapping
- composer.json
- db
- StaffContactController
- LanguageToggleBlock.php
- steps
- regions
- installer-paths
- web
- language_toggle
- montreal2027_tools.staff_contact_submit
- twig.config
- package.json
- montreal2027.theme
- global
- empty_position_link
- MontrealConfigurationForm
- montreal2027_tools.module
- .mcp.json
- montreal2027_tools.commands
- montreal2027_tools.settings
- mapping
- montreal2027_tools.info.yml
- montreal2027_tools.settings
- montreal2027.settings
- include_fontawesome
- slide_title_1
- slide_title_10
- slide_title_2
- slide_title_3
- slide_title_4
- slide_title_5
- slide_title_6
- slide_title_7
- slide_title_8
- slide_title_9
- slide_url_1
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
1. `require` - 59 edges
2. `mapping` - 34 edges
3. `StaffContactController` - 16 edges
4. `regions` - 16 edges
5. `allow-plugins` - 13 edges
6. `installer-paths` - 11 edges
7. `LanguageToggleBlock` - 10 edges
8. `label` - 10 edges
9. `type` - 10 edges
10. `web` - 9 edges

## Surprising Connections (you probably didn't know these)
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 6 → community 5_

## Import Cycles
- None detected.

## Communities (59 total, 15 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (59): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+51 more)

### Community 1 - "label"
Cohesion: 0.06
Nodes (42): slide_image_1, slide_image_10, slide_image_2, slide_image_3, slide_image_4, slide_image_5, slide_image_6, slide_image_7 (+34 more)

### Community 2 - "mapping"
Cohesion: 0.05
Nodes (40): label, type, label, type, label, type, label, type (+32 more)

### Community 3 - "composer.json"
Cohesion: 0.05
Nodes (36): ignore, composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender (+28 more)

### Community 4 - "db"
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

### Community 10 - "web"
Cohesion: 0.09
Nodes (23): PHP_VERSION, WEB_INTERNAL_PORT, args, context, dockerfile, condition, db, DB_HOST (+15 more)

### Community 11 - "language_toggle"
Cohesion: 0.10
Nodes (20): css/language-toggle.css, css/staff-contact-modal.css, component, js/language-toggle.js, js/staff-contact-modal.js, js/staff-jump-menu.js, language_toggle, css (+12 more)

### Community 12 - "montreal2027_tools.staff_contact_submit"
Cohesion: 0.14
Nodes (17): _controller, _form, _title, montreal2027_tools.settings, defaults, path, requirements, montreal2027_tools.staff (+9 more)

### Community 13 - "twig.config"
Cohesion: 0.11
Nodes (17): class, arguments, class, tags, arguments, parent, parameters, http.response.debug_cacheability_headers (+9 more)

### Community 14 - "package.json"
Cohesion: 0.13
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher@^2.5.6, description, devDependencies, sass@^1.73.1, engines (+8 more)

### Community 15 - "montreal2027.theme"
Cohesion: 0.19
Nodes (9): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+1 more)

### Community 16 - "global"
Cohesion: 0.17
Nodes (11): css/montreal2027.min.css, https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css, base, global, css, dependencies, js, minified (+3 more)

### Community 17 - "empty_position_link"
Cohesion: 0.20
Nodes (10): label, mapping, type, empty_position_link, title, uri, label, type (+2 more)

### Community 20 - ".mcp.json"
Cohesion: 0.25
Nodes (7): graphify-mcp, npx, obscura, fetch, graphify, obscura, @edictum/captatum

### Community 21 - "montreal2027_tools.commands"
Cohesion: 0.29
Nodes (6): arguments, class, tags, services, montreal2027_tools.commands, name

### Community 22 - "montreal2027_tools.settings"
Cohesion: 0.29
Nodes (6): montreal2027_tools.settings, description, parent, route_name, title, weight

### Community 23 - "mapping"
Cohesion: 0.29
Nodes (7): slide_count, slide_url_6, mapping, label, type, label, type

### Community 24 - "montreal2027_tools.info.yml"
Cohesion: 0.33
Nodes (5): core_version_requirement, description, name, package, type

### Community 25 - "montreal2027_tools.settings"
Cohesion: 0.40
Nodes (4): montreal2027_tools.settings, base_route_name, names, title

### Community 26 - "montreal2027.settings"
Cohesion: 0.50
Nodes (3): montreal2027.settings, label, type

### Community 27 - "include_fontawesome"
Cohesion: 0.67
Nodes (3): label, type, include_fontawesome

### Community 28 - "slide_title_1"
Cohesion: 0.67
Nodes (3): slide_title_1, label, type

### Community 29 - "slide_title_10"
Cohesion: 0.67
Nodes (3): slide_title_10, label, type

### Community 30 - "slide_title_2"
Cohesion: 0.67
Nodes (3): slide_title_2, label, type

### Community 31 - "slide_title_3"
Cohesion: 0.67
Nodes (3): slide_title_3, label, type

### Community 32 - "slide_title_4"
Cohesion: 0.67
Nodes (3): slide_title_4, label, type

### Community 33 - "slide_title_5"
Cohesion: 0.67
Nodes (3): slide_title_5, label, type

### Community 34 - "slide_title_6"
Cohesion: 0.67
Nodes (3): slide_title_6, label, type

### Community 35 - "slide_title_7"
Cohesion: 0.67
Nodes (3): slide_title_7, label, type

### Community 36 - "slide_title_8"
Cohesion: 0.67
Nodes (3): slide_title_8, label, type

### Community 37 - "slide_title_9"
Cohesion: 0.67
Nodes (3): slide_title_9, label, type

### Community 38 - "slide_url_1"
Cohesion: 0.67
Nodes (3): slide_url_1, label, type

### Community 39 - "slide_url_10"
Cohesion: 0.67
Nodes (3): slide_url_10, label, type

### Community 40 - "slide_url_2"
Cohesion: 0.67
Nodes (3): slide_url_2, label, type

### Community 41 - "slide_url_3"
Cohesion: 0.67
Nodes (3): slide_url_3, label, type

### Community 42 - "slide_url_4"
Cohesion: 0.67
Nodes (3): slide_url_4, label, type

### Community 43 - "slide_url_5"
Cohesion: 0.67
Nodes (3): slide_url_5, label, type

### Community 44 - "slide_url_7"
Cohesion: 0.67
Nodes (3): slide_url_7, label, type

### Community 45 - "slide_url_8"
Cohesion: 0.67
Nodes (3): slide_url_8, label, type

### Community 46 - "slide_url_9"
Cohesion: 0.67
Nodes (3): slide_url_9, label, type

### Community 47 - "slideshow_display"
Cohesion: 0.67
Nodes (3): slideshow_display, label, type

## Knowledge Gaps
- **4 isolated node(s):** `graphify-mcp`, `npx`, `obscura`, `@edictum/captatum`
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 385 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **15 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `mapping` connect `mapping` to `label`, `montreal2027.settings`, `include_fontawesome`, `slide_title_1`, `slide_title_10`, `slide_title_2`, `slide_title_3`, `slide_title_4`, `slide_title_5`, `slide_title_6`, `slide_title_7`, `slide_title_8`, `slide_title_9`, `slide_url_1`, `slide_url_10`, `slide_url_2`, `slide_url_3`, `slide_url_4`, `slide_url_5`, `slide_url_7`, `slide_url_8`, `slide_url_9`, `slideshow_display`?**
  _High betweenness centrality (0.031) - this node is a cross-community bridge._
- **What connects `graphify-mcp`, `npx`, `obscura` to the rest of the system?**
  _4 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03389830508474576 - nodes in this community are weakly interconnected._
- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **Should `label` be split into smaller, more focused modules?**
  _Cohesion score 0.05807200929152149 - nodes in this community are weakly interconnected._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.013) - this node is a cross-community bridge._
- **Should `mapping` be split into smaller, more focused modules?**
  _Cohesion score 0.05121951219512195 - nodes in this community are weakly interconnected._