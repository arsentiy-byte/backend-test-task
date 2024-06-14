build-and-up: build up composer-install check-env

build:
	- docker-compose -f docker-compose.yml build && docker-compose -f docker-compose.yml pull

up:
	- docker-compose -f docker-compose.yml up -d

down:
	- docker-compose -f docker-compose.yml down

composer-install:
	- docker-compose -f docker-compose.yml exec sio_test composer install

clean-dependencies:
	- rm -rf vendor

check-env:
ifeq (,$(wildcard ./.env))
	cp .env.example .env
endif

test:
	- docker-compose -f docker-compose.yml exec sio_test php bin/phpunit

fixer-test:
	- docker-compose -f docker-compose.yml exec sio_test vendor/bin/php-cs-fixer fix --dry-run --diff

fixer:
	- docker-compose -f docker-compose.yml exec sio_test vendor/bin/php-cs-fixer fix

phpstan:
	- docker-compose -f docker-compose.yml exec sio_test vendor/bin/phpstan analyse -c phpstan.dist.neon
