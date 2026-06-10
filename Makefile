# =========================
# CI4 DOCKER PREMIUM KIT
# SAAS-GRADE MAKEFILE
# =========================

APP_ENV ?= development

# -------------------------
# SETUP
# -------------------------
setup:
	@bash scripts/setup.sh

validate:
	@bash scripts/validate.sh

# -------------------------
# DOCKER LIFECYCLE
# -------------------------
up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d --build

build:
	docker compose build --no-cache

# -------------------------
# DEBUG / DEV
# -------------------------
logs:
	docker compose logs -f

shell:
	docker compose exec php bash

bash:
	docker compose exec php bash

# -------------------------
# DATABASE SAFETY LAYER
# -------------------------
migrate:
	docker compose exec php php spark migrate --all

# ⚠️ SAFE SEED (DEV ONLY)
seed-dev:
	@if [ "$(APP_ENV)" != "development" ]; then \
		echo "❌ Seeding allowed only in development"; exit 1; \
	fi
	docker compose exec php php spark db:seed DatabaseSeeder

# 🔥 PRODUCTION BOOTSTRAP (IDEMPOTENT)
bootstrap:
	docker compose exec php php spark db:seed SaasBaseSeeder

# -------------------------
# QUALITY GATES
# -------------------------
test:
	docker compose exec php vendor/bin/phpunit

stan:
	docker compose exec php vendor/bin/phpstan analyse

rector:
	docker compose exec php vendor/bin/rector process --dry-run

check:
	@echo "Running full quality gate..."
	docker compose exec php composer check

# -------------------------
# SYSTEM CHECK
# -------------------------
health:
	@curl -s http://localhost/health || echo "Healthcheck failed"

routes:
	docker compose exec php php spark routes

# -------------------------
# RELEASE GOVERNANCE
# -------------------------
validate-release:
	@echo "Validating release prerequisites..."

	@bash scripts/validate.sh || (echo "Validation script failed" && exit 1)

	@echo "Checking git status..."
	@git diff --quiet || (echo "Uncommitted changes detected" && exit 1)

	@echo "Checking branch..."
	@test "$$(git branch --show-current)" = "main" || (echo "Must be on main branch" && exit 1)

release:
	@make validate-release
	@bash scripts/release.sh

# -------------------------
# DEPLOYMENT
# -------------------------
deploy:
	@bash scripts/deploy.sh

# -------------------------
# CLEANUP
# -------------------------
clean:
	docker system prune -af