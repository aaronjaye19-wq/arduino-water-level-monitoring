<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Water Sensor Dashboard</title>
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
        .error {
            background-color: #ffebee;
            border: 1px solid #ffcdd2;
            color: #d32f2f;
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
    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Password</h1>

        @if (session('error'))
            <div class="error">{{ session('error') }}</div>
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

        <form action="{{ route('reset-password-submit', ['token' => $token]) }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" required>
                @error('password')
                    <span class="error-message">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" required>
            </div>

            <button type="submit">Reset Password</button>
        </form>
    </div>
</body>
</html>
