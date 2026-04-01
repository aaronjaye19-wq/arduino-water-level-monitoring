<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - {{ config('app.name', 'Laravel') }}</title>
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

            input[type="text"],
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

            input[type="text"]:focus,
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
                    <h1>Create Account</h1>
                    <p>Join us to get started</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name Field -->
                    <div class="form-group">
                        <label for="name">Full Name</label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Enter your full name"
                            @error('name') class="error" @enderror
                        >
                        @error('name')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

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
                        >
                        @error('email')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div class="form-group">
                        <label for="password">Password</label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            placeholder="Minimum 8 characters"
                            @error('password') class="error" @enderror
                        >
                        @error('password')
                            <p class="error-message">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="form-group">
                        <label for="password_confirmation">Confirm Password</label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            placeholder="Re-enter your password"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button type="submit">Create Account</button>
                </form>

                <!-- Login Link -->
                <div class="footer">
                    Already have an account?
                    <a href="{{ route('login') }}">Log in</a>
                </div>
            </div>
        </div>
    </body>
</html>
