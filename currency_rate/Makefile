USER_ID=$(shell id -u)

DC = @USER_ID=$(USER_ID) docker compose
DC_RUN = ${DC} run --rm php
DC_EXEC = ${DC} exec php

PHONY: help
.DEFAULT_GOAL := help

help: ## ❔  This help.
	@awk 'BEGIN {FS = ":.*?## "} /^[a-zA-Z_-]+:.*?## / {printf "\033[36m%-30s\033[0m %s\n", $$1, $$2}' $(MAKEFILE_LIST)

setup: down init build install up migrate success-message console ## ⚙️ Setup application.

init:  ## 🔢 Initialize environment and other .dist files.
	@for file in .env .env.test docker-compose.yml .phpunit.xml .php-cs-fixer.php .phpstan.neon; do \
		if [ ! -f $$file ]; then \
			echo "Создаем файл $$file из $$file.dist" && cp $$file.dist $$file; \
		else \
			echo "Файл $$file уже создан"; \
		fi; \
	done

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

run-tests: ## 📟 Run tests.
	@echo "📟 Run tests..."
	${DC_RUN} php vendor/bin/phpunit -c .phpunit.xml

phpstan: ## 📊 Run phpstan analysis
	${DC_RUN} php vendor/bin/phpstan analyse -c .phpstan.neon

success-message:
	@echo "🚀 Project installed successfully!"
	@echo "You can now access the application at http://localhost:8337"

cs-fix:
	${DC_RUN} vendor/bin/php-cs-fixer fix
