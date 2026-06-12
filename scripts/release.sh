#!/bin/bash

set -e

VERSION=$1

if [ -z "$VERSION" ]; then
  echo "Usage: ./release.sh v1.0.0"
  exit 1
fi

# -------------------------
# VALIDATE SEMVER
# -------------------------
if [[ ! "$VERSION" =~ ^v[0-9]+\.[0-9]+\.[0-9]+$ ]]; then
  echo "Invalid semantic version"
  exit 1
fi

# -------------------------
# MUST BE CLEAN
# -------------------------
git diff --quiet || { echo "Uncommitted changes"; exit 1; }

# -------------------------
# MUST BE MAIN
# -------------------------
CURRENT_BRANCH=$(git branch --show-current)

if [ "$CURRENT_BRANCH" != "main" ]; then
  echo "Must be on main branch"
  exit 1
fi

# -------------------------
# RUN QUALITY GATES
# -------------------------
docker compose exec php vendor/bin/phpunit || exit 1
docker compose exec php vendor/bin/phpstan analyse || exit 1

# -------------------------
# TAG + PUSH
# -------------------------
git tag "$VERSION"
git push origin "$VERSION"

echo "Release $VERSION created"