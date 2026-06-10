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

echo "Releasing $VERSION..."

# -------------------------
# CHECK CLEAN STATE
# -------------------------
git diff --quiet || {
  echo "Uncommitted changes detected"
  exit 1
}

# -------------------------
# TAG + PUSH
# -------------------------
git tag $VERSION
git push origin $VERSION

echo "Release $VERSION created successfully"