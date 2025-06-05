#!/bin/bash
export $(cat /var/www/html/.env | xargs)  # Load .env variables
cd /var/www/html
php artisan queue:work --stop-when-empty