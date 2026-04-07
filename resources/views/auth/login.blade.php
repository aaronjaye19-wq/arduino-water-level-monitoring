<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Water Sensor Dashboard</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', sans-serif;
            background: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            background: white;
            padding: 40px;
            border-radius: 12px;
            border: 1px solid #e5e5e5;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            max-width: 420px;
            width: 100%;
        }
        h1 {
            color: #000;
            margin-bottom: 30px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
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
        input {
            width: 100%;
            padding: 11px 14px;
            border: 1px solid #d0d0d0;
            border-radius: 6px;
            font-size: 14px;
            background: #fafafa;
            transition: all 0.2s;
        }
        input:focus {
            outline: none;
            border-color: #000;
            background: white;
            box-shadow: inset 0 0 0 3px rgba(0, 0, 0, 0.05);
        }
        .error-message {
            color: #d32f2f;
            font-size: 12px;
            margin-top: 6px;
        }
        .errors { margin-bottom: 20px; }
        .errors li { color: #d32f2f; margin-bottom: 8px; font-size: 14px; }
        .success {
            background-color: #f1f8f4;
            border: 1px solid #c8e6c9;
            color: #2e7d32;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        button {
            width: 100%;
            padding: 12px;
            background-color: #000;
            color: white;
            border: none;
            border-radius: 6px;
            font-size: 15px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            margin-top: 10px;
        }
        button:hover {
            background-color: #222;
        }
        button:active {
            transform: scale(0.99);
        }
        .links {
            display: flex;
            justify-content: space-between;
            margin-top: 20px;
            font-size: 14px;
        }
        .links a {
            color: #666;
            text-decoration: none;
        }
        .links a:hover {
            color: #000;
        }
        .register-link {
            text-align: center;
            margin-top: 20px;
        }
        .register-link a {
            color: #666;
            text-decoration: none;
            font-weight: 500;
        }
        .register-link a:hover {
            color: #000;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Login</h1>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if ($errors->any())
            <div class="errors">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="{{ old('email') }}" required>
                @error('email')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit">Login</button>
        </form>

        <div class="links">
            <a href="{{ route('forgot-password') }}">Forgot Password?</a>
            <a href="{{ route('register') }}">Don't have an account?</a>
        </div>
    </div>
</body>
</html>
