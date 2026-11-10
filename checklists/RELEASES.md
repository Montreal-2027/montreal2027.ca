# Release checklists <!-- omit from toc -->

## Table of Contents <!-- omit from toc -->
- [Scheduled release](#scheduled-release)
  - [**Prepare the release**](#prepare-the-release)
  - [**Test the release**](#test-the-release)
  - [**Deploy to production**](#deploy-to-production)
- [Non-content Hotfix release](#non-content-hotfix-release)
  - [**Prepare the release**](#prepare-the-release-1)
  - [**Test the release**](#test-the-release-1)
  - [**Deploy to production**](#deploy-to-production-1)
  - [**Backport changes to `develop`**](#backport-changes-to-develop)
  - [**Clean up after merge**](#clean-up-after-merge)
- [Content hotfix release](#content-hotfix-release)

## Scheduled release
All scheduled releases will be performed by the Area Head or any team member that the Area Head has delegated the responsibility to. Releases are scheduled for every other Wednesday at 23:30 UTC-4. The following steps will be followed to deploy a release to production:

### **Prepare the release**
   - [ ] Merge `develop` to `main`
   - [ ] Create a release on GitHub. Tag the release with the latest version number from the `CHANGELOG`, and use `Version X.Y.Z` for the release title
   - [ ] Send an email to `dh@montreal2027.ca` reminding them of the upcoming release with a brief list of items being deployed, including content updates. You don't have to go into detail.  Here is an actual email that was sent for a release.

        >Good evening,
        >
        >    As a reminder, we have a website release tonight at 11:30pm Eastern (UTC-4).  Tonight release includes:
        >    - Updates to the membership rate page to display better on mobile devices
        >    - Added the “Mon Ami” membership type to the member rate tables
        >    - Changed the button on the home page to say “Membership” instead of “Attend Worldcon”
        >    - Updates to the minor policy in the Code of Conduct and Polices page
        >
        >The next release will be on 10/14/2026.
    
### **Test the release**
   - [ ] `cd /home/cansmof/stg.montreal2027.ca`
   - [ ] `drush sql:dump --gzip > ../db_backups/stg-m2027.$(date +'%Y-%m-%d_%H:%M:%S').sql.gz`
   - [ ] Verify that you are on the `main` branch. (`git branch`)
   - [ ] `git pull`
   - [ ] `composer install --no-dev`
   - [ ] Run `./scripts/sync.sh` to sync assets and grab a current copy of the database
   - [ ] `drush deploy`
   - [ ] Import menus (`drush im`) using Full import style. This step is optional
   - [ ] `(cd web/themes/custom/montreal2027 && npm ci && npm install && npm run build:css)`
   - [ ] Publish staged content
   - [ ] Verify correct operation of https://stg.montreal2027.ca
   - [ ] Once functionality has been confirmed, notify the Web Team, including the Project Manager, for further verification and testing.
### **Deploy to production**
   - [ ] Receive approval to proceed with the release.
   - [ ] `cd /home/cansmof/mirror.montreal2027.ca`
   - [ ] `drush sql:dump --gzip > ../db_backups/prd-m2027.$(date +'%Y-%m-%d_%H:%M:%S').sql.gz`
   - [ ] Verify that you are on the `main` branch. (`git branch`)
   - [ ] `git pull`
   - [ ] `composer install --no-dev`
   - [ ] `drush deploy`
   - [ ] Import menus (`drush im`) using Full import style. This step is optional
   - [ ] `(cd web/themes/custom/montreal2027 && npm ci && npm install && npm run build:css)` 
   - [ ] Verify correct operation of https://montreal2027.ca
   - [ ] Once functionality has been confirmed, notify the current Release Manager, who will publish any staged content changes
   - [ ] Once the staged content has been published, the current Release Manager will notify the Project Manager for further verification and testing.
 
 ## Non-content Hotfix release

A hotfix release is only done when there is an issue that is determined to be an emergency. The issues that can be considered an emergency can vary, but typically involve software bugs, style, or layout issues that adversely effect the website. The designation of an incident as an emergency can only be made by the Communications or Technology and Production Division Head. Depending on the nature and severity of the issue, multiple team members will be asked to assist in making what ever corrections that are needed. Hotfix releases are deployed as soon as testing and verification has been completed of the `hotfix/*` branch. The process is very similar to a scheduled release, with some extra steps.

### **Prepare the release**
   - [ ] Merge `main-hotfix` to `main`
   - [ ] Create a release on GitHub. Tag the release with the latest version number from the `CHANGELOG`, and use `Version X.Y.Z (Hotfix)` for the release title
   - [ ] Send an email to `dh@montreal2027.ca` advising them of an emergency release. For example,

        >Good evening,
        >
        >   Due to a recent security vulnerability, we will be performing an emergency release immediately. We apologize for the short notice, but the severity of the vulnerability necessitated a rapid response. Thank you for your understanding.
    
### **Test the release**
   - [ ] `cd /home/cansmof/stg.montreal2027.ca`
   - [ ] `drush sql:dump --gzip > ../db_backups/stg-m2027.$(date +'%Y-%m-%d_%H:%M:%S').sql.gz`
   - [ ] `git fetch && git switch main-hotfix`
   - [ ] `composer install --no-dev`
   - [ ] Run `./scripts/sync.sh` to sync assets and grab a current copy of the database
   - [ ] `drush deploy`
   - [ ] `(cd web/themes/custom/montreal2027 && npm ci && npm install && npm run build:css)`
   - [ ] Verify correct operation of https://stg.montreal2027.ca
   - [ ] Once functionality has been confirmed, notify the Web Team, including the Project Manager, for further verification and testing.
### **Deploy to production**
   - [ ] Receive approval to proceed with the release.
   - [ ] `cd /home/cansmof/mirror.montreal2027.ca`
   - [ ] `drush sql:dump --gzip > ../db_backups/prd-m2027.$(date +'%Y-%m-%d_%H:%M:%S').sql.gz`
   - [ ] `git fetch && git switch main-hotfix`
   - [ ] `composer install --no-dev`
   - [ ] `drush deploy`
   - [ ] `(cd web/themes/custom/montreal2027 && npm ci && npm install && npm run build:css)` 
   - [ ] Verify correct operation of https://montreal2027.ca
   - [ ] Once the site has been updated, the current Release Manager will notify the Project Manager for further verification and testing.
### **Backport changes to `develop`**
   - [ ] Create a `backport` branch from `main`.
   - [ ] Copy the most recent `CHANGELOG` entry from `develop` and add it to the top of the file and adjust the version number
   - [ ] Create a pull request against the `develop` branch. See [Pull Request Checklist](checklists/PULL_REQUESTS.md)
### **Clean up after merge**
   - [ ] Delete the `main-hotfix` and `backport` branches.


## Content hotfix release
1. [ ] Log in to the [Montreal 2027 website](https://montreal2027.ca) and create a new workspace for the hotfix, to keep it isolated from any other content waiting to be published.
2. [ ] In this workspace, make the required content updates.
3. [ ] Proofread all changes
4. [ ] Notify the Area Head or designated individual, who will publish the content changes
5. [ ] Once the content changes have been published, the current Release Manager will notify the Project Manager for further verification and testing.
 
