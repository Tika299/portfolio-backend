#!/bin/bash

#!/bin/bash

# Tạo file log nếu chưa có và cấp quyền ngay lập tức
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

# 1. Chờ một chút để chắc chắn Database đã sẵn sàng (khoảng 5 giây)
sleep 5

# 2. Chạy lệnh Migration (bắt buộc có --force vì đây là môi trường Production)
echo "Running migrations..."
#php artisan migrate:fresh --seed
php artisan migrate --force

php artisan filament:assets
php artisan view:cache

echo "Seeding database..."
php artisan db:seed --force

# 3. Tạo liên kết Storage (để hiện ảnh)
echo "Linking storage..."
php artisan storage:link

chmod -R 775 storage bootstrap/cache

# 4. Tối ưu hóa hệ thống
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache

# 5. Khởi động Apache (Lệnh mặc định của image php-apache)
echo "Starting Apache..."
exec apache2-foreground