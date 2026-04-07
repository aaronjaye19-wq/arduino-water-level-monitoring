<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Setup Two-Factor Authentication</title>
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
            max-width: 500px;
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
        .step {
            margin-bottom: 30px;
        }
        .step-title {
            color: #000;
            font-weight: 600;
            margin-bottom: 12px;
            font-size: 15px;
        }
        .step-content {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 6px;
            border-left: 3px solid #000;
        }
        .step-content p {
            color: #666;
            font-size: 13px;
            line-height: 1.6;
            margin-bottom: 10px;
        }
        .step-content p:last-child {
            margin-bottom: 0;
        }
        .qr-code {
            text-align: center;
            margin: 15px 0;
            padding: 15px;
            background: white;
            border-radius: 6px;
        }
        .qr-code img {
            max-width: 100%;
            border: 1px solid #e5e5e5;
        }
        .secret-display {
            background: white;
            padding: 12px;
            border-radius: 6px;
            font-family: 'Courier New', monospace;
            font-size: 14px;
            word-break: break-all;
            border: 1px solid #e5e5e5;
            margin: 10px 0;
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
            font-size: 16px;
            letter-spacing: 0.1em;
            background: #fafafa;
            transition: all 0.2s;
            text-align: center;
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
        }
        button:hover {
            background-color: #222;
        }
        .warning {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 12px;
            border-radius: 6px;
            margin-top: 20px;
            font-size: 13px;
            color: #856404;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Setup Two-Factor Authentication</h1>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="step">
            <div class="step-title">Step 1: Download an Authenticator App</div>
            <div class="step-content">
                <p>Download one of these authenticator apps on your smartphone:</p>
                <p>• Google Authenticator (iOS/Android)</p>
                <p>• Microsoft Authenticator (iOS/Android)</p>
                <p>• Authy (iOS/Android)</p>
            </div>
        </div>

        <div class="step">
            <div class="step-title">Step 2: Scan QR Code or Enter Secret</div>
            <div class="step-content">
                <p>Open your authenticator app and scan this QR code:</p>
                <div class="secret-display">{{ $secret }}</div>
                <p style="font-size: 12px; margin-top: 10px;">If you can't scan, manually enter this secret key in your authenticator app.</p>
            </div>
        </div>

        <form method="POST" action="{{ route('mfa.setup.submit') }}">
            @csrf
            <input type="hidden" name="secret" value="{{ $secret }}">

            <div class="step">
                <div class="step-title">Step 3: Verify the Code</div>
                <div class="step-content">
                    <p>Enter the 6-digit code from your authenticator app to verify:</p>
                    <div class="form-group" style="margin-top: 15px;">
                        <input 
                            type="text" 
                            id="code" 
                            name="code" 
                            placeholder="000000" 
                            maxlength="6" 
                            pattern="\d{6}"
                            inputmode="numeric"
                            required 
                            autofocus
                        >
                        @error('code')
                            <div class="error-message">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <button type="submit">Enable Two-Factor Authentication</button>
        </form>

        <div class="warning">
            <strong>Important:</strong> Save your secret key in a safe place. You'll need it to recover your account if you lose access to your authenticator app.
        </div>
    </div>
</body>
</html>
