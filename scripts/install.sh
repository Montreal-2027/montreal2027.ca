#!/bin/sh
set -e

REPO_URL="git@git.montreal2027.ca:Montreal-2027/montreal2027.ca.git"

if [ -n "$1" ]; then
    DEST_DIR="$1"
else
    printf "Directory to clone into [montreal2027.ca]: "
    read -r input_dir < /dev/tty || input_dir=""
    DEST_DIR="${input_dir:-montreal2027.ca}"
fi

if ! command -v git >/dev/null 2>&1; then
    echo "Error: git is not installed or not in your PATH."
    exit 1
fi

echo "Cloning repository..."
git clone "$REPO_URL" "$DEST_DIR" || { echo "Failed to clone repository."; exit 1; }

cd "$DEST_DIR"

printf "Do you want to start the Docker containers now? (y/N): "
# Read from /dev/tty because this script might be piped to sh (e.g. curl | sh)
read -r start_containers < /dev/tty || start_containers="n"

if [ "$start_containers" = "y" ] || [ "$start_containers" = "Y" ]; then
    echo "Starting containers..."
    docker-compose up -d --build
fi

printf "Do you want to run the sync from production now? (y/N): "
read -r run_sync < /dev/tty || run_sync="n"

if [ "$run_sync" = "y" ] || [ "$run_sync" = "Y" ]; then
    echo "Running sync.sh..."
    bash ./scripts/sync.sh
fi

echo "Installation complete!"
