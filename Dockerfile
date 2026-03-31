FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    git curl libpng-dev libonig-dev libxml2-dev zip unzip nginx \
    libpq-dev \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd pdo_pgsql pgsql

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . /var/www/html

RUN rm -f /etc/nginx/nginx.conf
COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

# التحقق من وجود الملفات
RUN echo "=== Checking files ===" && \
    ls -la /var/www/html/ && \
    ls -la /var/www/html/public/ && \
    test -f /var/www/html/public/index.php && echo "✅ index.php found" || echo "❌ index.php NOT found"

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

RUN mkdir -p /var/www/html/resources/views

RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache || true

EXPOSE 80

CMD php-fpm -D && nginx -g "daemon off;"
