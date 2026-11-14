#!/bin/sh

# ვასწორებთ storage და cache ფოლდერების უფლებებს, რომ ლოგებმა და სესიებმა იმუშაოს
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ვწმენდთ ქეშს და ვუშვებთ მიგრაციებს
php artisan config:clear
php artisan route:clear
php artisan migrate --force

# ვრთავთ აპლიკაციას
apache2-foreground