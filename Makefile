.PHONY: up down stop start install cli test
include .env

default: install

up:
	docker-compose up -d
down:
	docker-compose down
stop:
	docker-compose stop
start:
	docker-compose start
install: up
	docker-compose exec -T -u=root php bash -c 'chown -R www-data:www-data /var/www/vendor'
	docker-compose exec -T php composer install --no-interaction
	docker-compose exec -T php bash -c "drush site:install --db-url=mysql://$(MYSQL_USER):$(MYSQL_PASS)@$(MYSQL_HOST):$(MYSQL_PORT)/$(MYSQL_DB_NAME) -y"
	@mkdir -p "drush"
	@echo "options:\n  uri: 'http://$(PROJECT_BASE_URL)'" > drush/drush.yml
cli:
	docker-compose exec php bash
test:
	docker-compose exec -T php curl 0.0.0.0:80 -H "Host: $(PROJECT_BASE_URL)" --write-out %{http_code} --silent --output /dev/null
