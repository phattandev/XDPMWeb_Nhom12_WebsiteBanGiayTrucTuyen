# Sử dụng PHP 8.2 và Apache làm nền tảng
FROM php:8.4-apache

# 1. Cài đặt các công cụ hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libpq-dev \
    curl \
    nano \
    && rm -rf /var/lib/apt/lists/*

# 2. Cài đặt các module PHP thiết yếu cho Laravel và CSDL (MySQL & PostgreSQL)
RUN docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql pdo_pgsql

# 3. Kích hoạt module rewrite của Apache (để các Route của Laravel hoạt động)
RUN a2enmod rewrite

# 4. Định tuyến Apache vào thư mục /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# 5. Cài đặt Composer phiên bản mới nhất
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 6. Đặt thư mục làm việc chính
WORKDIR /var/www/html

# 7. Copy toàn bộ code vào trong Container
COPY . .

# 8. Cài đặt thư viện Backend (bỏ qua các thư viện dev như phpunit)
RUN composer install --no-dev --optimize-autoloader --no-interaction

# 9. Phân quyền cho thư mục storage và cache (để Laravel có thể ghi file)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 10. Cài đặt Node.js, cài thư viện Frontend và tiến hành Build (Vite/Tailwind)
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
RUN npm install \
    && npm run build

# 11. Mở cổng mạng
EXPOSE 80

# 12. Lệnh khởi chạy server
CMD ["apache2-foreground"]