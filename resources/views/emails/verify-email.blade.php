<div style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto;">
    <div style="background-color: #f8f9fa; padding: 20px; border-radius: 8px; margin-bottom: 20px;">
        <h1 style="color: #1e40af; margin: 0; font-size: 28px;">Water Level Monitor</h1>
        <p style="color: #666; margin: 5px 0 0 0;">Email Verification</p>
    </div>

    <div style="margin-bottom: 20px;">
        <p>Hello <strong>{{ $user->name }}</strong>,</p>
        
        <p>Thank you for registering with Arduino Water Level Monitoring. To complete your registration and access your dashboard, please verify your email address by clicking the button below.</p>

        <p style="text-align: center; margin: 30px 0;">
            <a 
                href="{{ $verificationUrl }}" 
                style="display: inline-block; background-color: #2563eb; color: white; padding: 12px 30px; text-decoration: none; border-radius: 6px; font-weight: bold; font-size: 16px;"
            >
                Verify Email & Access Dashboard
            </a>
        </p>

        <p style="color: #666; font-size: 14px;">
            Or copy and paste this link in your browser:<br>
            <a href="{{ $verificationUrl }}" style="color: #2563eb; word-break: break-all;">{{ $verificationUrl }}</a>
        </p>

        <hr style="border: none; border-top: 1px solid #ddd; margin: 20px 0;">

        <p style="color: #999; font-size: 12px;">
            <strong>Security Notice:</strong> This link will expire in 24 hours. If you did not request this email or if you need a new verification link, please ignore this message and register again.
        </p>
    </div>

    <div style="background-color: #f8f9fa; padding: 15px; border-radius: 6px; text-align: center; color: #666; font-size: 12px;">
        <p style="margin: 0;">Arduino Water Level Monitoring System</p>
        <p style="margin: 5px 0 0 0;">&copy; {{ date('Y') }} All rights reserved.</p>
    </div>
</div>
