# Crimson
A web app for managing courses/classes and signups

## Features

* Instructors can create courses and manage signups
* Users can view courses and sign up
* Auth0 authentication

## Architecture

* Frontend: React JS and Bootstrap.
* Backend API: Built in PHP on Laravel.
* External services:
  * Auth0 for authentication

## Developer setup

1. Install a PHP/MySQL environment. The recommended method is [Laravel Homestead](https://laravel.com/docs/5.6/homestead) which is a pre-packaged virtual box with everything needed to run Laravel. To run Laravel Homestead you also need to install VirtualBox and Vagrant.
2. Clone the crimson repository.
3. Create an empty MySQL database.
4. Configure a domain name, e.g. crimson.test to point to your crimson directory. With Homestead, the domain name and folder mapping is configured in Homestead.yaml.
5. Create a configuration file by copying `.evn.example` to `.env` and fill it out with your database credentials, Auth0 credentials etc. An app key can be created using `php artisan key:generate`
6. Install PHP packages: `composer install`
7. Install frontend packages: `npm run dev`
