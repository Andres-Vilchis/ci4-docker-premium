#!/bin/bash

set -e

echo "====================================="
echo " CI4 PRODUCTION DEPLOY ENGINE"
echo "====================================="

# -------------------------
# VALIDATION
# -------------------------
if [ -z "$1" ]; then
  echo "ERROR: You must provide image version (e.g. v1.0.0)"
  exit 1
fi

VERSION=$1
IMAGE="ci4-starter:${VERSION}"

echo "Deploying version: $VERSION"

# -------------------------
# LOAD CURRENT STATE
# -------------------------
CURRENT=$(docker compose -f docker-compose.prod.yml ps -q php || true)

echo "Current container: $CURRENT"

# -------------------------
# PULL NEW IMAGE
# -------------------------
export DOCKER_IMAGE=$IMAGE

echo "Pulling image: $IMAGE"

docker pull $IMAGE || {
  echo "ERROR: Image not found"
  exit 1
}

# -------------------------
# DEPLOY NEW VERSION
# -------------------------
echo "Starting new container..."

docker compose -f docker-compose.prod.yml up -d

# -------------------------
# HEALTHCHECK
# -------------------------
echo "Running healthcheck..."

sleep 5

if curl -s http://localhost/health | grep -q "ok"; then
  echo "Healthcheck passed"
else
  echo "Healthcheck failed - rolling back"

  docker compose -f docker-compose.prod.yml down

  if [ ! -z "$CURRENT" ]; then
    echo "Restoring previous container..."
    docker start $CURRENT || true
  fi

  exit 1
fi

echo "====================================="
echo " DEPLOY SUCCESSFUL"
echo "====================================="