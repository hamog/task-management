# Task Management

A demo application to illustrate how Inertia.js works.


## Installation

Clone the repo locally:

```sh
git clone https://github.com/hamog/task-management.git
cd task-management/
```

Install PHP dependencies:

```sh
composer install
```

Install NPM dependencies:

```sh
npm ci
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

Create a MySQL database and update your configuration in .env file.

Run database migrations:

```sh
php artisan migrate
```

Run database seeder:

```sh
php artisan db:seed
```

Run the dev server (the output will give the address):

```sh
php artisan serve
```

You're ready to go! Visit Task Management in your browser, and login with:

- **Username:** user@example.com
- **Password:** password

## Running tests

To run the tests, run:

```
php artisan test
```
