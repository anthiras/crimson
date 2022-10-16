# Crimson
REST API for managing courses/classes and signups

## Features

* Instructors can create courses and manage signups
* Users can view courses and sign up
* Auth0 authentication
* Users may register for membership which is automatically periodically invalidated

## Architecture

* Laravel PHP
* Onion architecture
* Core domain layer
* REST/JSON API controllers
* Repositories for writing data using domain models
* Queries for reading data using Laravel resources
* Repositories and queries are segregated and defined in interfaces
* Persistence is implemented in Laravel Eloquent/MySQL, and dependency injected

External services:
* Auth0 for authentication
* Sentry for logging

Run development environment in Docker (recommended) or Laravel Homestead:

## Docker developer setup

1. Run `install.sh` to install PHP packages using composer through docker.
2. Create a configuration file by copying `.evn.example` to `.env` and fill it out with Auth0 credentials.
3. Start the app, including a webserver and database server using `docker compose up -d`.
4. Generate an app key for the `.env` file using `docker compose exec app php artisan key:generate`.
5. Create empty databases using `docker compose exec db mysql -u root -e "create database crimson; create database crimsontest;" -p`. See default password in `docker-compose.yaml`.
6. Run database migrations (optionally with the `--seed` flag to seed database with dummy data): `docker compose exec app php artisan migrate:refresh --seed`
7. Add the domain name to your hosts file: `127.0.0.1 crimson.test`

## Laravel Homestead developer setup

1. [Laravel Homestead](https://laravel.com/docs/6.x/homestead) is a pre-packaged virtual box with everything needed to run Laravel. To run Laravel Homestead you also need to install VirtualBox and Vagrant.
2. Clone the crimson repository.
3. Create an empty MySQL database.
4. Configure a domain name, e.g. `crimson.test` to point to your crimson directory. With Homestead, the domain name and folder mapping is configured in `Homestead.yaml`.
5. Create a configuration file by copying `.evn.example` to `.env` and fill it out with your database credentials, Auth0 credentials etc. An app key can be created using `php artisan key:generate`
6. Install PHP packages: `composer install`
7. Run database migrations (optionally with the `--seed` flag to seed database with dummy data): `php artisan migrate:refresh --seed`

## Developer tools

### Running commands against the docker app

Prefix commands with `docker compose exec app ` if you are running the app in docker.

### Unit tests

```
./vendor/bin/phpunit
```

### Manage user roles

There is a command to add/remove roles to users.

For example, you may want to give yourself administrator rights:

```
php artisan userroles:add your@email.com admin
```