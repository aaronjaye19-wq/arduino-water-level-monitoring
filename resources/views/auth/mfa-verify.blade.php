<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verify MFA Code</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex items-center justify-center min-h-screen px-4">
        <div class="w-full max-w-md bg-white rounded-lg shadow-md p-8">
            <div class="text-center">
                <svg class="w-12 h-12 mx-auto text-blue-600 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>

                <h1 class="text-2xl font-bold text-gray-900 mb-2">Verify Your Identity</h1>
                <p class="text-gray-600 mb-6">
                    Enter the 6-digit code sent to your email
                </p>
            </div>

            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                    <ul class="text-sm text-red-600 list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('auth.mfa-verify.store') }}" class="space-y-4">
                @csrf

                <div>
                    <label for="mfa_code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input
                        type="text"
                        id="mfa_code"
                        name="mfa_code"
                        placeholder="000000"
                        maxlength="6"
                        inputmode="numeric"
                        pattern="[0-9]{6}"
                        required
                        autofocus
                        class="w-full px-4 py-3 text-center text-2xl letter-spacing font-mono border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                    />
                    <p class="mt-2 text-xs text-gray-600">Enter the 6-digit code</p>
                </div>

                <button
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-lg transition"
                >
                    Verify Code
                </button>
            </form>

            <p class="mt-6 text-center text-xs text-gray-500">
                The code will expire in 5 minutes
            </p>

            <div class="mt-6 text-center">
                <a
                    href="{{ route('auth.login') }}"
                    class="text-sm text-blue-600 hover:text-blue-700 font-medium"
                >
                    Back to Login
                </a>
            </div>
        </div>
    </div>

    <script>
        const input = document.getElementById('mfa_code');
        input.addEventListener('input', function(e) {
            // Only allow numbers
            e.target.value = e.target.value.replace(/[^0-9]/g, '');
        });
    </script>
</body>
</html>
