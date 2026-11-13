FROM php:8.2-apache

# სისტემური ბიბლიოთეკების და Node.js-ის (npm-ისთვის) დაინსტალირება
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# PHP ექსტენშენების დაინსტალირება
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Composer-ის გადმოწერა
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . /var/www/html

# Composer ბიბლიოთეკების ინსტალაცია
RUN composer install --no-dev --optimize-autoloader

# NPM პაკეტების დაინსტალირება და ფრონტენდის (Tailwind/Vite/Livewire) აწყობა
RUN npm install && npm run build

# Apache-ს დოკუმენტების ფვრილფოინთის გადამისამართება public ფოლდერზე
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Apache-ს პორტის შეცვლა 10000-ზე Render-ისთვის
RUN sed -i 's/80/10000/g' /etc/apache2/sites-available/000-default.conf /etc/apache2/ports.conf

RUN a2enmod rewrite

# უფლებების მინიჭება
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache