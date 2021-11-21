APP=docker-compose exec php-fpm
APP_TEST=docker-compose exec -T php-fpm
COMPOSER=docker run --rm --tty --volume $${PWD}:/app --volume $${COMPOSER_HOME:-$$HOME/.composer}:/tmp composer

install: build key migrate helper
build: set-compose set-env build-container install-packages

install-packages:
	$(COMPOSER) install
update-packages:
	$(COMPOSER) update
build-container:
	docker-compose up -d --build
up-build-test: set-env-testing set-compose-test install-packages build-container
	$(APP_TEST) php artisan key:generate -n
	$(APP_TEST) php artisan migrate
	$(APP_TEST) php artisan storage:link
	$(APP_TEST) php artisan test --env=testing
set-env:
	-cp -n .env.example .env
set-env-testing:
	cp .env.testing .env
key:
	$(APP) php artisan key:generate -n
set-compose:
	-cp -n docker-compose.override.example.yml docker-compose.override.yml
set-compose-test:
	-cp -n docker-compose.override.example-test.yml docker-compose.override.yml
migrate:
	$(APP) php artisan migrate
migrate-fresh:
	$(APP) php artisan migrate:fresh
migration:
	@read -p "$(APP) php artisan make:migration " NAME; \
	$(APP) php artisan make:migration $$NAME
helper:
	$(APP) php artisan ide-helper:generate
	$(APP) php artisan ide-helper:meta
helper-models:
	$(APP) php artisan ide-helper:models -R -W
tinker:
	$(APP) php artisan tinker
exec:
	@read -p "$(APP) " COMMAND; \
	$(APP) $$COMMAND
