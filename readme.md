# Events in Wichita

An online calendar of events happening in Wichita, KS.

## Getting Started

Events in Wichita is built with [Laravel](http://laravel.com).

### Install Dependencies

    composer install

    npm install

### Compile Front End Assets

    npm run watch

### Setup Environment

Copy `.env.example`, rename it to `.env`, and update it with your database, email, Facebook API, and Google API credentials.

### Setup Database

Define database credentials in your newly created `.env` file, then migrate the database.

    php artisan migrate

## Testing

There are tests for most of the basic features.

    vendor/bin/phpunit

*Once in a while the test suit will generate random info that causes a database conflict (such as two users with the same email). Usually running the tests again will fix the problem.*
