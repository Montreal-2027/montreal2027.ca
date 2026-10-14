# Graph Report - montreal2027.ca  (2026-10-14)

## Corpus Check
- cluster-only mode — file stats not available

## Summary
- 247 nodes · 279 edges · 23 communities (7 shown, 16 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `bfabb6fb`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- LanguageToggleBlock.php
- installer-paths
- allow-plugins
- composer.json
- package.json
- montreal2027.theme
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
6. `StaffPageController` - 7 edges
7. `extra` - 7 edges
8. `MontrealConfigurationForm` - 6 edges
9. `ContactImportCommands` - 6 edges
10. `config` - 5 edges

## Surprising Connections (you probably didn't know these)
- `StaffPageController` --inherits--> `Drupal\Core\Controller\ControllerBase`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 7 → community 1_

## Import Cycles
- None detected.

## Communities (23 total, 16 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (59): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+51 more)

### Community 2 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message, drupal-scaffold (+18 more)

### Community 3 - "allow-plugins"
Cohesion: 0.10
Nodes (20): ignore, composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender (+12 more)

### Community 4 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 5 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "montreal2027.theme"
Cohesion: 0.19
Nodes (9): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+1 more)

### Community 11 - ".mcp.json"
Cohesion: 0.25
Nodes (7): graphify-mcp, npx, obscura, fetch, graphify, obscura, @edictum/captatum

## Knowledge Gaps
- **6 isolated node(s):** `graphify-mcp`, `npx`, `obscura`, `@edictum/captatum`, `@parcel/watcher` (+1 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 157 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **16 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.176) - this node is a cross-community bridge._
- **What connects `graphify-mcp`, `npx`, `obscura` to the rest of the system?**
  _6 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.03389830508474576 - nodes in this community are weakly interconnected._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Should `LanguageToggleBlock.php` be split into smaller, more focused modules?**
  _Cohesion score 0.12962962962962962 - nodes in this community are weakly interconnected._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.067) - this node is a cross-community bridge._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._