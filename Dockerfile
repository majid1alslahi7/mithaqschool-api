FROM php:8.2-fpm

# تثبيت الحزم المطلوبة
RUN apt-get update && apt-get install -y \
    nginx \
    libpq-dev \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# تثبيت إضافات PHP
RUN docker-php-ext-install pdo_pgsql pgsql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع
COPY . /var/www/html

# تكوين Nginx
COPY nginx.conf /etc/nginx/nginx.conf

WORKDIR /var/www/html

# إعداد الصلاحيات
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# تثبيت التبعيات (مع تجاهل متطلبات المنصة)
RUN composer install --no-interaction --optimize-autoloader --no-dev --ignore-platform-reqs || true

# تخزين التكوينات
RUN php artisan config:cache || true
RUN php artisan route:cache || true
RUN php artisan view:cache || true

EXPOSE 80

CMD service nginx start && php-fpm
