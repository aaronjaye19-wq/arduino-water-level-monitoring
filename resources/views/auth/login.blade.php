<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Water Level Monitoring</title>
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
            justify-content: center;
            align-items: center;
            padding: 20px;
        }

        .login-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .login-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .login-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .login-header p {
            color: #666;
            font-size: 14px;
        }

        .form-group {
            margin-bottom: 20px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 500;
            font-size: 14px;
        }

        input[type="email"],
        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="email"]:focus,
        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-login {
            width: 100%;
            padding: 12px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            margin-top: 10px;
        }

        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-login:active {
            transform: translateY(0);
        }

        .error-message {
            color: #e74c3c;
            font-size: 14px;
            margin-top: 5px;
            padding: 10px;
            background: #fadbd8;
            border-radius: 5px;
            border-left: 4px solid #e74c3c;
        }

        .success-message {
            color: #27ae60;
            font-size: 14px;
            margin-bottom: 20px;
            padding: 10px;
            background: #d5f4e6;
            border-radius: 5px;
            border-left: 4px solid #27ae60;
        }

        .login-links {
            display: flex;
            justify-content: space-between;
            margin-top: 15px;
            font-size: 14px;
        }

        .login-links a {
            color: #667eea;
            text-decoration: none;
            transition: color 0.3s;
        }

        .login-links a:hover {
            color: #764ba2;
            text-decoration: underline;
        }

        .register-link {
            text-align: center;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #eee;
        }

        .register-link p {
            color: #666;
            font-size: 14px;
        }

        .register-link a {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
            transition: color 0.3s;
        }

        .register-link a:hover {
            color: #764ba2;
        }

        .demo-credentials {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .demo-credentials strong {
            color: #333;
        }

        .demo-credentials p {
            color: #666;
            margin: 5px 0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="login-header">
            <h1>🌊 Water Level Monitor</h1>
            <p>Login to your account</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif

        @if (session('message'))
            <div class="success-message">{{ session('message') }}</div>
        @endif

        <div class="demo-credentials">
            <strong>Demo Admin Account:</strong>
            <p>📧 Email: <code>admin@waterflow.local</code></p>
            <p>🔐 Password: <code>Admin@123456</code></p>
        </div>

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" placeholder="your@email.com" required value="{{ old('email') }}">
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" class="btn-login">Login</button>

            <div class="login-links">
                <a href="{{ route('forgot-password') }}">Forgot Password?</a>
                <a href="{{ route('register') }}">Create Account</a>
            </div>
        </form>

        <div class="register-link">
            <p>Don&apos;t have an account? <a href="{{ route('register') }}">Register here</a></p>
        </div>
    </div>
</body>
</html>
