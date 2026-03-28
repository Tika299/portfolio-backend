# Nâng cấp lên PHP 8.3 kèm Apache
FROM php:8.3-apache

# Cài đặt các thư viện hệ thống cần thiết
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    libonig-dev \
    libxml2-dev \
    libicu-dev \
    libzip-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd intl zip

# Kích hoạt Apache Mod Rewrite
RUN a2enmod rewrite

# Cấu hình Document Root cho Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Thiết lập thư mục làm việc
WORKDIR /var/www/html

# Copy toàn bộ mã nguồn
COPY . .

# Cài đặt Composer từ image chính thức
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Chạy lệnh cài đặt với quyền bỏ qua kiểm tra môi trường nếu cần, 
# nhưng ở đây 8.3 đã khớp nên sẽ chạy mượt.
RUN composer install --no-dev --optimize-autoloader

# Cấp quyền cho các thư mục quan trọng
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Port 80 cho Render
EXPOSE 80