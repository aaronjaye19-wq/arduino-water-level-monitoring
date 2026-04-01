<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }

        .container {
            width: 100%;
            max-width: 500px;
            background-color: #ffffff;
            padding: 60px 40px;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 40px;
        }

        .header h1 {
            font-size: 28px;
            font-weight: 600;
            color: #000000;
            margin-bottom: 12px;
        }

        .header p {
            font-size: 14px;
            color: #666666;
            line-height: 1.6;
        }

        .message {
            background-color: #f3f4f6;
            border-left: 4px solid #000000;
            padding: 16px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 14px;
            color: #333333;
            line-height: 1.6;
        }

        .message.success {
            border-left-color: #000000;
            background-color: #f3f4f6;
            color: #333333;
        }

        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            font-weight: 500;
            color: #000000;
        }

        .form-group input {
            width: 100%;
            padding: 12px;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            font-size: 14px;
            font-family: inherit;
            transition: all 0.2s;
        }

        .form-group input:focus {
            outline: none;
            border-color: #000000;
            box-shadow: 0 0 0 3px rgba(0, 0, 0, 0.1);
        }

        .error-message {
            background-color: #fef2f2;
            border-left: 4px solid #dc2626;
            padding: 12px;
            margin-bottom: 24px;
            border-radius: 4px;
            font-size: 13px;
            color: #991b1b;
            line-height: 1.6;
        }

        .error-message ul {
            margin: 8px 0 0 20px;
        }

        .error-message li {
            margin-bottom: 4px;
        }

        .submit-btn {
            width: 100%;
            padding: 12px 24px;
            background-color: #000000;
            color: #ffffff;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .submit-btn:hover {
            background-color: #333333;
        }

        .back-link {
            text-align: center;
            margin-top: 24px;
        }

        .back-link a {
            color: #000000;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Reset Password</h1>
            <p>Enter your email address and we&apos;ll send you a link to reset your password.</p>
        </div>

        @if (session('message'))
            <div class="message success">
                {{ session('message') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="error-message">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('password.send') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="{{ old('email') }}"
                    placeholder="you@example.com"
                    required
                >
            </div>

            <button type="submit" class="submit-btn">Send Reset Link</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">Back to Login</a>
        </div>
    </div>
</body>
</html>
