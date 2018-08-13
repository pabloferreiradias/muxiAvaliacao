# Avaliation Muxi

This project is a avaliation in the selection process.

## Development Environment

### Requirements

* PHP 7.1+
* Postgres 9.4+
* Composer

### Installation

Please check the [installation documentation](docs/installation.md).

## Console usage

* **Make the fisrt avaliation**: `php artisan card:information`
* **Make the second avaliation**: `php artisan order:sync`
* **Create 10 fake orders**: `php artisan order:create 10`

### Code quality

Check code quality by running `php ./vendor/bin/grumphp run`.

### Testing

After installing, run `php ./vendor/bin/phpunit`.
