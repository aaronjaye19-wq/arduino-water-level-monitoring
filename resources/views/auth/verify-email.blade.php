<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email - Water Level Monitoring</title>
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

        .verify-container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
            width: 100%;
            max-width: 450px;
            padding: 40px;
        }

        .verify-header {
            text-align: center;
            margin-bottom: 30px;
        }

        .verify-header h1 {
            color: #333;
            font-size: 28px;
            margin-bottom: 10px;
        }

        .verify-header p {
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

        input[type="text"] {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #ddd;
            border-radius: 5px;
            font-size: 18px;
            text-align: center;
            letter-spacing: 4px;
            transition: border-color 0.3s;
            font-weight: 600;
        }

        input[type="text"]:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }

        .btn-verify {
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

        .btn-verify:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-verify:active {
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

        .info-box {
            background: #d5f4e6;
            border-left: 4px solid #27ae60;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .info-box strong {
            color: #27ae60;
        }

        .info-box p {
            color: #2d5016;
            margin: 5px 0;
        }

        .code-display {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 5px;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .code-display strong {
            color: #856404;
            font-size: 18px;
            display: block;
            margin-top: 8px;
            letter-spacing: 2px;
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
    <div class="verify-container">
        <div class="verify-header">
            <h1>✉️ Verify Your Email</h1>
            <p>Enter the verification code sent to your email</p>
        </div>

        @if ($errors->any())
            @foreach ($errors->all() as $error)
                <div class="error-message">{{ $error }}</div>
            @endforeach
        @endif

        <div class="email-display">
            {{ $email }}
        </div>

        <div class="info-box">
            <strong>ℹ️ Verification Code</strong>
            <p>A 6-digit code has been generated for this email address.</p>
            <p>The code expires in 15 minutes.</p>
        </div>

        @if ($code)
            <div class="code-display">
                <strong>Your code: {{ $code }}</strong>
            </div>
        @endif

        <form action="{{ route('verify-email') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="code">Verification Code</label>
                <input type="text" id="code" name="code" placeholder="000000" maxlength="6" inputmode="numeric" required autofocus>
            </div>

            <button type="submit" class="btn-verify">Verify Email</button>
        </form>

        <div class="back-link">
            <a href="{{ route('login') }}">← Back to Login</a>
        </div>
    </div>
</body>
</html>
