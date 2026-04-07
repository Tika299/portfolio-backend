#  Portfolio Backend - Headless CMS & REST API (Laravel 12)

Hệ quản trị nội dung (CMS) mạnh mẽ đóng vai trò là "bộ não" cho hệ thống Portfolio cá nhân. Dự án cung cấp các RESTful API bảo mật cho Frontend (Next.js) và một giao diện quản trị hiện đại để quản lý dự án, bài viết kỹ thuật và tương tác với nhà tuyển dụng.

##  Tech Stack (Công nghệ sử dụng)
*   **Framework:** Laravel 12 (Phiên bản mới nhất).
*   **Admin Panel:** Filament PHP v4 (Tối ưu trải nghiệm quản trị nội dung).
*   **Database:** PostgreSQL (Triển khai trên Render).
*   **Cloud Storage:** Supabase Storage (S3 Compatible) - Giải quyết vấn đề lưu trữ vĩnh viễn trên môi trường Docker/Ephemeral.
*   **Language:** PHP 8.3+.
*   **Real-time Notification:** Discord Webhook (Thông báo tin nhắn mới tức thì).

##  Tính năng nổi bật
*   **Headless CMS Architecture:** Tách biệt hoàn toàn logic Backend và giao diện, phục vụ dữ liệu qua JSON API chuẩn xác.
*   **S3 Integrated File System:** Tích hợp Supabase S3 giúp quản lý hình ảnh chuyên nghiệp, đảm bảo dữ liệu không bị mất khi Server restart.
*   **Markdown Support:** Cho phép viết bài Blog và mô tả dự án bằng định dạng Markdown (GitHub-style), hỗ trợ highlight code cho dân kỹ thuật.
*   **Discord Integration:** Mỗi khi có nhà tuyển dụng gửi thông báo qua Form liên hệ, hệ thống sẽ tự động bắn tin nhắn về kênh Discord cá nhân.
*   **Advanced Casting:** Tối ưu hóa kiểu dữ liệu (Boolean, DateTime) để tương thích hoàn hảo giữa Laravel và PostgreSQL.
*   **SEO Ready:** API Resource được format chuẩn để cung cấp Metadata động cho Frontend.

##  Cấu trúc thư mục tiêu biểu
```text
laravel/
├── app/
│   ├── Filament/          # Cấu hình giao diện Admin Dashboard
│   ├── Http/Controllers/  # Xử lý Logic API Endpoints
│   ├── Http/Resources/    # Format dữ liệu JSON trả về cho Next.js
│   └── Models/            # Định nghĩa cấu trúc dữ liệu & Casting
├── config/                # Cấu hình Filesystem (S3), Database...
├── database/              # Migrations & Seeders (Tự động khởi tạo dữ liệu)
└── docker-entrypoint.sh   # Script tự động hóa lúc khởi chạy (Auto-migrate)
```

##  Cài đặt & Chạy dưới Local
1. **Clone repository:**
   ```bash
   git clone https://github.com/Tika299/portfolio-backend.git
   cd laravel
   ```
2. **Cài đặt thư viện:**
   ```bash
   composer install
   ```
3. **Cấu hình môi trường:** Copy file `.env.example` thành `.env` và cấu hình:
   *   Database (MySQL/PostgreSQL)
   *   Supabase S3 Keys
   *   Discord Webhook URL
4. **Khởi tạo Database & Key:**
   ```bash
   php artisan key:generate
   php artisan migrate --seed
   ```
5. **Khởi động server:**
   ```bash
   php artisan serve
   ```

##  Docker & Deployment
Dự án được đóng gói bằng **Docker** và triển khai trên nền tảng **Render**. 
*   Sử dụng `Dockerfile` tối ưu cho PHP 8.3 Apache.
*   `docker-entrypoint.sh` giúp tự động hóa việc dọn dẹp cache và chạy migration mỗi khi deploy bản mới.

---
**Được xây dựng bởi Lê Xuân Vũ.**  
*Xem mã nguồn Frontend (Next.js) tại [đây](https://github.com/Tika299/portfolio-frontend).*
