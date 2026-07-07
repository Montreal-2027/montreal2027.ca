# Graph Report - web  (2026-07-07)

## Corpus Check
- 11 files · ~5,626 words
- Verdict: corpus is large enough that graph structure adds value.

## Summary
- 77 nodes · 79 edges · 21 communities (19 shown, 2 thin omitted)
- Extraction: 100% EXTRACTED · 0% INFERRED · 0% AMBIGUOUS
- Token cost: 0 input · 0 output

## Graph Freshness
- Built from commit: `a01198da`
- Run `git rev-parse HEAD` and compare to check if the graph is stale.
- Run `graphify update .` after code changes (no API cost).

## Community Hubs (Navigation)
- [[_COMMUNITY_Themes Custom Montreal2027|Themes Custom Montreal2027]]
- [[_COMMUNITY_Containerinterface Controllerbase Jsonresponse|Containerinterface Controllerbase Jsonresponse]]
- [[_COMMUNITY_Configformbase Modules Custom|Configformbase Modules Custom]]
- [[_COMMUNITY_Drushcommands Entitytypemanagerinterface Modules|Drushcommands Entitytypemanagerinterface Modules]]
- [[_COMMUNITY_Modules Custom Montreal2027|Modules Custom Montreal2027]]
- [[_COMMUNITY_Modules Custom Montreal2027|Modules Custom Montreal2027]]
- [[_COMMUNITY_Readme|Readme]]

## God Nodes (most connected - your core abstractions)
1. `ContactImportCommands` - 6 edges
2. `StaffPageController` - 6 edges
3. `StaffPageSettingsForm` - 6 edges
4. `StaffPageSettingsForm` - 6 edges
5. `StaffContactController` - 5 edges
6. `engines` - 3 edges
7. `scripts` - 3 edges
8. `Contributing` - 1 edges
9. `Usage` - 1 edges
10. `Changelog` - 1 edges

## Surprising Connections (you probably didn't know these)
- `StaffPageController` --inherits--> `ControllerBase`  [EXTRACTED]
  modules/custom/montreal2027_tools/src/Controller/StaffPageController.php →   _Bridges community 1 → community 4_
- `StaffPageSettingsForm` --inherits--> `ConfigFormBase`  [EXTRACTED]
  modules/custom/montreal2027_tools/src/Form/StaffContactSettingsForm.php →   _Bridges community 5 → community 2_

## Import Cycles
- None detected.

## Communities (21 total, 2 thin omitted)

### Community 0 - "Themes Custom Montreal2027"
Cohesion: 0.13
Nodes (14): dependencies, @parcel/watcher, description, devDependencies, sass, engines, node, npm (+6 more)

### Community 1 - "Containerinterface Controllerbase Jsonresponse"
Cohesion: 0.29
Nodes (6): ContainerInterface, ControllerBase, JsonResponse, StaffContactController, Request, static

### Community 2 - "Configformbase Modules Custom"
Cohesion: 0.36
Nodes (3): ConfigFormBase, FormStateInterface, StaffPageSettingsForm

### Community 3 - "Drushcommands Entitytypemanagerinterface Modules"
Cohesion: 0.39
Nodes (3): DrushCommands, EntityTypeManagerInterface, ContactImportCommands

### Community 16 - "Readme"
Cohesion: 0.29
Nodes (6): Changelog, Contributing, Legal matters, Need a helping hand?, Security, Usage

## Knowledge Gaps
- **16 isolated node(s):** `Contributing`, `Usage`, `Changelog`, `Security`, `Need a helping hand?` (+11 more)
  These have ≤1 connection - possible missing edges or undocumented components.
- **2 thin communities (<3 nodes) omitted from report** — run `graphify query` to explore isolated nodes.

## Suggested Questions
_Questions this graph is uniquely positioned to answer:_

- **Why does `StaffPageController` connect `Modules Custom Montreal2027` to `Containerinterface Controllerbase Jsonresponse`?**
  _High betweenness centrality (0.027) - this node is a cross-community bridge._
- **What connects `Contributing`, `Usage`, `Changelog` to the rest of the system?**
  _16 weakly-connected nodes found - possible documentation gaps or missing edges._
- **Should `Themes Custom Montreal2027` be split into smaller, more focused modules?**
  _Cohesion score 0.13333333333333333 - nodes in this community are weakly interconnected._