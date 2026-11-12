FROM php:8.2-apache

# სისტემური ბიბლიოთეკების დაინსტალირება
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip

# PHP ექსტენშენების დაინსტალირება
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer-ის გადმოწერა
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# სამუშაო დირექტორია
WORKDIR /var/www/html

# პროექტის ფაილების გადატანა
COPY . /var/www/html

# Apache-ს ფვრილფოინთის გადამისამართება public ფოლდერზე
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

RUN a2enmod rewrite

# უფლებების მინიჭება
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache