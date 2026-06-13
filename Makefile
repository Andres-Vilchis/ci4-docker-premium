# =========================
# CI4 DOCKER PREMIUM KIT
# SAAS-GRADE MAKEFILE v2 (ALIGNED)
# =========================

APP_ENV ?= development

psysh:
	docker compose exec php php spark console

# =====================================================
# CORE SETUP
# =====================================================

setup:
	@bash scripts/setup.sh

validate:
	@bash scripts/validate.sh

preflight:
	@echo "Running full preflight..."
	@make validate
	@make test
	@make stan
	@make rector

# =====================================================
# DOCKER LIFECYCLE
# =====================================================

up:
	docker compose up -d --build

down:
	docker compose down

restart:
	docker compose down && docker compose up -d --build

build:
	docker compose build --no-cache

logs:
	docker compose logs -f

shell:
	docker compose exec php bash

bash:
	docker compose exec php bash

# =====================================================
# CACHE
# =====================================================

cache-clear:
	docker compose exec php php spark cache:clear

cache-info:
	docker compose exec php php spark cache:info

# =====================================================
# DATABASE (SAFE LAYER)
# =====================================================

migrate:
	docker compose exec php php spark migrate --all

migrate-status:
	docker compose exec php php spark migrate:status
db-reset:
	docker compose exec php php spark migrate:refresh

db-reset-safe:
	@if [ "$(APP_ENV)" = "production" ]; then \
		echo "ERROR: DB reset blocked in production"; exit 1; \
	fi
	docker compose exec php php spark migrate:refresh

seed:
	docker compose exec php php spark db:seed DatabaseSeeder

seed-admin:
	docker compose exec php php spark db:seed UserSeeder

bootstrap:
	docker compose exec php php spark db:seed SaasBaseSeeder

# =====================================================
# QUALITY GATES
# =====================================================

test:
	docker compose exec php vendor/bin/phpunit

stan:
	docker compose exec php vendor/bin/phpstan analyse

rector:
	docker compose exec php vendor/bin/rector process --dry-run

check:
	docker compose exec php composer check

# =====================================================
# OBSERVABILITY
# =====================================================

health:
	@curl -s http://localhost:8080/health || echo "Healthcheck failed"

routes:
	docker compose exec php php spark routes

# =====================================================
# RELEASE FLOW (FIXED)
# =====================================================

validate-release:
	@echo "Running release validation..."
	@bash scripts/validate.sh || (echo "Validation failed" && exit 1)
	@git diff --quiet || (echo "Uncommitted changes detected" && exit 1)
	@test "$$(git branch --show-current)" = "main" || (echo "Must be on main branch" && exit 1)
	@make preflight

release:
	@if [ -z "$(VERSION)" ]; then \
		echo "Usage: make release VERSION=v1.0.0"; exit 1; \
	fi
	@make validate-release
	@bash scripts/release.sh $(VERSION)

# =====================================================
# DEPLOYMENT (FIXED)
# =====================================================

deploy:
	@if [ -z "$(VERSION)" ]; then \
		echo "Usage: make deploy VERSION=v1.0.0"; exit 1; \
	fi
	@bash scripts/deploy.sh $(VERSION)

# =====================================================
# CLEANUP
# =====================================================

clean:
	docker system prune -af

# =====================================================
# HELP
# =====================================================

help:
	@echo "CI4 Docker Premium Kit v2"
	@echo ""
	@echo "Core:"
	@echo "  make setup"
	@echo "  make validate"
	@echo "  make preflight"
	@echo ""
	@echo "Database:"
	@echo "  make migrate"
	@echo "  make db-reset-safe APP_ENV=development"
	@echo "  make bootstrap"
	@echo ""
	@echo "Quality:"
	@echo "  make test"
	@echo "  make stan"
	@echo "  make rector"
	@echo ""
	@echo "Release:"
	@echo "  make release VERSION=v1.0.0"
	@echo "  make deploy VERSION=v1.0.0"


# =====================================================
# REDIS
# =====================================================

redis-start:
	docker compose start redis
	
redis-stop: 
	docker compose stop redis
	
redis-restart:
	docker compose stop redis && docker compose start redis