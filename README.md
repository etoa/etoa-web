# EtoA Portal and Login Site

Portal site for the Escape to Andromeda browser game.

## Requirements

* PHP 8.1+
* APCu PHP Extension
* MySQL/MariaDB
* Composer 2.x
* NodeJS 16.x

## Installation

### Install dependencies

Run:

    composer install
    npm install
    npm run dev

### Setup database connection

Copy `config/app.dist.php` to `config/app.php` and configure the database connection parameters.

## Development

### Code analzsis

Run:

    ./vendor/bin/phpstan analyse

### Code fixer

Run:

    ./vendor/bin/php-cs-fixer fix src

## Use docker images (for development)

Build and start application:

    cp config/app.docker.php config/app.php
    docker-compose build
    docker-compose up -d
    docker-compose run --rm -w /app php composer install

Terminate application:

    docker-compose down
