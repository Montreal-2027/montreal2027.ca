# Pre-commit Checklist

There are no required test commands at this time. Test the affected behavior in your local development environment and summarize the checks you performed in the pull request. Visual/style changes must be checked in Chrome, Firefox, Safari (if available), and Edge.  At a minimum, check visual and style in a mobile simulator. Testing with mobile devices can be deferred until your branch has been deployed to the development server. If practical, both Android and iOS devices should be tested, using the same browsers as before. Test both portrait and landscape, and on larger tablets.

- [ ] Test the affected behavior in a local development environment.
- [ ] For theme changes, build the Sass bundle and check the result in a browser.
- [ ] For Drupal configuration changes, export and commit the corresponding files from `config/sync/`.
- [ ] Check that generated assets and configurations are intentional and up to date.
