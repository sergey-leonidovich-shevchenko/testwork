USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm php
DC_EXEC = ${DC} exec php

PHONY: help
.DEFAULT_GOAL := help

help: ## ❔  This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: init install up tests success-message console ## ⚙️ Setup application.

init:  ## 🔢 Initialize environment and other .dist files.
	@if [ ! -f .env ]; then (echo "Создаем файл .env из .env.dist" && cp .env.dist .env); else (echo "Файл .env уже создан"); fi
	@if [ ! -f .env.test ]; then (echo "Создаем файл .env.test из .env.test.dist" && cp .env.test.dist .env.test); else (echo "Файл .env.test уже создан"); fi
	@if [ ! -f docker-compose.yaml ]; then (echo "Создаем файл docker-compose.yaml из docker-compose.yaml.dist" && cp docker-compose.yaml.dist docker-compose.yaml); else (echo "Файл docker-compose.yaml уже создан"); fi
	@if [ ! -f phpunit.xml ]; then (echo "Создаем файл phpunit.xml из phpunit.xml.dist" && cp phpunit.xml.dist phpunit.xml); else (echo "Файл phpunit.xml уже создан"); fi
	@if [ ! -f phpstan.neon ]; then (echo "Создаем файл phpstan.neon из phpstan.neon.dist" && cp phpstan.neon.dist phpstan.neon); else (echo "Файл phpstan.neon уже создан"); fi

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

tests: ## 📟 Run tests.
	@echo "📟 Run tests..."
	${DC_RUN} php vendor/bin/phpunit

phpstan: ## 📊 Run phpstan analysis
	${DC_RUN} php vendor/bin/phpstan analyse

success-message:
	@echo "🚀 Project installed successfully!"
	@echo "You can now access the application at http://localhost:8337"
