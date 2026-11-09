# GitHub Issues & Projects <!-- omit in toc -->

## Table of Contents <!-- omit in toc -->
- [Introduction](#introduction)

## Introduction
The Montréal 2027 project uses [GitHub](https://github.com/Montreal-2027) for issue tracking and project management. Issues are created for both [technical issues](https://github.com/orgs/Montreal-2027/projects/1) and [content requests(https://github.com/orgs/Montreal-2027/projects/2)], and each has it's own Project, as well as a third Project for [content that requires translation](https://github.com/orgs/Montreal-2027/projects/3). This document serves as an overview of how issues and Projects are used.

## Issue statuses
Each project has predefined status that are appropriate to that project. The terms _**issue**_ and _**ticket**_ are interchangeable.

### Content Project
| Status | Description |
|---|---|
| `Todo` | This is the default status. The issue has just been created, and has not been started|
| `In Progress` | This issue has been started and is actively being worked on |
| `Ready to deploy` | All content for this issue has been completed. English and French language content are two distinct but related issues |
| `Done` | The completed issue has successfully been deployed to the production server |
| `On Hold` | An issue that had been `In Progress`, but had to be stopped for some reason. Should rarely be used for content |
| `Info Needed` | Questions or concerns about the content request have been sent to the submitter and we are waiting for a response |
| `Won't Fix/Superseded` | Also rarely used for content, but an occasional issue my have a new issue created that supersedes a previous issue |

### Tech Project 
| Status | Description |
|---|---|
| `Backlog` | This is the default status. The issue has not be started |
| `Planning` | This item is actively being planned/scoped |
| `In progress` | This is actively being worked on |
| `PR Opened` | A Pull Request has been opened |
| `PR in review` | Code review of the Pull Request has started |
| `PR Merged` | The Pull Request has been merged to the `develop` branch |
| `Ready to deploy` | All work has been completed, the PR has been approved, and merged into `develop` |
| `Done` | The completed item has been deployed |
| `Info Needed` | Questions or concerns about the feature or enhancement request has been sent to the submitter and we are waiting for a response |
| `On Hold` | Development has been paused on a previous `In progress` issue. This is not uncommon to be used in this project |
| `PR Closed` | The Pull Request has been closed without being merged |
| `Won't Fix` | The item (Feature request, Enhancement request, Bug) won't be fixed. A comment **MUST** be left on the issue explaining the reason for this status |

### Translation Project
| Status | Description |
|---|---|
| `Todo` | This is the default status. The issue has just been created, and has not been started|
| `In Progress` | This issue has been started and is actively being worked on |
| `Ready to deploy` | All content for this issue has been completed. English and French language content are two distinct but related issues |
| `Done` | The completed issue has successfully been deployed to the production server | 

## Issue Labels
Issue labels are used to show at a glance what kind of work needs to be done for that issue. All projects share the same issue labels.

| Label | Description |
|---|---|
| `Bug` | A problem with the function of the site.  **Do Not** use this for issues with the content, such as typos, misspellings, or missing content |
| `Carousel` | A slide to be added, changed, or deleted from a carousel |
| `Configuration` | Configuration changes needing to be made to the site |
| `Content` | Add, change, or delete content in either language |
| `CSS` | CSS/Layout only additions, changes, or deletions |
| `Deploy` | A task required for an upcoming deploy |
| `Enhancement` | A request to enhance an existing feature of the site |
| `Feature Request` | A request for a new feature |
| `Security Update` | Mitigation for security issues |
| `Team Only` | Items for the Team Only area of the website |
| `Template` | Twig template creation, modification or deletion |
| `Translation` | Requests for content translation |
| `Upgrades` | Contrib modules & themes or Drupal Core that need to be updated |

## Issue types
To further confuse the issue, an issue can also be assigned a type. There are three issue types that overlap with issue labels, which is how it can be confusing. In the case of those three, as long as the issue has been tagged with either the label or the type, it will serve the same purpose.

| Type | Description |
|---|---|
| Task | A specific piece of work to be accomplished |
| Bug | An unexpect problem or behavior with the site, **not content**|
| Feature | A request, idea, or new functionality for the site |
| Research Spike | Researching a topic for a possible feature or enhancement |
| Enhancement | An enhancement to existing functionality |
| Subtask | A child task that often blocks the parent ticket until it's completed |
| Tech Debt | Cleaning up or refactoring old code |
| Epic | Multi-issue and/or multi-sprint related issues |



