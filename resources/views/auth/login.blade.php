<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        <style>
            * {
                margin: 0;
                padding: 0;
                box-sizing: border-box;
            }

            body {
                font-family: 'Instrument Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
                background-color: #ffffff;
                color: #000000;
                line-height: 1.5;
            }

            .container {
                display: flex;
                align-items: center;
                justify-content: center;
                min-height: 100vh;
                padding: 20px;
            }

            .form-wrapper {
                width: 100%;
                max-width: 400px;
            }

            .header {
                margin-bottom: 32px;
                text-align: center;
            }

            .header h1 {
                font-size: 28px;
                font-weight: 600;
                color: #000000;
                margin-bottom: 8px;
            }

            .header p {
                font-size: 14px;
                color: #666666;
            }

            form {
                display: flex;
                flex-direction: column;
                gap: 20px;
            }

            .form-group {
                display: flex;
                flex-direction: column;
            }

            label {
                font-size: 13px;
                font-weight: 500;
                color: #000000;
                margin-bottom: 8px;
                display: block;
            }

            input[type="email"],
            input[type="password"] {
                width: 100%;
                padding: 10px 12px;
                border: 1px solid #cccccc;
                border-radius: 4px;
                background-color: #ffffff;
                color: #000000;
                font-size: 14px;
                font-family: inherit;
                transition: border-color 0.2s, box-shadow 0.2s;
            }

            input[type="email"]:focus,
            input[type="password"]:focus {
                outline: none;
                border-color: #666666;
                box-shadow: 0 0 0 2px rgba(0, 0, 0, 0.05);
            }

            input.error {
                border-color: #dc2626;
            }

            input::placeholder {
                color: #999999;
            }

            .error-message {
                font-size: 12px;
                color: #dc2626;
                margin-top: 4px;
            }

            button[type="submit"] {
                background-color: #000000;
                color: #ffffff;
                border: none;
                padding: 10px 16px;
                border-radius: 4px;
                font-size: 14px;
                font-weight: 500;
                cursor: pointer;
                margin-top: 12px;
                font-family: inherit;
                transition: background-color 0.2s;
            }

            button[type="submit"]:hover {
                background-color: #333333;
            }

            button[type="submit"]:active {
                background-color: #1a1a1a;
            }

            .footer {
                margin-top: 24px;
                text-align: center;
                font-size: 13px;
                color: #666666;
            }

            .footer a {
                color: #000000;
                text-decoration: none;
                font-weight: 500;
                transition: opacity 0.2s;
            }

            .footer a:hover {
                opacity: 0.7;
                text-decoration: underline;
            }
        </style>
    </head>
    <body>
        <div class="container">
            <div class="form-wrapper">
                <!-- Header -->
                <div class="header">
                    <h1>Welcome Back</h1>
                    <p>Log in to your account</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- Email Field -->
                    <div class="form-group">
                        <label for="email">Email Address</label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="Enter your email"
                            @error('email') class="error" @enderror
                            autofocus
                        >
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 8px;">
                            <label for="password">Password</label>
                            <a href="{{ route('password.request') }}" style="font-size: 12px; color: #666666; text-decoration: none; font-weight: 500;">Forgot?</a>
                        </div>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Enter your password"
                            @error('password') class="error" @enderror
                        >
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button type="submit">Log In</button>
                </form>

                <!-- Register Link -->
                <div class="footer">
                    Don't have an account?
                    <a href="{{ route('register') }}">Create one</a>
                </div>
            </div>
        </div>
    </body>
</html>
