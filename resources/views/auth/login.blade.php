<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Login - {{ config('app.name', 'Laravel') }}</title>
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @else
            <style>
                /*! tailwindcss v4.0.7 | MIT License | https://tailwindcss.com */
                @layer theme{:root,:host{--font-sans:'Instrument Sans',ui-sans-serif,system-ui,sans-serif,"Apple Color Emoji","Segoe UI Emoji","Segoe UI Symbol","Noto Color Emoji";--font-serif:ui-serif,Georgia,Cambria,"Times New Roman",Times,serif;--font-mono:ui-monospace,SFMono-Regular,Menlo,Monaco,Consolas,"Liberation Mono","Courier New",monospace;}}
                @import 'tailwindcss';
            </style>
        @endif
    </head>
    <body class="bg-white text-black font-sans">
        <div class="min-h-screen flex items-center justify-center px-4 py-6">
            <div class="w-full max-w-md">
                <!-- Header -->
                <div class="mb-8">
                    <h1 class="text-3xl font-semibold text-black mb-2">Welcome Back</h1>
                    <p class="text-gray-600">Log in to your account</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Field -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-black mb-2">
                            Email Address
                        </label>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            value="{{ old('email') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded bg-white text-black focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-400 @error('email') border-red-500 @enderror"
                            placeholder="Enter your email"
                            autofocus
                        >
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Field -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-black mb-2">
                            Password
                        </label>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="w-full px-4 py-2 border border-gray-300 rounded bg-white text-black focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-400 @error('password') border-red-500 @enderror"
                            placeholder="Enter your password"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-black text-white py-2 px-4 rounded font-medium hover:bg-gray-900 transition-colors duration-200 mt-6"
                    >
                        Log In
                    </button>
                </form>

                <!-- Register Link -->
                <p class="mt-6 text-center text-gray-600">
                    Don&apos;t have an account?
                    <a href="{{ route('register') }}" class="text-black font-medium hover:underline">
                        Create one
                    </a>
                </p>
            </div>
        </div>
    </body>
</html>
