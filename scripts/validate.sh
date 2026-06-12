#!/bin/bash

set -e

echo "=================================="
echo " CI4 VALIDATION ENGINE"
echo "=================================="

# -------------------------
# ENV CHECK
# -------------------------
echo "Checking environment..."

command -v docker >/dev/null 2>&1 || {
  echo "ERROR: Docker not installed"
  exit 1
}

docker compose version >/dev/null 2>&1 || {
  echo "ERROR: Docker Compose not available"
  exit 1
}

# -------------------------
# COMPOSE CHECK
# -------------------------
echo "Validating docker-compose files..."

test -f docker-compose.yml || { echo "Missing docker-compose.yml"; exit 1; }
test -f docker-compose.prod.yml || { echo "Missing docker-compose.prod.yml"; exit 1; }

# -------------------------
# PHP CONTAINER CHECK
# -------------------------
echo "Checking container build..."

docker compose build php

# -------------------------
# BASIC BOOT CHECK (non destructive)
# -------------------------
echo "Starting minimal stack check..."

docker compose up -d mysql redis

sleep 5

docker compose exec mysql mysqladmin ping -h localhost --silent || {
  echo "ERROR: MySQL not ready"
  exit 1
}

docker compose exec redis redis-cli ping | grep PONG >/dev/null || {
  echo "ERROR: Redis not ready"
  exit 1
}

echo "OK: Core services healthy"

echo "=================================="
echo " VALIDATION PASSED"
echo "=================================="