<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Register - {{ config('app.name', 'Laravel') }}</title>
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
                    <h1 class="text-3xl font-semibold text-black mb-2">Create Account</h1>
                    <p class="text-gray-600">Join us to get started</p>
                </div>

                <!-- Form -->
                <form method="POST" action="{{ route('register') }}" class="space-y-5">
                    @csrf

                    <!-- Name Field -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-black mb-2">
                            Full Name
                        </label>
                        <input
                            type="text"
                            id="name"
                            name="name"
                            value="{{ old('name') }}"
                            class="w-full px-4 py-2 border border-gray-300 rounded bg-white text-black focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-400 @error('name') border-red-500 @enderror"
                            placeholder="Enter your full name"
                        >
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

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
                            placeholder="Minimum 8 characters"
                        >
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password Field -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-black mb-2">
                            Confirm Password
                        </label>
                        <input
                            type="password"
                            id="password_confirmation"
                            name="password_confirmation"
                            class="w-full px-4 py-2 border border-gray-300 rounded bg-white text-black focus:outline-none focus:border-gray-500 focus:ring-1 focus:ring-gray-400"
                            placeholder="Re-enter your password"
                        >
                    </div>

                    <!-- Submit Button -->
                    <button
                        type="submit"
                        class="w-full bg-black text-white py-2 px-4 rounded font-medium hover:bg-gray-900 transition-colors duration-200 mt-6"
                    >
                        Create Account
                    </button>
                </form>

                <!-- Login Link -->
                <p class="mt-6 text-center text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-black font-medium hover:underline">
                        Log in
                    </a>
                </p>
            </div>
        </div>
    </body>
</html>
