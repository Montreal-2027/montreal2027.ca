   # Montreal 2027 WorldCon Website

Official website for the 85th World Science Fiction Convention (Worldcon), taking place in Montreal, Quebec, Canada in 2027.

## About

This Drupal 11 website serves as the central hub for Montreal 2027 WorldCon, providing:

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

### Quick Start Script

**Prerequisites:**
- `git` installed on your machine.
- SSH access configured for the Git repository.
- Docker and Docker Compose (if you plan to start the containers and sync the database automatically).

The easiest way to get the project up and running is to clone the repository and run the setup script.

```bash
git clone git@git.montreal2027.ca:Montreal-2027/montreal2027.ca.git [DESTINATION_DIR]
cd {montreal2027.ca | DESTINATION_DIR}
git checkout {BRANCH}
./scripts/install.sh
```

The setup script will prompt you to automatically start the Docker containers and synchronize the database and configuration from the production environment.

*(Note: If you run this script immediately after cloning, it will use the default Docker environment variables because an `.env` file does not yet exist. In most Docker development scenarios, these defaults are perfectly fine. If you need to customize your environment before starting, please see the **Environment configuration** section below on using `.env.example`.)*

### Getting Started with Docker

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
   git clone https://github.com/Montreal-2027/montreal2027.ca.git
   cd montreal2027.ca
   ```

2. **Environment configuration (optional)**
   
   The `docker-compose.yml` file includes sensible defaults for local development. A `.env` file is **only needed if you want to override these defaults**.
   
   **Default values:**
   - Web accessible at: `http://localhost:8080`
   - Database: `drupal` / `drupal` / `drupal`
   - Container names: `montreal2027-web`, `montreal2027-db`
   - PHP version: 8.3
   - MariaDB version: 11.4
   
   **To customize**, create a `.env` file with any of these variables:
   ```bash
   # Environment type (determines which commands to use)
   APP_ENV_TYPE=docker  # or 'native' for local development
   OS_TYPE=macos        # or 'linux' (used in native mode only)

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
docker-compose logs -f web
docker-compose logs -f db

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
docker-compose exec web sh

# Access database (password: drupal)
docker-compose exec db mysql -u drupal -p drupal

# Run Drush commands
docker-compose exec web drush [command]

# Clear cache
docker-compose exec web drush cr

# Rebuild containers after Dockerfile changes
docker-compose up -d --build
```

### Development Workflow

1. **CSS Development**
   
   The theme uses Sass. For local development outside Docker:
   ```bash
   cd web/themes/custom/montreal2027
   npm install
   npm run watch
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
   docker-compose exec web drush config:export
   ```

   Import configuration:
   ```bash
   docker-compose exec web drush config:import
   ```

4. **Database Backups**
   ```bash
   docker-compose exec web drush sql:dump --gzip > backup-$(date +%Y%m%d-%H%M%S).sql.gz
   ```

### Troubleshooting

**Port conflicts**: If port 8080 is already in use, change `WEB_EXTERNAL_PORT` in `.env`

**Permission issues**: The web container runs as the `nginx` user. If you encounter permission issues:
```bash
docker-compose exec web chown -R nginx:nginx /var/www/html/web/sites/default/files
```

**Database connection errors**: Ensure the `DB_HOST` in `.env` is set to `db` (the service name)

**Configuration not found**: Import the module configurations:
```bash
docker-compose exec web drush config:import --partial --source=modules/custom/montreal2027_tools/config/install
```

**Clear all caches**:
```bash
docker-compose exec web drush cr
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

## Project Structure

```
.
├── config/sync/                 # Drupal configuration
├── docker/                      # Docker configuration files
│   └── web/                     # Web container configs (Nginx, PHP-FPM, etc.)
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
6. Create a pull request for your feature branch against `develop`. Assign the PR to yourself. Add meaningful labels. Add the PR to the `Website` project, and link it to the issue under `Development`

## License

Copyright © 2025, CanSMOF. All rights reserved.

## Support

For questions or issues, contact the Website Area Head, Technology & Production Division via the [staff directory](https://montreal2027.ca/staff) on the website.
