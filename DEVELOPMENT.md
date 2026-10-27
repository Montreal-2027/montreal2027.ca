# Development

## Workflow
Follow the complete setup instructions in [`README.md`](README.md). The usual Docker-based procedure is:

1. Start the services with `docker compose up -d`.
2. Install PHP dependencies with `docker exec montreal2027-web composer install`.
3. Make and verify changes locally. See [Change and Review Checklist](checklists/CHANGE_REVIEW.md)
4. For theme work, run `npm install` and `npm run watch:css` from `web/themes/custom/montreal2027/`; use `npm run build:css` for a one-time build.
5. Clear Drupal caches after relevant changes with `docker exec montreal2027-web vendor/bin/drush cr`.

The repository also documents a native PHP, MariaDB, and Nginx setup. Use the matching commands and environment settings from the [README](README.md) for that workflow.

## Branching Strategy
The default branch is `main`. Do not push directly to `develop` or `main`; changes to either branch must done via a pull request. Because of the automatic deployment of pushes to the `feature` and `hotfix` branches to GitHub, ensure that no other development team member is in the process of testing a branch.

### Drupal Configuration and Local Data

Drupal configuration is version-controlled in `config/sync/`. After changing site configuration in the admin UI, export it with Drush and include the resulting YAML changes in the same contribution. The configured pre-commit hook checks for configuration drift.

Keep local environment settings and credentials out of Git. Review the `README` before using scripts that synchronize production files or database content.

## Development workflow

### Feature work

1. Create a `feature/<issue-number>_<short-description>` branch from `develop`, or `origin/develop` if your local branch is not up-to-date. For example `feature/135_contact-form`.
2. Add/Update the `CHANGELOG` entry with a brief description of all changes. If you did more than one related change in this feature, list all significant changes such as modules being added, updated, or removed; configuration changes; or templates being added, updated or removed. Follow the style of previous `CHANGELOG` entries.
3. Merge changes from `develop` into your working branch on a regular basis.
4. Don't forget to export any configuration changes and commit them to the repository.
5. When your work has been completed, push the branch to GitHub and use the [development site](https://dev.montreal2027.ca) to verify the your work after it's been deployed.
6. Open a pull request targeting `develop`. See [Pull Request Checklist](checklists/PULL_REQUESTS.md)

### Hotfixes

1. Delete `main-hotfix` branch, both from your local repository and GitHub if it exists. Create a `main-hotfix` branch from `main`, or `origin/main` if your local branch is not up to date. 
2. Create a `hotfix/<issue-number>_<short-description>` branch from `main-hotfix`. For example, `hotfix/135_contact-form`, 
3. Add the CHANGELOG entry. The current sprint will need to be renumbered, as you will be using that version number for this hotfix.
4. Don't forget to export any configuration changes and commit them to the repository.
5. When your work has been completed, push the branch to GitHub and use the [development site](https://dev.montreal2027.ca) to verify the change after it's deployed.
6. Open a pull request targeting `main-hotfix`. See [Pull Request Checklist](checklists/PULL_REQUESTS.md)

*The current theme is not yet fully accessible. Until it is redesigned, contributors should make a best effort to target WCAG 2.1 Level AA for accessibility.*

## Pull Request Procedure

See [Pull Request Checklist](checklists/PULL_REQUESTS.md)

## Release Overview

- Feature and Hotfix branches are automatically deployed to [the development server](https://dev.montreal2027.ca) after a push to GitHub
- The GitHub Actions workflow will scan for secrets/credentials.  If any are found, the deploy will be aborted.
- Deployments to [staging](https://stg.montreal2027.ca) and [production](https://montreal2027.ca) are not automated and will be done by hand to allow for the rapid detection of problems.
- If problems do develop, the team member performing the release will make the decision using their best judgement.

See [Release Checklists](checklists/RELEASES.md) for more information.
