<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { color: #666; line-height: 1.6; }
        .button { display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #28a745; color: white; text-decoration: none; border-radius: 5px; }
        .button:hover { background-color: #218838; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->name }},</h2>
        <p>We received a request to reset your password. Click the button below to set a new password.</p>
        <a href="{{ $resetUrl }}" class="button">Reset Password</a>
        <p>Or copy and paste this link in your browser:</p>
        <p><small>{{ $resetUrl }}</small></p>
        <div class="footer">
            <p>This link will expire in 10 minutes.</p>
            <p>If you did not request this, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
