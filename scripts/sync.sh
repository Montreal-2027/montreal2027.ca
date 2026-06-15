#!/bin/bash

######################################################################
# 
# Script to synchronize files and databases between the production
# server and the local development environment.
#
# Usage: ./scripts/sync.sh
#
# NOTE: Run this script from the project root directory.
#
# WARNING: This script will DROP the local database before importing
#          the remote database. Ensure you have backups if necessary.
#
######################################################################

# Project root
PROJECT_ROOT="${PWD}"

# Load environment variables from .env file
if [ -f "${PROJECT_ROOT}/.env" ]; then
    set -a
    source "${PROJECT_ROOT}/.env"
    set +a
else
    echo "Warning: .env file not found. Using default values."
fi

# Local directory path to sync with remote server
LOCAL_SYNC_DIR="${PROJECT_ROOT}/web/assets/"

# Hostname or IP to sync from
SYNC_FROM="${SYNC_HOST:-montreal2027.ca}"

# Remote user for SSH
REMOTE_USER="${SYNC_USER:-www-data}"

# Remote root directory
REMOTE_ROOT="${SYNC_REMOTE_ROOT:-/var/www/montreal2027.ca/}"

# SSH key path and port
SSH_KEY="${SYNC_SSH_KEY:-~/.ssh/montreal2027}"
SSH_PORT="${SYNC_SSH_PORT:-345}"

# Local backup directory
BACKUP_DIR="${SYNC_BACKUP_DIR:-../backups}"

# Directory to sync
REMOTE_DIR="${REMOTE_ROOT}/web/assets/"

# Determine drush command based on environment type
APP_ENV_TYPE="${APP_ENV_TYPE:-docker}"
if [ "$APP_ENV_TYPE" = "docker" ]; then
    echo "Using Docker environment for local commands..."

    # Check if the web container is running
    if ! docker-compose ps --services --filter "status=running" | grep -q "^web$"; then
        echo "Docker container 'web' is not running."
        read -p "Do you want to start the containers now? (y/N): " start_containers
        if [[ "$start_containers" =~ ^[Yy]$ ]]; then
            echo "Starting Docker containers..."
            docker-compose up -d
            echo "Waiting for containers to configure..."
            sleep 5
        else
            echo "Cannot proceed without containers running. Exiting."
            exit 1
        fi
    fi

    DRUSH="docker-compose exec -T web ./vendor/bin/drush"
    SQL_CONNECT="docker-compose exec -T web bash -c \"\$(./vendor/bin/drush sql:connect)\""
else
    echo "Using native environment for local commands..."
    DRUSH="./vendor/bin/drush"
    SQL_CONNECT="\$(./vendor/bin/drush sql:connect)"
fi


# Sync files using rsync
echo "Starting file synchronization of assets from ${SYNC_FROM}..."
rsync -avz --delete -e "ssh -i ${SSH_KEY} -p ${SSH_PORT}"  --exclude 'php/*' --progress "${REMOTE_USER}@${SYNC_FROM}:${REMOTE_DIR}" "${LOCAL_SYNC_DIR}"

read -p "Drop local database and import remote database? (y/N): " confirm
if [[ "$confirm" =~ ^[Nn]$ ]]
then
    echo "Database synchronization aborted."
    exit 0
fi

cd "${PROJECT_ROOT}"

echo "Downloading remote database..."
ssh "${REMOTE_USER}@${SYNC_FROM}" -i "${SSH_KEY}" -p "${SSH_PORT}" "(cd ${REMOTE_ROOT} && drush sql:dump)" > dump.sql

# Check if local database is not empty
TABLE_COUNT=$($DRUSH sql:query "SHOW TABLES;" 2>/dev/null | wc -l)

if [ "$TABLE_COUNT" -gt 0 ]; then
    echo "Backing up local database..."
    mkdir -p "${BACKUP_DIR}"
    $DRUSH sql:dump > "${BACKUP_DIR}/m2707-$(date +%Y-%m-%d_%H:%M).sql"

    echo "Exporting changes..."
    $DRUSH cex

    echo "Dropping local database..."
    $DRUSH sql:drop -y
else
    echo "Local database is empty. Skipping backup, config export, and drop."
fi

echo "Importing remote database into local database..."
if [ "$APP_ENV_TYPE" = "docker" ]; then
    docker-compose exec -T web bash -c "$($DRUSH sql:connect) < /var/www/html/dump.sql"
else
    $($DRUSH sql:connect) < dump.sql
fi

echo "Importing any exported changes..."
$DRUSH cim
$DRUSH updb

# Rebuild Drupal cache
$DRUSH cr

# Clean up
echo "Cleaning up temporary files..."
#rm dump.sql

echo "Synchronization complete."

