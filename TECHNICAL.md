# Montréal 2027 Technical Documentation <!-- omit in toc -->

## Table of Contents <!-- omit in toc -->
- [Technology Stack](#technology-stack)
- [Repository Structure](#repository-structure)
- [Further reading](#further-reading)

## Technology Stack

| Area | Technology |
| --- | --- |
| CMS | Drupal 11; Composer currently pins Drupal core to 11.4.8 |
| Production server runtime | PHP 8.3+, Apache, and mod_php |
| Local environment | Docker Compose (`web` and `db` services); native development is also documented |
| Default web container name | `montreal2027-web` (configurable with `WEB_CONTAINER_NAME`) |
| Docker runtime | PHP 8.3+, nginx, and PHP-FPM |
| Database | MariaDB 11.4 |
| PHP dependencies and CLI | Composer and Drush 13 |
| Theme assets | Sass compiled with Dart Sass; vanilla JavaScript |
| Theme build tooling | Node.js 18.19+ and npm 10.2+ |
| Search and email | Search API and PHPMailer SMTP integration |
| Configuration | Drupal configuration exported to and imported from `config/sync/` |

For exact dependency constraints, see [`composer.json`](composer.json) and [`package.json`](web/themes/custom/montreal2027/package.json).

## Repository Structure

```text
.
├── .github/                         # GitHub workflows and project templates
├── .githooks/                       # Local Git hooks
├── config/sync/                     # Exported Drupal site configuration
├── docker/                          # Web-container build and server configuration
├── patches/                         # Composer-managed dependency patches
├── scripts/                         # Setup, synchronization, and maintenance scripts
├── web/                             # Drupal document root and application
│   ├── modules/custom/
│   │   └── montreal2027_tools/      # Staff, contact, and import utilities
│   ├── modules/contrib/             # Composer-installed contributed modules
│   ├── themes/custom/
│   │   └── montreal2027/            # Custom theme and Sass source
│   ├── themes/contrib/               # Composer-installed contributed themes
│   └── sites/default/               # Site settings and public/private files
├── composer.json                    # PHP dependencies and Drupal installer paths
└── docker-compose.yml               # Local web and database services
```
Composer-managed Drupal core and dependencies are installed under `web/` and `vendor/`. Edit the Sass source under `web/themes/custom/montreal2027/src/scss/`; the CSS in `css/` is generated.

## Further reading
[Project README](README.md)
[How to contribute](CONTRIBUTING.md)
[Project Guidelines](PROJECT_GUIDELINES.md)
[Standard Operating Procedures](SOPS.md)
[Checklists](checklists/CHECKLISTS.MD)
