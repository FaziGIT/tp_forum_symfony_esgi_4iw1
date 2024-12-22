Getting started:

- Go change Makefile line 4 if you start docker with docker-compose and not docker compose
- And after this, you can ```make start``` in cmd and click [here](http://localhost:8000) if the port is not running.

If an error appeared you can make all the steps manually:

- ```composer install```
- ```npm install```
- ```npm run build```
- ```docker compose up -d```
- ```php bin/console doctrine:database:create```
- ```php bin/console doctrine:schema:update --force```
- ```php bin/console h:f:l --no-interaction```
- Start the Symfony server with `symfony server:start -d`.
