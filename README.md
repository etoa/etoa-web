# EtoA Portal and Login Site

Portal site for the Escape to Andromeda browser game.

## Requirements

* PHP 7.x
* APCu PHP Extension
* MySQL
* Composer

## Installation

### Install dependencies

Run:

    composer install
    composer dump-autoload

### Setup database connection

Copy `config/app.dist.php` to `config/app.php` and configure the database connection parameters.
