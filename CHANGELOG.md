# Montreal 2027 WorldCon Website

## Changelog
---
## 1.1.3 (2026-11-18)
- Fixed line spacing for `<summary>` tags ([#275](https://github.com/Montreal-2027/montreal2027.ca/issues/275))
- Updated Carousel Page content type configuration to better support adding a node with a news carousel ([#235](https://github.com/Montreal-2027/montreal2027.ca/issues/235))
- Additional roles for team members ([#238](https://github.com/Montreal-2027/montreal2027.ca/issues/238))
- Changed page background ([#252](https://github.com/Montreal-2027/montreal2027.ca/issues/252))
- In Memoriam visibility changes made in prod
- Registration page money cell alignment and padding ([#266](https://github.com/Montreal-2027/montreal2027.ca/issues/266))

## 1.1.2 (2026-11-05 Hotfix)
- Added access hook for In Memoriam role/content type to allow viewing and publishing of unpublished content regardless of owner
  
## 1.1.1 (2026-11-04)
- Improved error message when sending Committee & Team emails with Contact Form
- Completed translation on Contact Form
- In Memoriam
  - Submission form
  - Spreadsheet import
  - Listing page w/filtering
- Added ckeditor5 plugin to simplify using `<details>` and `<summary>` in body copy
- Updated styling for `<details>` and `<summary>`
- Team Login
  - Added URL field to theme image custom block and wrap image in the url if present
  - Added hook to prevent access to registration page while still allowing users using 
    Google social auth to register
  - Installed Private Content and Menu Item Role Access modules to restrict access to
    team only content and menus
- Added new content type for pages that need a carousel
- Made hero image optional for carousel slides
- Added translucent background color for slides without a hero image

## 1.1.0 (2026-10-17)
- Upgraded to Drupal 11.4.8
- Removed unused modules
- Added Google authentication for Montreal 2027 team members
- Revised CSS for news carousel

## 1.0.11 (2026-10-07)
- Updated sync script to use nfp hosting for remote sync
- Added menu_link_attributes module and updated configuration for registration links
- Refactored grid layout for new membership rate table structure

## 1.0.10 (2026-09-23)
- Updated CSS for article content type
- Adjusted link color and header styles for guest content type
- Installed Webform
- Team Scholarship Questionnaire
- Program Participant Form
- Updated composer.json to include composer.libraries.json from the Webform module
- Updated global Webform settings
- Added a quick jump menu to the team listing page
- Added styling/css for Webform review page
- Updated Team list maintainer role
- Changed `Staff` role display name to `Team`
- Changed `Staff List Maintainer` role display name to `Team List Maintainer`
- Updated deploy workflow
- Updated sync script

## 1.0.9 (2026-09-09)

- Updated deploy scripts to better support new hosting provider
- Updated language detection settings
- Changed "Staff" to "Team" in staff page configuration
- Changed the url routing from /staff to /team
- Installed redirect module and added permanent redirect from /staff to /team
- Added local sync to sync.sh to enable an additional instance of the same server to sync assets and database with the production site
- Documented new environment settings in .env.example and README.md 

## 1.0.8 (2026-07-24)
 
 - Updates to AI Agent instructions
 - Documentation updates
 - Script updates
 - Added Staff List Maintainer role
 - Added favicon
 - Fixed membership rate table display on mobile

## 1.0.7 (2026-06-20)

 - Add Translator role
 - Enabled translation of staff page configuration
 - Added text for contact link on the staff page to the staff page configuration
 - Added language switcher

---

## 1.0.6 (2026-06-19)

- News carousel for landing page
- Sync script enhancements
- Install script for Docker
- Staff Page style enhancements
- Updated README.md
- Use dotenv for configuration
- Removed focal point crop
  
---

## 1.0.5 (2026-05-23) HOTFIX

 - New masthead block & reorganized block weights in header region
 - Disabled outdated image blocks
 - Theme styling updates

---

## 1.0.4 (2026-04-24)

- Docker setup
- Added taxonomy vocabulary for staff listing
- Created view and view template to show staff listing
- Added modal popup to send email to staff member

---

## 1.0.3 (2026-04-22)

- Updated guest node template to use explicit fields to better control markup
- Refactored grid layout for guest article content
- Refactored guest article SCSS and adjusted image properties

---

## 1.0.2 (2026-03-12)

- Updated issue templates

---

## 1.0.1 (2026-03-11)

- Applied style improvements across the site
- Fixed spacing of buttons on search results page
- Updated deploy script
- Configured search using SearchAPI
- Styled new search bar in footer
- Styled search results page

---

## 1.0.0 (2026-01-21)

**Major Features:**
- Upgraded to Drupal 11.2.10
- Installed ECA and related modules to enable automatic updates of membership rates
- Added custom module for ability to set a link to open in a new tab

**Styling & Design:**
- Converted rgba colors to oklch where applicable
- Updated font sizes, line heights with clamp() for better responsiveness
- Added site-wide color for links
- Button styling improvements
- Typography changes and adjustments
- Margin, padding and typography adjustments
- Moved social media icons into navigation
- Removed breadcrumb display
- Added hover states for interactive elements
- Adjusted styling for basic pages

**Content & Images:**
- Added and updated image styles
- Added hero image field to home page content type
- Renamed lefleur block template to a more generic theme-image template
- Added content blocks for the canadasaurus images
- Removed 1:1 image constraint from guest images on default page

**Hotels Page:**
- CSS for hotels page implementation
- Updated deploy configuration
- New user roles

**Deployment & Infrastructure:**
- Updated workflow to deploy to dev when a feature branch is pushed
- Updated deploy configuration
