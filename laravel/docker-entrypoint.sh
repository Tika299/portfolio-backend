#!/bin/bash

# 1. Cấp quyền log
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

# 2. Đợi DB sẵn sàng
sleep 5

# 3. Dọn dẹp Cache (Cực kỳ quan trọng để xóa dấu vết Cloudinary)
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 4. Chạy Migration & Seed
echo "Running migrations..."
php artisan migrate --force
php artisan db:seed --force

# 5. Assets & Link Storage
php artisan filament:assets
php artisan storage:link --force

# 6. Cấp quyền cuối cùng cho các thư mục ghi file
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. CHỈ DÙNG 1 LỆNH EXEC CUỐI CÙNG Ở ĐÂY
echo "Starting Apache..."
exec apache2-foreground