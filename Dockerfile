FROM php:8.4-fpm

# تثبيت الإضافات والمكتبات المطلوبة لـ Laravel مع دعم SQLite
RUN apt-get update && apt-get install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    nginx \
    sqlite3 \
    libsqlite3-dev

RUN docker-php-ext-install mbstring exif pcntl bcmath gd pdo_sqlite

# تثبيت Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# نسخ ملفات المشروع وتحديد المجلد الرئيسي
WORKDIR /var/www
COPY . .

# تثبيت مكتبات الـ PHP والـ Node وتجميع واجهات Tailwind
RUN composer install --no-dev --optimize-autoloader
RUN apt-get install -y nodejs npm && npm install && npm run build

# إعداد قاعدة بيانات SQLite وحقن الصلاحيات لتعمل بأمان وسرعة فائقة
RUN mkdir -p /var/www/database && touch /var/www/database/database.sqlite
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache /var/www/database

# نسخ إعدادات Nginx المخصصة لـ Laravel
COPY nginx.conf /etc/nginx/nginx.conf

# فتح المنفذ وتشغيل قاعدة البيانات وإعطاء الصلاحيات الكاملة للملفات ثم تشغيل السيرفر
EXPOSE 80
CMD php artisan migrate:fresh --seed --force && chown -R www-data:www-data /var/www/database /var/www/storage && service nginx start && php-fpm
