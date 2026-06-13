FROM php:8.4-fpm

# تثبيت الإضافات والمكتبات المطلوبة لـ Laravel
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx

RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع وتحديد المجلد الرئيسي
WORKDIR /var/www
COPY . .

# تثبيت مكتبات الـ PHP والـ Node
RUN composer install --no-dev --optimize-autoloader
RUN apt-get install -y nodejs npm && npm install && npm run build

# إعدادات الـ Permissions للمجلدات الحيوية
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache
# نسخ إعدادات Nginx المخصصة لـ Laravel
COPY nginx.conf /etc/nginx/nginx.conf

# نسخ إعدادات Nginx المخصصة لـ Laravel
COPY nginx.conf /etc/nginx/nginx.conf

# تشغيل خادم Nginx والـ PHP، مع تشغيل قاعدة البيانات وحقن الصور تلقائياً عند الإقلاع
EXPOSE 80
CMD php artisan migrate:fresh --seed --force && service nginx start && php-fpm

