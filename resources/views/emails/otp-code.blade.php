<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f5f5;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
            font-weight: 600;
        }
        .content {
            padding: 30px;
        }
        .content p {
            font-size: 16px;
            color: #333;
            line-height: 1.6;
            margin: 15px 0;
        }
        .otp-box {
            background-color: #f9f9f9;
            border: 2px solid #667eea;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 30px 0;
        }
        .otp-code {
            font-size: 36px;
            font-weight: bold;
            color: #667eea;
            letter-spacing: 5px;
            font-family: 'Courier New', monospace;
        }
        .expiry {
            color: #ff6b6b;
            font-size: 14px;
            font-weight: 600;
            margin-top: 15px;
        }
        .footer {
            background-color: #f5f5f5;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #ddd;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #667eea;
            color: white;
            padding: 12px 30px;
            border-radius: 5px;
            text-decoration: none;
            margin-top: 20px;
            font-weight: 600;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Email Verification</h1>
        </div>
        
        <div class="content">
            <p>Hello <strong>{{ $user->name }}</strong>,</p>
            
            <p>Thank you for registering with Arduino Water Level Monitoring System. To complete your registration and verify your email address, please use the OTP code below:</p>
            
            <div class="otp-box">
                <div class="otp-code">{{ $otp }}</div>
                <div class="expiry">This code will expire in 15 minutes</div>
            </div>
            
            <p>If you did not create this account, please ignore this email.</p>
            
            <p>For security reasons, never share this code with anyone. Our support team will never ask for your OTP code.</p>
            
            <p>Best regards,<br><strong>Arduino Water Level Monitoring Team</strong></p>
        </div>
        
        <div class="footer">
            <p>This is an automated email. Please do not reply to this message.</p>
            <p>&copy; 2025 Arduino Water Level Monitoring. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
