# Installation

Clone this repository (`https://github.com/pabloferreiradias/muxiAvaliacao.git`).

Open its folder `cd muxi`.

Run `composer install` to add its dependencies.

Copy `cp .env.example .env`

If using Docker, copy `cp docker-compose.yml.example docker-compose.yml`

Configure `.env`:

* `DB_...` Postgres connection data
* `APP_REDIS_...` Redis connection data
* `APP_ENV` = `production` or `local`
* `APP_DEBUG` = true (for local) or false (for production)
* `APP_URL` must be equal to the project URL

Create a Database for tests in your Postgres