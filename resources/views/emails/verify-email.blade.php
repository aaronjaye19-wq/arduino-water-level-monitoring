<!DOCTYPE html>
<html>
<head>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { max-width: 600px; margin: 20px auto; background-color: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.1); }
        h2 { color: #333; }
        p { color: #666; line-height: 1.6; }
        .button { display: inline-block; margin-top: 20px; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 5px; }
        .button:hover { background-color: #0056b3; }
        .footer { margin-top: 30px; padding-top: 20px; border-top: 1px solid #eee; font-size: 12px; color: #999; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Hello {{ $user->name }},</h2>
        <p>Thank you for registering! Please verify your email address by clicking the button below.</p>
        <a href="{{ $verificationUrl }}" class="button">Verify Email</a>
        <p>Or copy and paste this link in your browser:</p>
        <p><small>{{ $verificationUrl }}</small></p>
        <div class="footer">
            <p>This link will expire in 24 hours.</p>
            <p>If you did not create this account, please ignore this email.</p>
        </div>
    </div>
</body>
</html>
