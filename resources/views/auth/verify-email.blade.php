<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
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

        .actions {
            display: flex;
            gap: 12px;
            margin-bottom: 24px;
        }

        .actions form {
            flex: 1;
        }

        .btn {
            width: 100%;
            padding: 12px 24px;
            border: none;
            border-radius: 6px;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: all 0.2s;
            font-family: inherit;
        }

        .btn-primary {
            background-color: #000000;
            color: #ffffff;
        }

        .btn-primary:hover {
            background-color: #333333;
        }

        .btn-secondary {
            background-color: #ffffff;
            color: #000000;
            border: 1px solid #d1d5db;
        }

        .btn-secondary:hover {
            background-color: #f3f4f6;
        }

        .divider {
            text-align: center;
            margin: 32px 0;
            position: relative;
        }

        .divider::before {
            content: '';
            position: absolute;
            top: 50%;
            left: 0;
            right: 0;
            height: 1px;
            background-color: #d1d5db;
        }

        .divider span {
            position: relative;
            background-color: #ffffff;
            padding: 0 12px;
            color: #999999;
            font-size: 13px;
        }

        .footer-text {
            text-align: center;
            font-size: 13px;
            color: #999999;
            line-height: 1.6;
        }

        .footer-text a {
            color: #000000;
            text-decoration: none;
            font-weight: 500;
        }

        .footer-text a:hover {
            text-decoration: underline;
        }

        .info-box {
            background-color: #f9f9f9;
            border: 1px solid #e5e7eb;
            padding: 16px;
            border-radius: 6px;
            margin-top: 24px;
            font-size: 13px;
            color: #666666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Verify Your Email</h1>
            <p>We&apos;ve sent a verification link to your email address.</p>
        </div>

        @if (session('message'))
            <div class="message success">
                {{ session('message') }}
            </div>
        @endif

        <p style="font-size: 14px; color: #333333; margin-bottom: 24px; line-height: 1.6;">
            Please check your inbox and click the verification link to activate your account. If you don&apos;t see the email, check your spam folder.
        </p>

        <div class="actions">
            <form method="POST" action="{{ route('verify.resend') }}">
                @csrf
                <button type="submit" class="btn btn-primary">Resend Verification Link</button>
            </form>
        </div>

        <div class="divider">
            <span>or</span>
        </div>

        <div style="text-align: center;">
            <a href="{{ route('logout') }}" style="display: inline-block; padding: 12px 24px; background-color: #ffffff; color: #000000; border: 1px solid #d1d5db; border-radius: 6px; text-decoration: none; font-weight: 500; font-size: 14px; transition: all 0.2s;" onmouseover="this.style.backgroundColor='#f3f4f6'" onmouseout="this.style.backgroundColor='#ffffff'">
                Log Out
            </a>
        </div>

        <div class="info-box">
            <strong>Need help?</strong><br>
            Make sure to check your spam or junk folder. Verification links expire after 24 hours.
        </div>
    </div>
</body>
</html>
