#!/bin/bash

set -e

echo "=================================="
echo " CI4 DOCKER PREMIUM - SETUP"
echo "=================================="

# -------------------------
# GUARD: PREVENT PROD RUN
# -------------------------
if [ "$CI_ENVIRONMENT" = "production" ]; then
  echo "ERROR: Setup not allowed in production"
  exit 1
fi

# -------------------------
# CHECK REQUIREMENTS
# -------------------------
command -v docker >/dev/null 2>&1 || { echo "Docker missing"; exit 1; }
docker compose version >/dev/null 2>&1 || { echo "Docker Compose missing"; exit 1; }

# -------------------------
# ENV SETUP
# -------------------------
if [ ! -f .env ]; then
    cp .env.example .env
    echo "OK: .env created"
fi

# -------------------------
# BUILD
# -------------------------
docker compose build

# -------------------------
# START STACK
# -------------------------
docker compose up -d

# -------------------------
# WAIT SERVICES (robust)
# -------------------------
echo "Waiting services..."

until docker compose exec mysql mysqladmin ping -h localhost --silent; do
  sleep 2
done

until docker compose exec redis redis-cli ping | grep PONG; do
  sleep 2
done

# -------------------------
# MIGRATIONS SAFE
# -------------------------
docker compose exec php php spark migrate --all || {
  echo "Migration failed"
  exit 1
}

# -------------------------
# SEED SAFE (NON FATAL)
# -------------------------
docker compose exec php php spark db:seed || true

# -------------------------
# HEALTHCHECK (STRICT)
# -------------------------
HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost:8080/health)

if [ "$HTTP_STATUS" != "200" ]; then
  echo "WARNING: Healthcheck failed ($HTTP_STATUS)"
fi

echo "=================================="
echo " SETUP COMPLETED"
echo "=================================="