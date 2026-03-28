FROM richarvey/nginx-php-fpm:2.2.0

# Copy code
COPY . .

# Cài dependencies
RUN composer install --no-dev --optimize-autoloader

# Cấu hình quyền thư mục
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# Expose port
EXPOSE 8080

# Start nginx + php-fpm
CMD ["./start.sh"]