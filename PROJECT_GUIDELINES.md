# Project Guidelines

## Introduction

Thank you for your interest in contributing to the Montréal 2027 project. This document is aimed at both the developer and the non-technical content maintainers. Some items may seem like an oversimplification for you or over your head.  Don't worry. If that is the case, it probably means that the item in question is intended for the other type of user 😆. Sections that are exclusively technical in nature have been marked with  💻. This document includes technical information about the project, as well as checklists and standard operating procedures for the Web Team. If you have any questions or concerns about anything in this document, please do not hesitate to contact the [Web Team Leadership](mailto:website@montreal2027.ca).

## Before You Contribute

All prospective contributors must contact the [Web Team Leadership](mailto:website@montreal2027.ca) and wait for guidance before proceeding. See [CONTRIBUTING.md](CONTRIBUTING.md) for onboarding details.

## Project at a Glance 👀

This repository contains the Drupal website for the 85th World Science Fiction Convention to be held in Montréal in September, 2027. Drupal provides content management and site functionality; the custom `montreal2027` theme provides the site's presentation, and `montreal2027_tools` contains project-specific utilities.

## What is a repository? ℹ️
A [repository](https://en.wikipedia.org/wiki/Repository_(version_control)), is a collection of metadata and files, stored in a directory structure. A repository, or repo, can be purely local, or distributed, where there is a copy of the repository on every user's system. This project uses a hybrid of the two types: the software developers maintain a copy of the repository on their local system, and there is a central repository ([GitHub](https://github.com)) that is the "source of truth".  The purpose of a repository in this type of instance, is to provide a version control system.  That is, every time a file is committed to the repository, the difference between the current version and the version before it is saved to the hard disk, as opposed to the whole file.  The only time the complete file is saved is when it is first added to the repository. By storing just the differences, any previous version can be retrieved, and any two versions can be compared to show the differences. For this project, the repository contains these informational documents, custom software, custom layout templates, and styling.

## Technical Documentation 💻
See [Technical Documentation](TECHNICAL.md) for details.







#### Style, template, or code fixes



## Further Reading

- [README.md](README.md) — setup, Docker/native environments, scripts, Git hooks, and troubleshooting
- [CONTRIBUTING.md](CONTRIBUTING.md) — contributor onboarding
- [Theme README](web/themes/custom/montreal2027/README.md) — Sass build instructions
- [Custom module README](web/modules/custom/montreal2027_tools/README.md) — module utilities and commands
