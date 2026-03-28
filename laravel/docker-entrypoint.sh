#!/bin/bash

# 1. Chờ một chút để chắc chắn Database đã sẵn sàng (khoảng 5 giây)
sleep 5

# 2. Chạy lệnh Migration (bắt buộc có --force vì đây là môi trường Production)
echo "Running migrations..."
php artisan migrate --force

# 3. Tạo liên kết Storage (để hiện ảnh)
echo "Linking storage..."
php artisan storage:link

# 4. Tối ưu hóa hệ thống
echo "Caching config and routes..."
php artisan config:cache
php artisan route:cache

# 5. Khởi động Apache (Lệnh mặc định của image php-apache)
echo "Starting Apache..."
exec apache2-foreground