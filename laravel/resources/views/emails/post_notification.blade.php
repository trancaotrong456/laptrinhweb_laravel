<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Khuyến mãi mới: {{ $post->title }}</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 20px; }
        .email-container { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background: #2e7d32; color: #ffffff; text-align: center; padding: 20px; font-size: 24px; font-weight: bold; }
        .content { padding: 30px; color: #333333; line-height: 1.6; }
        .post-title { font-size: 20px; color: #1b5e20; margin-bottom: 10px; font-weight: bold; }
        .btn { display: inline-block; padding: 12px 24px; background: #ff9800; color: #ffffff; text-decoration: none; border-radius: 4px; font-weight: bold; margin-top: 20px; }
        .footer { background: #eeeeee; text-align: center; padding: 15px; font-size: 12px; color: #777777; }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            🛒 Siêu Thị Trực Tuyến
        </div>
        <div class="content">
            <p>Xin chào,</p>
            <p>Chúng tôi vừa cập nhật một chương trình khuyến mãi/tin tức mới mà bạn có thể sẽ quan tâm!</p>
            
            <div class="post-title">{{ $post->title }}</div>
            
            <p>{{ \Illuminate\Support\Str::limit(strip_tags($post->content), 120) }}</p>
            
            <div style="text-align: center;">
                <a href="{{ route('posts.show', $post->id) }}" class="btn">Xem Chi Tiết Ngay</a>
            </div>
            
            <p style="margin-top: 30px;">Cảm ơn bạn đã luôn đồng hành cùng chúng tôi!</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Siêu Thị Trực Tuyến. Tất cả các quyền được bảo lưu.<br>
            Bạn nhận được email này vì đã đăng ký tài khoản tại hệ thống của chúng tôi.
        </div>
    </div>
</body>
</html>
