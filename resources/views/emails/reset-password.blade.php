<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            background-color: #f5f5f5;
            line-height: 1.6;
            color: #333;
        }
        .wrapper {
            background-color: #f5f5f5;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: #000;
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-bottom: 1px solid #e5e5e5;
        }
        .header h1 {
            font-size: 24px;
            font-weight: 600;
            margin: 0;
            letter-spacing: -0.5px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 16px;
            margin-bottom: 20px;
            color: #000;
            font-weight: 500;
        }
        .message {
            font-size: 15px;
            line-height: 1.8;
            margin-bottom: 30px;
            color: #666;
        }
        .button-wrapper {
            text-align: center;
            margin: 30px 0;
        }
        .button {
            display: inline-block;
            padding: 14px 40px;
            background: #000;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.2s;
            border: none;
            cursor: pointer;
            text-align: center;
        }
        .button:hover {
            background: #222;
        }
        .link-section {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e5e5e5;
        }
        .link-label {
            font-size: 13px;
            color: #999;
            margin-bottom: 10px;
            display: block;
        }
        .link-text {
            font-size: 13px;
            word-break: break-all;
            background-color: #f5f5f5;
            padding: 12px;
            border-radius: 6px;
            border-left: 3px solid #000;
            color: #333;
        }
        .link-text a {
            color: #000;
            text-decoration: none;
        }
        .footer {
            background-color: #fafafa;
            padding: 20px 30px;
            border-top: 1px solid #e5e5e5;
            text-align: center;
        }
        .footer-text {
            font-size: 12px;
            color: #999;
            margin: 5px 0;
            line-height: 1.6;
        }
        .warning {
            background-color: #f5f5f5;
            border-left: 4px solid #000;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 13px;
            color: #333;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container">
            <!-- Header -->
            <div class="header">
                <h1>Password Reset</h1>
            </div>

            <!-- Main Content -->
            <div class="content">
                <p class="greeting">Hello <strong>{{ $user->name }}</strong>,</p>

                <p class="message">
                    We received a request to reset your password for your account. Click the button below to create a new password.
                </p>

                <!-- Button -->
                <div class="button-wrapper">
                    <a href="{{ $resetUrl }}" class="button">Reset Password</a>
                </div>

                <!-- Backup Link -->
                <div class="link-section">
                    <span class="link-label">Or copy and paste this link in your browser:</span>
                    <div class="link-text">
                        <a href="{{ $resetUrl }}">{{ $resetUrl }}</a>
                    </div>
                </div>

                <!-- Warning -->
                <div class="warning">
                    <strong>Important:</strong> This password reset link will expire in <strong>10 minutes</strong>. If you did not request this email, please ignore it and your password will remain unchanged.
                </div>
            </div>

            <!-- Footer -->
            <div class="footer">
                <p class="footer-text">
                    &copy; 2024 Water Level Monitoring System. All rights reserved.
                </p>
                <p class="footer-text">
                    This is an automated message, please do not reply to this email.
                </p>
            </div>
        </div>
    </div>
</body>
</html>
