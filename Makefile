.PHONY: install start database schema fixtures server post_fixtures

# Variables
DOCKER_COMPOSE=docker compose
COMPOSER=composer
NPM=npm
SYMFONY=bin/console

install:
	@echo "Installing dependencies..."
	$(COMPOSER) install
	$(NPM) install

build:
	@echo "Building assets..."
	$(NPM) run build

docker:
	@echo "Starting Docker containers..."
	$(DOCKER_COMPOSE) up -d

database:
	@echo "Creating the database..."
	$(SYMFONY) doctrine:database:create

schema:
	@echo "Updating the database schema..."
	$(SYMFONY) doctrine:schema:update --force

fixtures:
	@echo "Loading fixtures..."
	$(SYMFONY) h:fixtures:load --no-interaction

post_fixtures:
	@echo "Updating APP_ENV to prod..."
	sed -i '' 's/^APP_ENV=.*/APP_ENV=prod/' .env
	@echo "Clearing cache..."
	$(SYMFONY) cache:clear

server:
	@echo "Starting Symfony server..."
	symfony server:start -d

start: install build docker database schema fixtures post_fixtures server
	@echo "Perfect, now go to http://localhost:8000 (if the port is not running) with this account email: admin@admin.admin and password: password"
