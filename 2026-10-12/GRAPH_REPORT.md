# Graph Report - montreal2027.ca  (2026-10-12)

## Corpus Check
- 24 files · ~20,433 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 27 file(s) not represented in the graph (top: .twig 12, (none) 6, .patch 2)

## Summary
- 249 nodes · 280 edges · 25 communities (7 shown, 18 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `81932b44`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- installer-paths
- allow-plugins
- composer.json
- package.json
- LanguageToggleBlock.php
- montreal2027.theme
- StaffContactController.php
- StaffPageController.php
- MontrealConfigurationForm
- ContactImportCommands
- montreal2027_tools.module
- .mcp.json
- entrypoint.sh
- install.sh
- setup-graphify.sh
- sync.sh

## God Nodes (most connected - your core abstractions)
1. `require` - 59 edges
2. `allow-plugins` - 13 edges
3. `installer-paths` - 11 edges
4. `LanguageToggleBlock` - 10 edges
5. `StaffContactController` - 9 edges
6. `extra` - 7 edges
7. `StaffPageController` - 7 edges
8. `ContactImportCommands` - 6 edges
9. `MontrealConfigurationForm` - 6 edges
10. `config` - 5 edges

## Surprising Connections (you probably didn't know these)
- `StaffContactController` --references--> `Drupal\Core\Language\LanguageManagerInterface`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffContactController.php →   _Bridges community 7 → community 5_
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 8 → community 7_

## Import Cycles
- None detected.

## Communities (25 total, 18 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (59): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+51 more)

### Community 1 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message, drupal-scaffold (+18 more)

### Community 2 - "allow-plugins"
Cohesion: 0.10
Nodes (20): ignore, composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender (+12 more)

### Community 3 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 4 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "montreal2027.theme"
Cohesion: 0.19
Nodes (9): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+1 more)

### Community 12 - ".mcp.json"
Cohesion: 0.25
Nodes (7): graphify-mcp, npx, obscura, fetch, graphify, obscura, @edictum/captatum

## Knowledge Gaps
- **6 isolated node(s):** `npx`, `@edictum/captatum`, `graphify-mcp`, `obscura`, `sass` (+1 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 159 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **18 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.173) - this node is a cross-community bridge._
- **What connects `npx`, `@edictum/captatum`, `graphify-mcp` to the rest of the system?**
  _6 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03389830508474576 - nodes in this community are weakly interconnected._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.086) - this node is a cross-community bridge._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.066) - this node is a cross-community bridge._
- **Should `allow-plugins` be split into smaller, more focused modules?**
  _Cohesion score 0.1 - nodes in this community are weakly interconnected._