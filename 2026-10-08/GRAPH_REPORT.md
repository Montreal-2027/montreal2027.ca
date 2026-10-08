# Graph Report - montreal2027.ca  (2026-10-08)

## Corpus Check
- 23 files · ~20,296 words
- Verdict: corpus is large enough that graph structure adds value.
- Unclassified: 27 file(s) not represented in the graph (top: .twig 12, (none) 6, .patch 2)

## Summary
- 243 nodes · 275 edges · 24 communities (7 shown, 17 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `f82a634b`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- require
- installer-paths
- composer.json
- allow-plugins
- LanguageToggleBlock.php
- package.json
- montreal2027.theme
- StaffPageController.php
- StaffContactController.php
- MontrealConfigurationForm
- ContactImportCommands
- montreal2027_tools.module
- .mcp.json
- entrypoint.sh
- install.sh
- setup-graphify.sh
- sync.sh

## God Nodes (most connected - your core abstractions)
1. `require` - 58 edges
2. `allow-plugins` - 13 edges
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
  web/modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 7 → community 8_
- `LanguageToggleBlock` --references--> `Drupal\Core\Language\LanguageManagerInterface`  [EXTRACTED]
  web/modules/custom/montreal2027_tools/src/Plugin/Block/LanguageToggleBlock.php →   _Bridges community 4 → community 8_

## Import Cycles
- None detected.

## Communities (24 total, 17 thin omitted)

### Community 0 - "require"
Cohesion: 0.03
Nodes (58): require, composer/installers, cweagans/composer-patches, drupal/admin_theme, drupal/admin_toolbar, drupal/block_field, drupal/chosen, drupal/ckeditor5_open_new_tab (+50 more)

### Community 1 - "installer-paths"
Cohesion: 0.08
Nodes (26): include-keys, post-create-project-cmd-message, locations, Add Bluesky, Reset workflow state to draft when cloning workspace, extra, drupal-core-project-message, drupal-scaffold (+18 more)

### Community 2 - "composer.json"
Cohesion: 0.12
Nodes (16): conflict, drupal/drupal, description, homepage, license, minimum-stability, name, prefer-stable (+8 more)

### Community 3 - "allow-plugins"
Cohesion: 0.12
Nodes (17): composer/installers, cweagans/composer-patches, dealerdirect/phpcodesniffer-composer-installer, drupal/core-composer-scaffold, drupal/core-project-message, drupal/core-recipe-unpack, oomphinc/composer-installers-extender, php-http/discovery (+9 more)

### Community 5 - "package.json"
Cohesion: 0.12
Nodes (16): @parcel/watcher, sass, dependencies, @parcel/watcher, description, devDependencies, sass, engines (+8 more)

### Community 6 - "montreal2027.theme"
Cohesion: 0.19
Nodes (9): get_image_info(), montreal2027_entity_in_content_language(), montreal2027_form_alter(), montreal2027_preprocess_block__montreal2027_content(), montreal2027_preprocess_guest_taxonomy_paragraph(), montreal2027_preprocess_node__guest__teaser(), montreal2027_preprocess_page(), montreal2027_preprocess_paragraph__guest_block() (+1 more)

### Community 12 - ".mcp.json"
Cohesion: 0.25
Nodes (7): graphify-mcp, npx, obscura, fetch, graphify, obscura, @edictum/captatum

## Knowledge Gaps
- **121 isolated node(s):** `npx`, `@edictum/captatum`, `graphify-mcp`, `obscura`, `name` (+116 more)
  These have ≤1 connection - possible missing edges or undocumented components. (Counts symbols only; 155 node(s) total have ≤1 connection when file, concept and rationale nodes are included.)
- **17 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `require` connect `require` to `composer.json`?**
  _High betweenness centrality (0.172) - this node is a cross-community bridge._
- **What connects `npx`, `@edictum/captatum`, `graphify-mcp` to the rest of the system?**
  _121 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `require` be split into smaller, more focused modules?**
  _Cohesion score 0.034482758620689655 - nodes in this community are weakly interconnected._
- **Why does `extra` connect `installer-paths` to `composer.json`?**
  _High betweenness centrality (0.087) - this node is a cross-community bridge._
- **Should `installer-paths` be split into smaller, more focused modules?**
  _Cohesion score 0.07692307692307693 - nodes in this community are weakly interconnected._
- **Why does `config` connect `allow-plugins` to `composer.json`?**
  _High betweenness centrality (0.057) - this node is a cross-community bridge._
- **Should `composer.json` be split into smaller, more focused modules?**
  _Cohesion score 0.11764705882352941 - nodes in this community are weakly interconnected._