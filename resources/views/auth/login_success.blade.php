<!DOCTYPE html>
<html>
<head>
    <title>Đăng nhập thành công</title>
</head>
<body>
    <!-- Bạn có thể thêm một thông báo như: "Đăng nhập thành công! Đang đóng cửa sổ..." -->

    <script>
        // Gửi thông báo đăng nhập thành công đến trang gốc
        window.opener.postMessage('login_success', '*');
        // Đóng tab hiện tại
        window.close();
    </script>
</body>
</html>
