<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify Email</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <svg class="w-16 h-16 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                </svg>

                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Email</h1>
                <p class="text-gray-600 mb-4">
                    A verification link has been sent to {{ $user->email }}
                </p>

                @if (session('error'))
                    <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg text-red-700 text-sm">
                        {{ session('error') }}
                    </div>
                @endif

                @if (session('success'))
                    <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg text-green-700 text-sm">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="space-y-4 mt-6">
                    <p class="text-sm text-gray-600">
                        Please check your email for a verification link. Click on the link to verify your email address.
                    </p>

                    <p class="text-xs text-gray-500">
                        The link will expire in 24 hours.
                    </p>
                </div>

                <div class="mt-8">
                    <a
                        href="{{ route('auth.login') }}"
                        class="inline-block bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-6 rounded-lg transition"
                    >
                        Go to Login
                    </a>
                </div>

                <p class="mt-6 text-xs text-gray-500">
                    Didn&apos;t receive an email?
                    <a href="{{ route('auth.register') }}" class="text-blue-600 hover:text-blue-700 font-medium">
                        Try Again
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>
</html>
