#!/bin/sh
set -e

INSTALL_FLAG=".install_completed"

if [ -f "$INSTALL_FLAG" ]; then
    echo "This installation script has already been run."
    echo "If you need to sync again, please run ./scripts/sync.sh directly."
    exit 1
fi

echo "Setting up graphify environment..."
sh ./scripts/setup-graphify.sh
echo "Graphify environment setup complete."

echo "Starting containers..."
docker-compose up -d --wait
echo "Containers are up and ready."

echo "Running sync.sh..."
sh ./scripts/sync.sh

echo "Building theme assets in the container..."
docker-compose exec -T web bash -c "cd web/themes/custom/montreal2027 && npm install && npm run build:css"

touch "$INSTALL_FLAG"

echo "Setup complete!"
