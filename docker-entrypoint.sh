#!/bin/bash
composer install
php artisan key:generate --force
php artisan migrate:fresh --seed
php artisan serve --host=0.0.0.0 --port=8000
