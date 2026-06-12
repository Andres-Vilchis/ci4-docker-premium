#!/bin/bash

set -e

echo "====================================="
echo " CI4 PRODUCTION DEPLOY ENGINE"
echo "====================================="

VERSION=$1

if [ -z "$VERSION" ]; then
  echo "ERROR: Version required (v1.0.0)"
  exit 1
fi

IMAGE="ci4-starter:${VERSION}"

echo "Deploying: $IMAGE"

# -------------------------
# PULL IMAGE
# -------------------------
docker pull "$IMAGE" || {
  echo "ERROR: image not found"
  exit 1
}

# -------------------------
# PREVIOUS STATE
# -------------------------
OLD_CONTAINER=$(docker compose -f docker-compose.prod.yml ps -q php || true)

# -------------------------
# DEPLOY NEW STACK
# -------------------------
DOCKER_IMAGE="$IMAGE" docker compose -f docker-compose.prod.yml up -d

sleep 5

# -------------------------
# HEALTHCHECK (STRICT)
# -------------------------
STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health)

if [ "$STATUS" != "200" ]; then
  echo "DEPLOY FAILED - rolling back"

  DOCKER_IMAGE="$IMAGE" docker compose -f docker-compose.prod.yml down

  if [ ! -z "$OLD_CONTAINER" ]; then
    docker start "$OLD_CONTAINER" || true
  fi

  exit 1
fi

echo "DEPLOY SUCCESSFUL"