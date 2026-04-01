<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - Water Level Monitoring</title>
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

        .reset-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .reset-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .reset-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .reset-header p {
            color: #666;
            font-size: 14px;
        }

        .email-display {
            background: #f0f4ff;
            border: 2px solid #667eea;
            border-radius: 5px;
            padding: 12px;
            margin: 20px 0;
            text-align: center;
            font-weight: 600;
            color: #333;
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

        input[type="password"] {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            transition: border-color 0.3s;
        }

        input[type="password"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-reset {
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

        .btn-reset:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-reset:active {
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

        .password-requirements {
            background: #f0f4ff;
            border-left: 4px solid #667eea;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 13px;
        }

        .password-requirements strong {
            color: #333;
        }

        .password-requirements p {
            color: #666;
            margin: 5px 0;
        }

        .back-link {
            text-align: center;
            margin-top: 20px;
        }

        .back-link a {
            color: #667eea;
            text-decoration: none;
            font-size: 14px;
        }

        .back-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="reset-container">
        <div class="reset-header">
            <h1>🔐 Reset Password</h1>
            <p>Create your new password</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif

        <div class="email-display">
            {{ $email }}
        </div>

        <div class="password-requirements">
            <strong>Password Requirements:</strong>
            <p>✓ Minimum 6 characters</p>
            <p>✓ Must match confirmation password</p>
        </div>

        <form action="{{ route('reset-password') }}" method="POST">
            @csrf

            @if ($token)
                <input type="hidden" name="token" value="{{ $token }}">
            @endif

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" id="password" name="password" placeholder="Enter new password" required>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Confirm Password</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Confirm password" required>
            </div>

            <button type="submit" class="btn-reset">Reset Password</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </div>
</body>
</html>
