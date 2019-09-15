# Events in Wichita

An online calendar of events happening in Wichita, KS.

## Installation

Clone the repo locally:

```sh
git clone https://github.com/imacrayon/eventsinwichita eventsinwichita
cd eventsinwichita
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm install
```

Build assets:

```sh
npm run dev
```

Setup configuration:

```sh
cp .env.example .env
```

Generate application key:

```sh
php artisan key:generate
```

Create a SQLite database. You can also use another database (MySQL, Postgres), simply update your configuration accordingly.

```sh
touch database/database.sqlite
```

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Start the local web server, or use Laravel Valet or Homestead.

```sh
php artisan serve
```

You're ready to go! Visit [http://127.0.0.1:8000](http://127.0.0.1:8000) in your browser, and login with:

- **Username:** admin@example.com
- **Password:** secret

## Running tests

To run the Events in Wichita tests, run:

```sh
vendor/bin/phpunit
```
