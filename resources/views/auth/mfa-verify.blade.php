<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Factor Authentication</title>
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
            margin-bottom: 10px;
            text-align: center;
            font-size: 28px;
            font-weight: 600;
            letter-spacing: -0.5px;
        }
        .subtitle {
            color: #666;
            text-align: center;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.5;
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
            margin-top: 10px;
        }
        button:hover {
            background-color: #222;
        }
        button:active {
            transform: scale(0.99);
        }
        .info-box {
            background-color: #f5f5f5;
            border-left: 4px solid #000;
            padding: 12px;
            border-radius: 6px;
            margin-bottom: 20px;
            font-size: 13px;
            color: #666;
            line-height: 1.6;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>2FA Verification</h1>
        <p class="subtitle">Enter the 6-digit code from your authenticator app</p>

        @if ($errors->any())
            <ul class="errors">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif

        <div class="info-box">
            Open your authenticator app and enter the 6-digit code below to verify your identity.
        </div>

        <form method="POST" action="{{ route('mfa.verify.submit') }}">
            @csrf
            <div class="form-group">
                <label for="code">Verification Code</label>
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

            <button type="submit">Verify & Login</button>
        </form>
    </div>
</body>
</html>
