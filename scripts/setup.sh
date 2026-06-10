#!/bin/bash

set -e

echo "=================================="
echo " CI4 DOCKER PREMIUM - SETUP"
echo "=================================="

# -------------------------
# 1. CHECK REQUIREMENTS
# -------------------------
echo ""
echo "Step 1: Checking environment..."

command -v docker >/dev/null 2>&1 || {
  echo "ERROR: Docker not installed"
  exit 1
}

docker compose version >/dev/null 2>&1 || {
  echo "ERROR: Docker Compose not available"
  exit 1
}

echo "OK: Docker environment ready"

# -------------------------
# 2. ENV SETUP
# -------------------------
echo ""
echo "Step 2: Environment setup..."

if [ ! -f .env ]; then
    cp .env.example .env
    echo "OK: .env created"
else
    echo "OK: .env already exists"
fi

# -------------------------
# 3. BUILD CONTAINERS
# -------------------------
echo ""
echo "Step 3: Building containers..."

docker compose build

# -------------------------
# 4. START STACK
# -------------------------
echo ""
echo "Step 4: Starting containers..."

docker compose up -d

# -------------------------
# 5. WAIT FOR SERVICES (SAFE WAIT)
# -------------------------
echo ""
echo "Step 5: Waiting for services..."

# MySQL check
echo "Checking MySQL..."
until docker compose exec mysql mysqladmin ping -h "localhost" --silent; do
  sleep 2
done

echo "MySQL is ready"

# Redis check
echo "Checking Redis..."
until docker compose exec redis redis-cli ping | grep PONG; do
  sleep 2
done

echo "Redis is ready"

# -------------------------
# 6. MIGRATIONS
# -------------------------
echo ""
echo "Step 6: Running migrations..."

docker compose exec php php spark migrate --all

# -------------------------
# 7. SEEDERS (SAFE)
# -------------------------
echo ""
echo "Step 7: Running seeders..."

docker compose exec php php spark db:seed || echo "No seeders found"

# -------------------------
# 8. HEALTHCHECK
# -------------------------
echo ""
echo "Step 8: Healthcheck validation..."

HTTP_STATUS=$(curl -s -o /dev/null -w "%{http_code}" http://localhost/health)

if [ "$HTTP_STATUS" != "200" ]; then
  echo "WARNING: Healthcheck returned $HTTP_STATUS"
else
  echo "OK: Healthcheck passed"
fi

# -------------------------
# FINAL STATE
# -------------------------
echo ""
echo "=================================="
echo "SETUP COMPLETED SUCCESSFULLY"
echo "=================================="

echo ""
echo "App: http://localhost:8080"
echo "Health: http://localhost/health"
echo ""
echo "Next step:"
echo "  make test"
echo "  make migrate"