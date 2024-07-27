USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm php
DC_EXEC = ${DC} exec php

PHONY: help
.DEFAULT_GOAL := help

help: ## ❔  This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: down build init install up migrate fixtures tests success-message console ## ⚙️ Setup application.

init:  ## 🔢 Initialize environment and other .dist files.
	@if [ ! -f .env ]; then (echo "Создаем файл .env из .env.dist" && cp .env.dist .env); else (echo "Файл .env уже создан"); fi
	@if [ ! -f .env.test ]; then (echo "Создаем файл .env.test из .env.test.dist" && cp .env.test.dist .env.test); else (echo "Файл .env.test уже создан"); fi
	@if [ ! -f docker-compose.yml ]; then (echo "Создаем файл docker-compose.yml из docker-compose.yml.dist" && cp docker-compose.yml.dist docker-compose.yml); else (echo "Файл docker-compose.yml уже создан"); fi
	@if [ ! -f phpunit.xml ]; then (echo "Создаем файл phpunit.xml из phpunit.xml.dist" && cp phpunit.xml.dist phpunit.xml); else (echo "Файл phpunit.xml уже создан"); fi

build: ## 🔧 Build services.
	${DC} build $(c)

up: ## ⬆️ Create and start services.
	${DC} up -d $(c)

stop: ## ⛔  Stop services.
	${DC} stop $(c)

start: ## ▶  Start services.
	${DC} start $(c)

down: ## ⬇️ Stop and remove containers and volumes.
	${DC} down -v $(c)

restart: stop start ## 🔃 Restart services.

console: ## 👤 Login in console.
	${DC_EXEC} /bin/bash

install: ## 🌟 Install dependencies without running the whole application.
	${DC_RUN} composer install

migrate: ## 📝 Run migration.
	@echo "📝 Run migration..."
	sleep 3
	${DC_RUN} bin/console doctrine:migration:migrate -n

fixtures: ## 📄 Create fixtures.
	@echo "📄 Create fixtures..."
	${DC_RUN} bin/console doctrine:fixtures:load -n

tests: ## 📟 Run tests.
	@echo "📟 Run tests..."
	${DC_RUN} php vendor/bin/phpunit

success-message:
	@echo "🚀 Project installed successfully!"
	@echo "You can now access the application at http://localhost:8337"
