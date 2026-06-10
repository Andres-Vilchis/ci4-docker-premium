up:
	docker compose up -d

down:
	docker compose down

build:
	docker compose build --no-cache

install:
	docker compose exec php composer install

routes:
	docker compose exec php php spark routes

shell:
	docker compose exec php bash