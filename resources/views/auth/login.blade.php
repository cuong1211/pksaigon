<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng nhập - Hệ thống quản lý</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 50px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 450px;
            padding: 40px;
            position: relative;
            overflow: hidden;
        }

        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #667eea, #764ba2);
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
        }

        .logo h1 {
            color: #333;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .logo p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }

        .form-input {
            width: 100%;
            padding: 15px 20px;
            border: 2px solid #e1e5e9;
            border-radius: 12px;
            font-size: 16px;
            transition: all 0.3s ease;
            background: #f8f9fa;
        }

        .form-input:focus {
            outline: none;
            border-color: #667eea;
            background: white;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .form-input.error {
            border-color: #e74c3c;
            background: #fdf2f2;
        }

        .error-message {
            color: #e74c3c;
            font-size: 13px;
            margin-top: 5px;
            display: block;
        }

        .forgot-password {
            text-align: right;
            margin-top: 10px;
        }

        .forgot-password a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .forgot-password a:hover {
            text-decoration: underline;
        }

        .login-btn {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .login-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(102, 126, 234, 0.3);
        }

        .login-btn:active {
            transform: translateY(0);
        }

        .login-btn.loading {
            pointer-events: none;
        }

        .spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-radius: 50%;
            border-top-color: white;
            animation: spin 1s ease-in-out infinite;
            margin-right: 10px;
        }

        .login-btn.loading .spinner {
            display: inline-block;
        }

        .login-btn.loading .btn-text {
            opacity: 0.7;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .alert {
            padding: 15px 20px;
            border-radius: 10px;
            margin-bottom: 25px;
            font-size: 14px;
            font-weight: 500;
            display: flex;
            align-items: center;
        }

        .alert-error {
            background: #fdf2f2;
            color: #e74c3c;
            border: 1px solid #fadbd8;
        }

        .alert-success {
            background: #f0fff4;
            color: #27ae60;
            border: 1px solid #d5f4e6;
        }

        .alert-icon {
            margin-right: 10px;
            font-size: 18px;
        }

        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 13px;
        }

        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 30px 25px;
                margin: 10px;
            }

            .logo h1 {
                font-size: 24px;
            }
        }

        /* Animation */
        .login-container {
            animation: slideUp 0.6s ease-out;
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        /* Input icons */
        .input-icon {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #999;
            font-size: 18px;
        }

        .form-group.has-icon .form-input {
            padding-right: 50px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🏥 Healthcare System</h1>
            <p>Hệ thống quản lý y tế</p>
        </div>

        <form action="{{route('backend.postLogin')}}" method="POST" id="loginForm">
            @csrf
            
            <!-- Alert Messages -->
            @if(session('error'))
                <div class="alert alert-error">
                    <span class="alert-icon">⚠️</span>
                    <span>{{ session('error') }}</span>
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <span class="alert-icon">✅</span>
                    <span>{{ session('success') }}</span>
                </div>
            @endif

            <div class="form-group has-icon">
                <label class="form-label" for="email">Email</label>
                <input 
                    type="email" 
                    id="email"
                    name="email" 
                    class="form-input @error('email') error @enderror"
                    value="{{ old('email') }}"
                    placeholder="Nhập địa chỉ email của bạn"
                    required
                >
                <span class="input-icon">📧</span>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group has-icon">
                <label class="form-label" for="password">Mật khẩu</label>
                <input 
                    type="password" 
                    id="password"
                    name="password" 
                    class="form-input @error('password') error @enderror"
                    placeholder="Nhập mật khẩu của bạn"
                    required
                >
                <span class="input-icon">🔒</span>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
                <div class="forgot-password">
                    <a href="#">Quên mật khẩu?</a>
                </div>
            </div>

            <button type="submit" class="login-btn" id="loginBtn">
                <span class="spinner"></span>
                <span class="btn-text">Đăng nhập</span>
            </button>
        </form>

        <div class="footer">
            <p>&copy; 2025 Healthcare System. All rights reserved.</p>
        </div>
    </div>

    <script>
        // Form submission handling
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            
            // Reset after 3 seconds if no response
            setTimeout(() => {
                btn.classList.remove('loading');
            }, 3000);
        });

        // Input focus animations
        document.querySelectorAll('.form-input').forEach(input => {
            input.addEventListener('focus', function() {
                this.parentElement.style.transform = 'translateY(-2px)';
            });
            
            input.addEventListener('blur', function() {
                this.parentElement.style.transform = 'translateY(0)';
            });
        });

        // Auto-hide alerts after 5 seconds
        document.querySelectorAll('.alert').forEach(alert => {
            setTimeout(() => {
                alert.style.opacity = '0';
                alert.style.transform = 'translateY(-10px)';
                setTimeout(() => {
                    alert.remove();
                }, 300);
            }, 5000);
        });

        // Keyboard shortcuts
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter' && e.ctrlKey) {
                document.getElementById('loginForm').submit();
            }
        });
    </script>
</body>
</html>