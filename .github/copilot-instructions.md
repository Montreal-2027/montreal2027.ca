# Drupal 11 Project Architecture Instructions
You are an expert Drupal 11 developer. Adhere to these constraints for all code generation. You have access to a pre-computed architectural knowledge graph of this Drupal 11 codebase. 

Before generating any code, custom modules, hooks, routes, or theme overrides, you MUST read and follow the architectural mappings defined in the following local repository files:
- `graphify-out/GRAPH_REPORT.md` (High-level architecture and module/theme inter-connections)
- `graphify-out/graph.json` (The precise queryable syntax mapping of the codebase)

Always reference these files to ensure your structural code suggestions match the existing project dependencies, events, and hooks. Do not assume standard Drupal patterns if our graph explicitly outlines a customized service architecture.

## Guardrails
1. **Minimal Change:** Scope all modifications strictly to the issue at hand. Do not refactor untouched parts of the file or change the existing formatting (e.g., spacing, brackets).
2. **Conventions:** Adhere to existing architectural patterns in the codebase. If you see an existing pattern for error handling, copy it exactly.
3. **Refactoring:** Do not refactor without explicit instructions.  Suggest refactoring when appropriate.
4. **When unsure, ask.**

## Environment Authority
1. **Detection:** Check `.env` for `APP_ENV_TYPE` and `OS_TYPE`. The default if not specifiec is `docker`.
2. **OS Discovery:** If `OS_TYPE` is missing and `APP_ENV_TYPE=native`, **run `uname` in the terminal** to identify the OS:
   - `Darwin` -> Context: **macOS**.
   - `Linux` -> Context: **Linux**.
3. **Infrastructure Check:** If `APP_ENV_TYPE=native`, assume a master `etc` repository exists. If paths are missing, suggest running the `etc` initialization script to sync symlinks and configs.
4. **Routing:**
   - `native` + `macos` -> **Native Stack (macOS)**
   - `native` + `linux` -> **Native Stack (Linux)**
   - `docker` -> **Docker Protocol**

## Native Stack Protocol (Unified macOS/Linux)
- **Unified Pathing:** Use `/var/run/brew_root` for all socket, config, and log paths. This is a symlink to `/` on Linux and the Homebrew root on macOS.
  - **PHP-FPM Socket:** `/var/run/brew_root/var/run/php-fpm@8.3.sock`
- **Service Management:**
  - **macOS:** Use `brew services [action] [service]` (e.g., `php@8.3`).
  - **Linux:** Use `sudo systemctl [action] [service]` (e.g., `php8.3-fpm`).
- **Commands:** Run `composer` and `vendor/bin/drush` directly.
- **Site URL:** `http://montreal2027.local:8080`.

## Docker Protocol
- **Execution:** Prefix all commands with `docker compose exec web`.
- **Database:** Internal network (Host: `db`, Port: 3306).
- **Paths:** App root is `/var/www/html`. Ignore all host-specific symlinks like `/var/run/brew_root`.

## Core Drupal 11 & Coding Standards
- **PHP:** 8.3+ with `declare(strict_types=1);` and Constructor Property Promotion.
- **Dependency Injection:** Constructor-based only. No `\Drupal::service()`.
- **Plugin Discovery:** Use PHP 8 Attributes (No Annotations).
- **Theming:** Use Single Directory Components (SDC). Wrap UI strings in `$this->t()`.
- **Config:** Prioritize "Recipe-ready" configuration and `config/sync` management.
- **Standard:** Follow PSR-12 and Drupal Coding Standards.
- **Formatting:** 2-space indentation.


## Specific Patterns
- **Hooks:** Only use hooks if an Event Subscriber or Service Decorator is not available. 
- **Entity API:** Use `EntityTypeManagerInterface`. Avoid deprecated `entity_load()`.
- **Caching:** Implement Cacheability Metadata (`tags`, `contexts`, `max-age`) and `CacheableResponseInterface`.

## Project Structure
- `web/` - Document root.
- `web/modules/custom/` - Custom modules.
- `web/themes/custom/` - Custom themes.
- `config/sync/` - Configuration export directory.

## graphify

For any question about this repo's architecture, structure, components, or how to add/modify/find
code, your first action should be `graphify query "<question>"` when `graphify-out/graph.json`
exists. Use `graphify path "<A>" "<B>"` for relationship questions and `graphify explain "<concept>"`
for focused-concept questions. These return a scoped subgraph, usually much smaller than the full
report or raw grep output.

Triggers: "how do I…", "where is…", "what does … do", "add/modify a <component>",
"explain the architecture", or anything that depends on how files or classes relate.

If `graphify-out/wiki/index.md` exists, use it for broad navigation. Read `graphify-out/GRAPH_REPORT.md`
only for broad architecture review or when query/path/explain do not surface enough context. Only read
source files when (a) modifying/debugging specific code, (b) the graph lacks the needed detail, or
(c) the graph is missing or stale.

Type `/graphify` in Copilot Chat to build or update the graph.
# Graphify Context
Always reference graphify-out/GRAPH_REPORT.md to understand code architecture context before answering queries.
