#!/bin/bash

set -e

echo "Bootstrapping infrastructure..."

docker compose down -v

docker volume prune -f

docker network prune -f

echo "🧹 Clean environment ready"