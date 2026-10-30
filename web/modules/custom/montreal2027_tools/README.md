# Montreal 2027 Tools

Custom utility module for Montreal 2027 operations.

## Taxonomy UI Label Overrides

This module customizes the `divisions` taxonomy vocabulary UI labels:

- Term add/edit form field label: `Name` -> `Division Name`
- Terms overview table column header: `Name` -> `Division Name`

Implementation is done with form alter hooks in [web/modules/custom/montreal2027_tools/montreal2027_tools.module](web/modules/custom/montreal2027_tools/montreal2027_tools.module).

## Contact Import Command

This module provides a Drush command that imports contacts from a CSV file and creates Drupal users when they do not already exist by email address.

New users are created with:

- Username = email address (lowercase)
- Email domain restricted to `montreal2027.ca`
- Last Name required

### Command

- `montreal2027:import-contacts`
- Alias: `m2027:ic`

### CSV format

The CSV must include these exact headers:

- `First Name`
- `Last Name`
- `E-mail 1 - Value`

You can override these header names with command options.

### Options

- `--file=/path/to/contacts.csv` (required)
- `--dry-run` (optional): reports actions but does not create users
- `--status=0|1` (optional): account status for newly created users
- `--first-name-column='First Name'` (optional)
- `--last-name-column='Last Name'` (optional)
- `--email-column='E-mail 1 - Value'` (optional)

Status values:
- `0` = blocked (default)
- `1` = active

### Examples

Dry-run preview with default blocked status:

```bash
drush montreal2027:import-contacts --file=/Users/daveweiner/Downloads/contacts.csv --dry-run
```

Live import with default blocked status:

```bash
drush montreal2027:import-contacts --file=/Users/daveweiner/Downloads/contacts.csv
```

Live import creating active users:

```bash
drush montreal2027:import-contacts --file=/Users/daveweiner/Downloads/contacts.csv --status=1
```

Using the alias:

```bash
drush m2027:ic --file=/Users/daveweiner/Downloads/contacts.csv --dry-run
```

Using custom column headers:

```bash
drush m2027:ic \
  --file=/Users/daveweiner/Downloads/contacts.csv \
  --first-name-column='Given Name' \
  --last-name-column='Surname' \
  --email-column='Primary Email' \
  --dry-run
```

### Output behavior

Each row prints a status:

- `EXISTS` when a user with the email already exists
- `WOULD CREATE` in dry-run mode
- `CREATING` followed by `SUCCESS` or `FAILED` in live mode
- `SKIP` for blank email rows

Rows are skipped when:

- Last Name is empty
- Email is empty
- Email is not in the `montreal2027.ca` domain

A summary is printed at the end:

- Existing
- Created (or Would create in dry-run)
- Skipped
- Failed

## In Memoriam spreadsheet import

`montreal2027:import-in-memoriam /path/to/in-memoriam.xlsx` (alias: `m2027:iim`) imports the first worksheet of an XLSX file. The path must be readable from the `web` container when using Docker. The seven required column headers are case-sensitive: `First Name`, `Last Name`, `Year of Birth`, `Date of Death`, `Sort Date`, `Category`, and `Bio`.

The migration validates the destination fields and workbook headers, processes rows in batches of 25, creates missing taxonomy terms, and keeps imported nodes unpublished. It runs entity validation as user 1 so the configured text format is permitted; new nodes are therefore owned by user 1. Exact name matches update changed values; unchanged matches are skipped. Rows may be sorted, inserted or deleted between imports: the name, not the row number, decides which node is updated, and a row's stored mapping is corrected to its matching node. If no node matches a row exactly, but a node has the same first and last name ignoring letter case, the row is skipped with an on-screen warning instead of creating a duplicate, so site edits are kept; fix the spreadsheet name to update that node. If a previously imported row's old node has a different name, the row is treated as a new person and a new node is created without changing the old one. Submission fields (`field_submitted_by` and `field_submitted_email`) are excluded from change detection. Category values are semicolon-delimited and limited to the first four terms. `Date of Death` cells formatted as Excel dates are read as displayed text: `m/yyyy` becomes `YYYY-MM`, and `m/d/yyyy` becomes `YYYY-MM-DD`. Text such as `Summer` is kept. `field_sort_date` always holds a complete `YYYY-MM-DD` date: `Sort Date` is used when present, otherwise `Date of Death`. Either value is normalized with the same rules, and a month-only result uses the month's last day (`10/2026` becomes `2026-10-31`). A descriptive `Date of Death` with no `Sort Date` leaves the field empty. An invalid `Date of Death` (such as `13/2026` or `2/30/2027`) or an invalid or non-date `Sort Date` skips that row with an on-screen warning naming the column; the rest of the file still imports, and the row can be corrected and re-imported. Fully blank rows, such as rows created by formatting an entire column, are skipped.

Import the migration configuration with `drush cim` before its first use.
The YAML file in `config/sync` is not discovered until it is imported into active configuration. If the command reports that the migration is unavailable, review `drush config:status` before importing configuration; `drush cim` may apply other pending changes.

Example:

```bash
docker compose exec web vendor/bin/drush montreal2027:import-in-memoriam /tmp/in-memoriam.xlsx
```
