init:
	make stop
	make start

stop:
	docker compose stop

start:
	docker compose up -d

down:
	docker compose down

restart:
	make stop
	make start

tests.all:
	PHP=74 make tests.run
	PHP=80 make tests.run
	PHP=81 make tests.run
	PHP=82 make tests.run

cs:
	docker exec -it 68publishers.asset.81 vendor/bin/php-cs-fixer fix -v

stan:
	PHP=81 make composer.update
	docker exec -it 68publishers.asset.81 vendor/bin/phpstan analyse --level 9 src

composer.update:
ifndef PHP
	$(error "PHP argument not set.")
endif
	@echo "========== Installing dependencies with PHP $(PHP) ==========" >&2
	docker exec -it 68publishers.asset.$(PHP) composer update --no-progress --prefer-dist --prefer-stable --optimize-autoloader --quiet

composer.update-lowest:
ifndef PHP
	$(error "PHP argument not set.")
endif
	@echo "========== Installing dependencies with PHP $(PHP) (prefer lowest dependencies) ==========" >&2
	docker exec -it 68publishers.asset.$(PHP) composer update --no-progress --prefer-dist --prefer-lowest --prefer-stable --optimize-autoloader --quiet

tests.run:
ifndef PHP
	$(error "PHP argument not set.")
endif
	PHP=$(PHP) make composer.update
	@echo "========== Running tests with PHP $(PHP) ==========" >&2
	docker exec -it 68publishers.asset.$(PHP) vendor/bin/tester -C -s ./tests
	PHP=$(PHP) make composer.update-lowest
	@echo "========== Running tests with PHP $(PHP) (prefer lowest dependencies) ==========" >&2
	docker exec -it 68publishers.asset.$(PHP) vendor/bin/tester -C -s ./tests
