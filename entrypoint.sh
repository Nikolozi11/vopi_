#!/bin/sh

# 1. ვასწორებთ უფლებებს, რომ ვებ-სერვერმა შეძლოს storage-ში წერა
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 2. ვწმენდთ ქეშს და ვუშვებთ მიგრაციებს
php artisan config:clear
php artisan route:clear
php artisan migrate --force

# 3. ვრთავთ აპლიკაციას
apache2-foreground