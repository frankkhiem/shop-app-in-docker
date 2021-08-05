start:
	- UID=$(id -u) GID=$(id -g) && docker-compose up -d
	- docker-compose run npm run watch
stop:
	- docker-compose stop
clear-config:
	- docker-compose run php php artisan config:cache
autoload:
	- docker-compose run composer dump-autoload
migrate:
	- docker-compose run php php artisan migrate
go-inside-php-container:
	- docker-compose exec php sh
install:
	- sudo docker-compose up -d
	- sudo docker-compose run npm run prod
	- sudo docker-compose run php php artisan migrate
	- sudo docker-compose run php php artisan db:seed

