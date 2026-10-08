# Montreal 2027 WorldCon Website

## About

This website serves as the central hub for the official website for the 85th World Science Fiction Convention (Worldcon), taking place in Montreal, Quebec, Canada in 2027., providing:

- Event information and schedules
- Guest of Honor biographies and photos
- Staff directory with contact functionality
- Hotel and venue information
- Membership registration and management
- News and updates for attendees

The site is built on Drupal 11 with a custom theme and includes specialized modules for convention management, including a staff contact form.

## Technology Stack

- **CMS**: Drupal 11.2+
- **PHP**: 8.3+
- **Database**: MariaDB 11.4
- **Web Server**: Nginx with PHP-FPM
- **Frontend**: Custom Sass/CSS, vanilla JavaScript
- **Search**: SearchAPI
- **Email**: PHPMailer with SMTP

## Getting Started

You can run this project either with Docker or in a native local environment (macOS/Linux with PHP, MariaDB, and Nginx installed locally).

### Quick Start

**Prerequisites:**
- `git` installed on your machine.
- SSH access configured for the Git repository.
- Docker and Docker Compose (if you plan to start the containers and sync the database automatically).
- Your ssh key added to the production server.

The easiest way to get the project up and running using docker is to clone the repository and run the setup script.

```bash
git clone git@git.montreal2027.ca:Montreal-2027/montreal2027.ca.git [DESTINATION_DIR]
cd montreal2027.ca | DESTINATION_DIR
git checkout [BRANCH]
./scripts/install.sh
```

The setup script will prompt you to automatically start the Docker containers and synchronize the database and configuration from the production environment after configuring the graphify git worktree.

*(Note: If you run this script immediately after cloning, it will use the default Docker environment variables because an `.env` file does not yet exist. In most Docker development scenarios, these defaults are perfectly fine. If you need to customize your environment before starting, please see the **Environment configuration** section below on using `.env.example`.)*

## A note about git commits

After running `composer install`, git will be configured to use the scripts in the `.githooks/` directory.  Currently, there is a pre-commit hook that checks that the running Drupal config matches the config on disk.  If it does not, the commit is aborted.  In the rare case that you need to bypass this check, add the `--no-verify` option to your `git commit` command. If you are using VS Code, you can enable the `Allow No Verify Commit` option. To use the `No Verify` for a commit, click on the `...` next to either the repository or `Changes` right below `Repositories`. Expand the `Commit` menu, and select one of the `(No Verify)` options. There will be a pop-up warning that you are about to bypass the pre-commit hook and asks you to confirm the action.

## Git Hooks

Git hooks are configured automatically via Composer scripts and manual setup. The hooks are stored in the `.githooks/` directory and are configured using `git config core.hooksPath .githooks`.

### Configuration
- Automatically configured via Composer post-install and post-update scripts
- Manual setup: `git config core.hooksPath .githooks`

### Pre-Commit Validation
Automatic check to ensure Drupal config is exported before commits:
- **Script:** [.githooks/pre-commit](.githooks/pre-commit)
- **Functionality:** Checks that Drupal configuration is in sync before allowing commits
- **Environment Detection:** Automatically detects Docker or local Drush environments
- **Bypass (emergency only):** `git commit --no-verify`

The hook blocks commits if config changes haven't been exported via `drush cex`.

### Post-Merge Hook
Automatic synchronization of AI architecture map:
- **Script:** [.githooks/post-merge](.githooks/post-merge)
- **Functionality:** Syncs the graphify worktree after git merge operations
- **Graphify Integration:** Automatically pulls updates from the `graph-data` branch

### Merge Driver Configuration
- The `merge.ours` driver is enabled via `git config merge.ours.driver true`
- This driver can be used in `.gitattributes` to always prefer local changes during merges
- Manual setup: `git config merge.ours.driver true`

## Getting Started with Docker

#### Prerequisites

- Docker Desktop (or Docker Engine + Docker Compose) - see alternatives below
- Git

### Open Source Container Runtime Alternatives

While Docker Desktop is a convenient option, there are several open source alternatives available:

#### Linux

**Docker Engine (Open Source)**
- The original open source Docker runtime
- Install via your package manager or from docker.io
- Fully compatible with docker-compose
- **Installation**: 
  ```bash
  # Ubuntu/Debian
  sudo apt-get update
  sudo apt-get install docker.io docker-compose
  
  # Fedora/RHEL
  sudo dnf install docker docker-compose
  ```

**Podman**
- Daemonless container engine
- Drop-in replacement for Docker (alias docker=podman)
- Rootless containers by default
- **Installation**:
  ```bash
  # Ubuntu/Debian
  sudo apt-get install podman podman-compose
  
  # Fedora/RHEL
  sudo dnf install podman podman-compose
  ```
- **Usage**: Replace `docker` with `podman` and `docker-compose` with `podman-compose` in commands

#### macOS

**Colima**
- Lightweight container runtime with minimal resource usage
- Uses Lima VM underneath
- Compatible with docker-compose
- **Installation**:
  ```bash
  brew install colima docker docker-compose
  colima start
  ```

**Podman**
- Same benefits as Linux version
- Requires Podman Desktop or podman machine
- **Installation**:
  ```bash
  brew install podman podman-compose
  podman machine init
  podman machine start
  ```

**Rancher Desktop**
- Open source Kubernetes and container management
- Supports both dockerd and containerd
- GUI application with built-in docker-compose
- **Installation**: Download from [rancherdesktop.io](https://rancherdesktop.io)

#### Windows

**Podman**
- Windows native via WSL2
- **Installation**:
  ```powershell
  winget install RedHat.Podman
  podman machine init
  podman machine start
  ```

**Rancher Desktop**
- Same as macOS version
- Works with WSL2
- **Installation**: Download from [rancherdesktop.io](https://rancherdesktop.io)

**Docker Engine on WSL2** (Open Source)
- Install Docker Engine directly in WSL2 distribution
- Completely free and open source
- **Installation**: Follow Docker Engine installation for your WSL2 distro (Ubuntu/Debian)

> **Note**: When using alternatives, you may need to replace `docker` with `podman` or configure aliases. Most commands in this README work with any OCI-compliant container runtime.

### Initial Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Montreal-2027/montreal2027.ca.git [DESTINATION_DIR]
   cd montreal2027.ca | DESTINATION_DIR
   ```

2. **Environment configuration (optional)**
   
   The `docker-compose.yml` file includes sensible defaults for local development. A `.env` file is **only needed if you want to override these defaults**.
   
   **Default values:**
   - Web accessible at: `http://localhost:8080`
   - Database name/user/password: `drupal` / `drupal` / `drupal`
   - Container names: `montreal2027-web`, `montreal2027-db`
   - PHP version: 8.3
   - MariaDB version: 11.4
   
   **To customize**, create a `.env` file with any of these variables:
   ```bash
   # Environment type (determines which commands to use)
   APP_ENV_TYPE=docker  # or 'native' for local development
   OS_TYPE=macos        # or 'linux' (used in native mode only)

   # Used to map the project to the container
   HOST_PROJECT_ROOT=/path/to/your/project/root # Default is ./ which should work in most situations

   # Web server ports (default: 8080 -> 80)
   WEB_EXTERNAL_PORT=8080
   WEB_INTERNAL_PORT=80

   # Database connectivity (defaults: drupal/drupal/drupal)
   DB_HOST=db
   DB_PORT=3306
   DB_NAME=drupal
   DB_USER=drupal
   DB_PASSWORD=drupal
   DB_ROOT_PASSWORD=root

   # Container and volume names
   WEB_CONTAINER_NAME=montreal2027-web
   DB_CONTAINER_NAME=montreal2027-db
   DB_VOLUME_NAME=montreal2027_db_data
   FRONTEND_NETWORK_NAME=montreal2027_frontend
   BACKEND_NETWORK_NAME=montreal2027_backend
   
   # PHP/MariaDB versions
   PHP_VERSION=8.3
   MARIADB_VERSION=11.4

   # Hash Salt (change for production!)
   HASH_SALT=RandomHashSaltForDevelopmentPurposesOnly

   # Production sync settings (for scripts/sync.sh)
   # Set to true when production is available locally; no SSH is used.
   SYNC_LOCAL=false

   # Local production checkout used when SYNC_LOCAL=true. 
   # It is unlikely that you will need these, so don't set them
   SYNC_PROD_ROOT=/home/cansmof/prod.montreal2027.ca
   SYNC_HOST=montreal2027.ca
   SYNC_USER=www-data
   SYNC_REMOTE_ROOT=/var/www/montreal2027.ca/
   SYNC_SSH_KEY=~/.ssh/montreal2027
   SYNC_SSH_PORT=345
   SYNC_BACKUP_DIR=../backups
   ```

3. **Build and start containers**
   ```bash
   docker-compose up -d --build
   ```

   This will:
   - Build the web container with PHP 8.3, Nginx, and all required extensions
   - Start a MariaDB 11.4 database container
   - Create persistent volumes for the database
   - Set up isolated networks for frontend and backend communication

4. **Install Drupal dependencies**
   ```bash
   docker-compose exec web composer install
   ```

5. **Setup database**

   **Option A: Sync from production** (recommended for team members)
   
   If you have access to the production server, use the sync script:
   ```bash
   # Configure sync settings in .env (see step 2)
   # Ensure SYNC_SSH_KEY points to your SSH key with production access
   
   ./scripts/sync.sh
   ```
   
   This will:
   - Download files from production (web/assets/)
   - Back up your local database (if it exists)
   - Download and import the production database
   - Clear all Drupal caches
   
   **Option B: Manual database import**
   
   If you have a database dump file:
   ```bash
   docker-compose exec -T web bash -c "$(drush sql:connect)" < your-database-dump.sql
   docker-compose exec web drush cr
   ```

   **Option C: Fresh install**
   
   For a clean installation from configuration:
   ```bash
   docker-compose exec web drush site:install --existing-config
   docker-compose exec web drush cr
   ```

6. **Access the site**
   
   Open your browser to: `http://localhost:8080`

### Common Docker Commands

```bash
# View logs
docker logs -f montreal2027-web
docker logs -f montreal2027-db

# Stop containers
docker-compose stop

# Start containers
docker-compose start

# Restart containers
docker-compose restart

# Stop and remove containers (preserves database volume)
docker-compose down

# Stop and remove everything including volumes
docker-compose down -v

# Access web container shell
docker exec -it montreal2027-web sh

# Run Drush commands
docker exec -it montreal2027-web drush [command]

# Access database
docker exec -it montreal2027-web drush sqlc

# Clear cache
docker exec -it montreal2027-web drush cr

# Rebuild containers after Dockerfile changes
docker-compose up -d --build
```

### Development Workflow

1. **CSS Development**
   
   The theme uses Sass. For local and Docker development:
   ```bash
   cd web/themes/custom/montreal2027
   npm install
   npm run watch:css
   ```

2. **Syncing Production Data**
   
   To sync the latest files and database from production during development:
   
   ```bash
   ./scripts/sync.sh
   ```
   
   The script automatically detects your environment (Docker or native) based on the `APP_ENV_TYPE` setting in `.env` and uses the appropriate commands.
   
   **What it does:**
   - Syncs production files (assets) to your local environment
   - Creates a timestamped backup of your local database
   - Downloads the production database
   - Imports the production database locally
   - Clears all caches
   
   **Configuration:** Set these variables in your `.env` file:
   - `SYNC_HOST` - Production server hostname
   - `SYNC_USER` - SSH username for production
   - `SYNC_SSH_KEY` - Path to your SSH private key
   - `SYNC_SSH_PORT` - SSH port (default: 345)
   - `SYNC_REMOTE_ROOT` - Production site root path
   - `SYNC_BACKUP_DIR` - Local directory for database backups (default: ../backups)

3. **Configuration Management**
   
   Export configuration changes:
   ```bash
   [docker exec -it montreal2027-web ]drush config:export
   ```

   Import configuration:
   ```bash
   [docker exec -it montreal2027-web ]drush config:import
   ```

4. **Database Backups**
   ```bash
   [docker exec -it montreal2027-web ]drush sql:dump --gzip > backup-$(date +%Y-%m-%d_%H:%M:%S).sql.gz
   ```

### Docker Troubleshooting

**Port conflicts**: If port 8080 is already in use, change `WEB_EXTERNAL_PORT` in `.env`

**Permission issues**: The web container runs `nginx` as the `nginx` user. If you encounter permission issues:
```bash
docker exec -it montreal2027-web chown -R nginx:nginx /var/www/html/web/sites/default/files
```

**Database connection errors**: Ensure the `DB_HOST` in `.env` is set to `db` (the service name)

**Configuration not found**: Import the module configurations:
```bash
docker exec -it montreal2027-web drush config:import --partial --source=modules/custom/montreal2027_tools/config/install
```

**Clear all caches**:
```bash
docker exec -it montreal2027-web drush cr
```

## Getting Started with Native/Local Development

### Prerequisites

- PHP 8.3+
- MariaDB 11.4 or MySQL 8.0+
- Composer
- Drush (installed via Composer)
- Node.js and npm (for theme compilation)
- Web server (Nginx or Apache)
- Git

*(Note on Drush: We recommend creating an alias for local Native development to automatically point to the current project's Drush executable: `alias drush='$(composer config vendor-dir)/bin/drush'`)*

### Initial Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/Montreal-2027/montreal2027.ca.git
   cd montreal2027.ca
   ```

2. **Create .env file**
   
   Copy `.env.example` to `.env` and configure for native development:
   ```bash
   cp .env.example .env
   ```
   
   Edit `.env` and set:
   ```bash
   APP_ENV_TYPE=native
   OS_TYPE=macos  # or 'linux'
   
   # Database settings (match your local database)
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_NAME=montreal2027
   DB_USER=your_db_user
   DB_PASSWORD=your_db_password
   
   # Sync settings (if you have production access)
   SYNC_SSH_KEY=~/.ssh/montreal2027
   # ... other sync settings
   ```

3. **Install PHP dependencies**
   ```bash
   composer install
   ```

4. **Setup database**
   
   **Option A: Sync from production** (recommended for team members)
   ```bash
   # Ensure sync settings are configured in .env
   ./scripts/sync.sh
   ```
   
   **Option B: Fresh install**
   ```bash
   drush site:install --existing-config
   drush cr
   ```

5. **Install theme dependencies and build CSS**
   ```bash
   cd web/themes/custom/montreal2027
   npm install
   npm run build:css
   cd ../../..
   ```

6. **Configure your web server**
   
   Point your web server document root to the `web/` directory.
   
   Example Nginx configuration:
   ```nginx
   server {
       listen 8080;
       server_name localhost;
       root /path/to/montreal2027.ca/web;
       
       index index.php;
       
       location / {
           try_files $uri /index.php?$query_string;
       }
       
       location ~ \.php$ {
           fastcgi_pass unix:/var/run/php-fpm.sock;
           fastcgi_index index.php;
           fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
           include fastcgi_params;
       }
   }
   ```

   Example .htaccess if you're using Apache
   ```
   #
# Apache/PHP/Drupal settings:
#

# Protect files and directories from prying eyes.
<FilesMatch "\.(engine|inc|install|make|module|profile|po|sh|.*sql|theme|twig|tpl(\.php)?|xtmpl|yml)(~|\.sw[op]|\.bak|\.orig|\.save)?$|^(\.(?!well-known).*|Entries.*|Repository|Root|Tag|Template|composer\.(json|lock)|web\.config|yarn\.lock|package\.json)$|^#.*#$|\.php(~|\.sw[op]|\.bak|\.orig|\.save)$">
  <IfModule mod_authz_core.c>
    Require all denied
  </IfModule>
  <IfModule !mod_authz_core.c>
    Order allow,deny
  </IfModule>
</FilesMatch>

# Don't show directory listings for URLs which map to a directory.
Options -Indexes

# Set the default handler.
DirectoryIndex index.php index.html index.htm

# Add correct encoding for SVGZ.
AddType image/svg+xml svg svgz
AddEncoding gzip svgz

# Add correct encoding for webp.
AddType image/webp .webp

# Most of the following PHP settings cannot be changed at runtime. See
# sites/default/default.settings.php and
# Drupal\Core\DrupalKernel::bootEnvironment() for settings that can be
# changed at runtime.
<IfModule mod_php.c>
  php_value assert.active                   0
</IfModule>

# Requires mod_expires to be enabled.
<IfModule mod_expires.c>
  # Enable expirations.
  ExpiresActive On

  # Cache all files for 1 year after access.
  ExpiresDefault "access plus 1 year"

  <FilesMatch \.php$>
    # Do not allow PHP scripts to be cached unless they explicitly send cache
    # headers themselves. Otherwise all scripts would have to overwrite the
    # headers set by mod_expires if they want another caching behavior. This may
    # fail if an error occurs early in the bootstrap process, and it may cause
    # problems if a non-Drupal PHP file is installed in a subdirectory.
    ExpiresActive Off
  </FilesMatch>
</IfModule>

# Set a fallback resource if mod_rewrite is not enabled. This allows Drupal to
# work without clean URLs. This requires Apache version >= 2.2.16. If Drupal is
# not accessed by the top level URL (i.e.: http://example.com/drupal/ instead of
# http://example.com/), the path to index.php will need to be adjusted.
<IfModule !mod_rewrite.c>
  FallbackResource /index.php
</IfModule>

# Various rewrite rules.
<IfModule mod_rewrite.c>
  RewriteEngine on

  # Set "protossl" to "s" if we were accessed via https://.  This is used later
  # if you enable "www." stripping or enforcement, in order to ensure that
  # you don't bounce between http and https.
  RewriteRule ^ - [E=protossl]
  RewriteCond %{HTTPS} on
  RewriteRule ^ - [E=protossl:s]

  # Make sure Authorization HTTP header is available to PHP
  # even when running as CGI or FastCGI.
  RewriteRule ^ - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]

  # Block access to "hidden" directories whose names begin with a period. This
  # includes directories used by version control systems such as Subversion or
  # Git to store control files. Files whose names begin with a period, as well
  # as the control files used by CVS, are protected by the FilesMatch directive
  # above.
  #
  # NOTE: This only works when mod_rewrite is loaded. Without mod_rewrite, it is
  # not possible to block access to entire directories from .htaccess because
  # <DirectoryMatch> is not allowed here.
  #
  # If you do not have mod_rewrite installed, you should remove these
  # directories from your webroot or otherwise protect them from being
  # downloaded.
  RewriteRule "/\.|^\.(?!well-known/)" - [F]

  # If your site can be accessed both with and without the 'www.' prefix, you
  # can use one of the following settings to redirect users to your preferred
  # URL, either WITH or WITHOUT the 'www.' prefix. Choose ONLY one option:
  #
  # To redirect all users to access the site WITH the 'www.' prefix,
  # (http://example.com/foo will be redirected to http://www.example.com/foo)
  # uncomment the following:
  # RewriteCond %{HTTP_HOST} .
  # RewriteCond %{HTTP_HOST} !^www\. [NC]
  # RewriteRule ^ http%{ENV:protossl}://www.%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
  #
  # To redirect all users to access the site WITHOUT the 'www.' prefix,
  # (http://www.example.com/foo will be redirected to http://example.com/foo)
  # uncomment the following:
  # RewriteCond %{HTTP_HOST} ^www\.(.+)$ [NC]
  # RewriteRule ^ http%{ENV:protossl}://%1%{REQUEST_URI} [L,R=301]

  # Modify the RewriteBase if you are using Drupal in a subdirectory or in a
  # VirtualDocumentRoot and the rewrite rules are not working properly.
  # For example if your site is at http://example.com/drupal uncomment and
  # modify the following line:
  # RewriteBase /drupal
  #
  # If your site is running in a VirtualDocumentRoot at http://example.com/,
  # uncomment the following line:
  # RewriteBase /

  # Redirect common PHP files to their new locations.
  RewriteCond %{REQUEST_URI} ^(.*)?/(install\.php) [OR]
  RewriteCond %{REQUEST_URI} ^(.*)?/(rebuild\.php)
  RewriteCond %{REQUEST_URI} !core
  RewriteRule ^ %1/core/%2 [L,QSA,R=301]

  # Rewrite install.php during installation to see if mod_rewrite is working
  RewriteRule ^core/install\.php core/install.php?rewrite=ok [QSA,L]

  # Pass all requests not referring directly to files in the filesystem to
  # index.php.
  RewriteCond %{REQUEST_FILENAME} !-f
  RewriteCond %{REQUEST_FILENAME} !-d
  RewriteCond %{REQUEST_URI} !=/favicon.ico
  RewriteRule ^ index.php [L]

  # For security reasons, deny access to other PHP files on public sites.
  # Note: The following URI conditions are not anchored at the start (^),
  # because Drupal may be located in a subdirectory. To further improve
  # security, you can replace '!/' with '!^/'.
  # Allow access to PHP files in /core (like authorize.php or install.php):
  RewriteCond %{REQUEST_URI} !/core/[^/]*\.php$
  # Allow access to test-specific PHP files:
  RewriteCond %{REQUEST_URI} !/core/modules/system/tests/https?\.php
  # Deny access to any other PHP files that do not match the rules above.
  # Specifically, disallow autoload.php from being served directly.
  RewriteRule "^(.+/.*|autoload)\.php($|/)" - [F]

  # Rules to correctly serve gzip compressed CSS and JS files.
  # Requires both mod_rewrite and mod_headers to be enabled.
  <IfModule mod_headers.c>
    # Serve gzip compressed CSS files if they exist and the client accepts gzip.
    RewriteCond %{HTTP:Accept-encoding} gzip
    RewriteCond %{REQUEST_FILENAME}\.gz -s
    RewriteRule ^(.*css_[a-zA-Z0-9-_]+)\.css$ $1\.css\.gz [QSA]

    # Serve gzip compressed JS files if they exist and the client accepts gzip.
    RewriteCond %{HTTP:Accept-encoding} gzip
    RewriteCond %{REQUEST_FILENAME}\.gz -s
    RewriteRule ^(.*js_[a-zA-Z0-9-_]+)\.js$ $1\.js\.gz [QSA]

    # Serve correct content types, and prevent double compression.
    RewriteRule \.css\.gz$ - [T=text/css,E=no-gzip:1,E=no-brotli:1]
    RewriteRule \.js\.gz$ - [T=text/javascript,E=no-gzip:1,E=no-brotli:1]

    <FilesMatch "(\.js\.gz|\.css\.gz)$">
      # Serve correct encoding type.
      Header set Content-Encoding gzip
      # Force proxies to cache gzipped & non-gzipped css/js files separately.
      Header append Vary Accept-Encoding
    </FilesMatch>
  </IfModule>
</IfModule>

# Various header fixes.
<IfModule mod_headers.c>
  # Disable content sniffing for all responses, since it's an attack vector.
  # This header is also set in FinishResponseSubscriber, which depending on
  # Apache configuration might get placed in the 'onsuccess' table. To prevent
  # header duplication, unset that one prior to setting in the 'always' table.
  # See "To circumvent this limitation..." in
  # https://httpd.apache.org/docs/current/mod/mod_headers.html.
  Header onsuccess unset X-Content-Type-Options
  Header always set X-Content-Type-Options nosniff
  # Disable Proxy header, since it's an attack vector.
  RequestHeader unset Proxy
</IfModule>
```

7. **Access the site**
   
   Open your browser to your configured URL (e.g., `http://localhost:8080`)

### Common Native Development Commands

All commands run without the `docker-compose exec web` prefix:

```bash
# Drush commands
drush cr                  # Clear cache
drush config:export       # Export configuration
drush config:import       # Import configuration
drush uli                 # Generate login link
drush sql:dump > backup.sql  # Database backup

# Sync from production
./scripts/sync.sh

# Theme development
cd web/themes/custom/montreal2027
npm run watch:css  # Watch for changes
npm run build:css  # One-time build
```

## VS Code Development

### Internal Browser (Obscura)

This project uses [Obscura](https://github.com/h4ckf0r0day/obscura) as the browser tool for VS Code Copilot. Obscura is an open-source headless browser built in Rust — lightweight, fast, and cross-platform. It supports the Chrome DevTools Protocol (CDP) and works as a drop-in replacement for headless Chrome with Puppeteer and Playwright.

**Platforms:** Linux (x86_64, ARM64, AUR), macOS (Apple Silicon, Intel), Windows

**Install:**

```bash
# Linux x86_64
curl -LO https://github.com/h4ckf0r0day/obscura/releases/latest/download/obscura-x86_64-linux.tar.gz
tar xzf obscura-x86_64-linux.tar.gz

# Linux ARM64
curl -LO https://github.com/h4ckf0r0day/obscura/releases/latest/download/obscura-aarch64-linux.tar.gz
tar xzf obscura-aarch64-linux.tar.gz

# Arch Linux (AUR)
yay -S obscura-browser

# macOS Apple Silicon
curl -LO https://github.com/h4ckf0r0day/obscura/releases/latest/download/obscura-aarch64-macos.tar.gz
tar xzf obscura-aarch64-macos.tar.gz

# macOS Intel
curl -LO https://github.com/h4ckf0r0day/obscura/releases/latest/download/obscura-x86_64-macos.tar.gz
tar xzf obscura-x86_64-macos.tar.gz

# Windows — download the .zip from the releases page and extract manually
# https://github.com/h4ckf0r0day/obscura/releases/latest
```

**VS Code Copilot MCP integration:**

Obscura ships an MCP server that exposes browser automation tools to Copilot agents. The configuration is pre-configured in [`.vscode/mcp.json`](.vscode/mcp.json) — VS Code Copilot will auto-discover it automatically. No manual setup required.

**VS Code launch configurations (`.vscode/launch.json`):**

Three Obscura-related launch configurations are pre-configured:

| Configuration | What it does |
|---|---|
| **Start Obscura MCP Server** | Launches `obscura mcp` (stdio) for Copilot agent integration |
| **Start Obscura CDP Server** | Launches `obscura serve --port 9222` for Chrome DevTools Protocol |
| **Attach Code to Running Obscura Engine** | Attaches VS Code to an already-running Obscura CDP server on port 9222 |

A compound **"Obscura: Start & Attach"** is also available in the Run & Debug panel — it starts the CDP server and immediately attaches VS Code in one step.

To use with Copilot, start the MCP server first via the **"Start Obscura MCP Server"** configuration in the Run & Debug panel (`Cmd+Shift+D`), then browser tools will be available to Copilot agents.

For more details see the [Obscura GitHub repository](https://github.com/h4ckf0r0day/obscura) and [documentation](https://docs.obscura.sh/).

## Project Structure

```
.
├── .githooks/                   # Git pre-commit and post-merge hook scripts
├── .github/                     # Github configurations
│   └── ISSUE_TEMPLATE/          # Template(s) for opening an issue on Github
│   └── workflows/               # Github Actions workflows
├── config/sync/                 # Drupal configuration
├── docker/                      # Docker configuration files
│   └── web/                     # Dockerfile and web container configs (Nginx, PHP-FPM, etc.)
├── patches/                     # Local patches to be applied by composer
├── scripts/                     # Build and deployment scripts
├── web/                         # Drupal web root
│   ├── modules/custom/          # Custom modules
│   │   └── montreal2027_tools/  # Staff contact & utilities
│   ├── themes/custom/           # Custom themes
│   │   └── montreal2027/        # Main site theme
│   └── sites/default/           # Site-specific settings
├── .env                         # Environment variables (not in git)
├── composer.json                # PHP dependencies
└── docker-compose.yml           # Docker service definitions
```

## Scripts

### sync.sh

Location: `scripts/sync.sh`

Synchronizes files and database from the production server to your local development environment.

**Features:**
- Auto-detects environment (Docker or native) based on `APP_ENV_TYPE` in `.env`
- Downloads production assets (web/assets/)
- Creates timestamped backups of local database before importing
- Downloads and imports production database
- Clears all Drupal caches automatically

**Usage:**
```bash
./scripts/sync.sh
```

**Configuration:** Set these variables in `.env`:
- `SYNC_HOST` - Production server hostname (default: montreal2027.ca)
- `SYNC_USER` - SSH username (default: www-data)
- `SYNC_REMOTE_ROOT` - Production site root path (default: /var/www/montreal2027.ca/)
- `SYNC_SSH_KEY` - Path to SSH private key (default: ~/.ssh/montreal2027)
- `SYNC_SSH_PORT` - SSH port (default: 345)
- `SYNC_BACKUP_DIR` - Local backup directory (default: ../backups)

**Requirements:**
- SSH access to production server
- SSH key configured for authentication
- `rsync` installed locally

## Common Pitfalls and Solutions

- **Don't edit compiled CSS:** Always edit `src/scss/*.scss`, not `css/*.css`
- **Config sync required:** After entity/field changes, run `drush cex` before committing (pre-commit hook enforces this)
- **File permissions:** If permission errors occur, run `chmod -R u+w web/sites/default`
- **Environment detection:** Pre-commit hook auto-detects Docker vs local - ensure containers are running if using Docker
- **Git hooks not working:** If githooks aren't functioning, run `git config core.hooksPath .githooks` manually
- **Graphify worktree issues:** If graphify isn't updating, ensure the worktree is properly set up via `scripts/setup-graphify.sh`
- **Port conflicts (Docker):** Default is `8080:80` - change `WEB_EXTERNAL_PORT` in `.env` if needed
- **Node modules in deployment:** Deployment workflow removes `node_modules` after CSS build to reduce footprint

## Custom Modules

### montreal2027_tools

Provides utilities for convention management:
- **Staff Directory**: Hierarchical taxonomy-based staff listing with contact forms
- **Contact Import**: Drush command for importing contacts from CSV
- **AJAX Contact Forms**: Modal contact forms with email validation and spam protection

## Contributing

1. Create a feature branch from `develop`. Naming convention is `feature/[short_token]`
2. Make your changes
3. Test thoroughly in your development environment (Docker or native)
4. Commit with descriptive messages
5. Push to your branch. This will auto-deploy to https://dev.montreal2027.ca. Test thoroughly
6. Create a pull request for your feature branch against `develop`. Assign the PR to yourself. Add meaningful labels. Add the PR to the `Tech` project, and link it to the issue under `Development`

## License

Copyright © 2025, CanSMOF. All rights reserved.

## Support

For questions or issues, contact the Website Area Head, Technology & Production Division via the [staff directory](https://montreal2027.ca/staff) on the website.
