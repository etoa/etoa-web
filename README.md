# EtoA Portal and Login Site

Portal site for the Escape to Andromeda browser game.

## Requirements

* PHP 8.x
* APCu PHP Extension
* MySQL
* Composer

## Installation

### Install dependencies

Run:

    composer install

### Setup database connection

Copy `config/app.dist.php` to `config/app.php` and configure the database connection parameters.

## Use docker images

Build and start application:

    cp config/app.docker.php config/app.php
    docker-compose build
    docker-compose up -d
    docker exec -it -w /app etoa-web-php-1 composer install

Terminate application:

    docker-compose down
