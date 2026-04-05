#!/bin/bash

# 1. Cấp quyền log
touch /var/www/html/storage/logs/laravel.log
chown www-data:www-data /var/www/html/storage/logs/laravel.log
chmod 664 /var/www/html/storage/logs/laravel.log

# 2. Đợi Database sẵn sàng
sleep 5

# 3. Xóa cache cũ để nhận cấu hình mới
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# 4. Chạy Migration
# Lưu ý: Trên Render Postgres, dùng migrate --force là an toàn nhất. 
# Nếu muốn xóa sạch và làm lại, hãy dùng migrate:fresh --force
echo "Running migrations..."
php artisan migrate:fresh --seed --force

# 5. Cài đặt assets và link storage
echo "Setting up assets..."
php artisan filament:assets
php artisan storage:link --force

# 6. Cấp quyền cho thư mục storage (Rất quan trọng)
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. CHỈ DÙNG 1 LỆNH EXEC DUY NHẤT Ở CUỐI CÙNG
echo "Starting Apache..."
exec apache2-foreground