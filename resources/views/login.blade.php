<!DOCTYPE html>
<html lang="en" class="h-full bg-[#0b0e11]">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dynamic Receipt Mocking Engine</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Outfit', sans-serif;
        }
    </style>
</head>
<body class="h-full flex items-center justify-center px-4 sm:px-6 lg:px-8">
    <div class="max-w-md w-full space-y-8">
        <div>
            <!-- Header Brand -->
            <div class="flex justify-center">
                <div class="h-16 w-16 bg-gradient-to-tr from-[#FCD535] to-[#F3BA2F] rounded-2xl flex items-center justify-center shadow-lg shadow-yellow-500/20">
                    <svg class="h-9 w-9 text-[#0b0e11]" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                </div>
            </div>
            <h2 class="mt-6 text-center text-3xl font-extrabold text-white tracking-tight">
                Mocking Engine Workbench
            </h2>
            <p class="mt-2 text-center text-sm text-[#848e9c]">
                Sign in to customize, generate, and download dynamic slips
            </p>
        </div>

        <!-- Session Status / Errors -->
        @if ($errors->any())
            <div class="bg-red-500/10 border border-red-500/30 rounded-xl p-4 text-sm text-red-400">
                <div class="flex items-center space-x-2">
                    <svg class="h-5 w-5 text-red-400 flex-shrink-0" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                    <span class="font-medium">Authentication Failed</span>
                </div>
                <ul class="mt-2 list-disc list-inside space-y-1 text-xs opacity-90">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form class="mt-8 space-y-6" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="bg-[#181a20] border border-[#2b3139] rounded-2xl p-6 shadow-xl space-y-4">
                <div>
                    <label for="email" class="block text-xs font-semibold text-[#848e9c] uppercase tracking-wider">Email Address</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#848e9c]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.206" />
                            </svg>
                        </div>
                        <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email', 'admin@mockengine.com') }}"
                            class="block w-full pl-10 pr-3 py-3 border border-[#2b3139] rounded-xl bg-[#0b0e11] text-white placeholder-[#47525f] focus:outline-none focus:ring-2 focus:ring-yellow-500/20 focus:border-[#FCD535] transition duration-200 text-sm"
                            placeholder="name@company.com">
                    </div>
                </div>

                <div>
                    <label for="password" class="block text-xs font-semibold text-[#848e9c] uppercase tracking-wider">Password</label>
                    <div class="mt-1 relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-[#848e9c]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" autocomplete="current-password" required value="admin123"
                            class="block w-full pl-10 pr-3 py-3 border border-[#2b3139] rounded-xl bg-[#0b0e11] text-white placeholder-[#47525f] focus:outline-none focus:ring-2 focus:ring-yellow-500/20 focus:border-[#FCD535] transition duration-200 text-sm"
                            placeholder="••••••••">
                    </div>
                </div>

                <div class="flex items-center justify-between pt-2">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" checked
                            class="h-4 w-4 text-[#FCD535] focus:ring-0 rounded bg-[#0b0e11] border-[#2b3139] accent-[#FCD535]">
                        <label for="remember" class="ml-2 block text-sm text-[#848e9c] select-none">
                            Remember me
                        </label>
                    </div>
                    <div class="text-xs text-yellow-500 hover:text-yellow-400 font-medium cursor-pointer transition duration-150">
                        Forgot Password?
                    </div>
                </div>

                <div class="pt-4">
                    <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-xl text-sm font-bold text-[#0b0e11] bg-gradient-to-r from-[#FCD535] to-[#F3BA2F] hover:from-yellow-400 hover:to-yellow-500 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-yellow-500 transition duration-150 transform hover:-translate-y-0.5">
                        Log In
                    </button>
                </div>
            </div>
        </form>

        <div class="text-center text-xs text-[#47525f]">
            Demo Access: <span class="text-[#848e9c]">admin@mockengine.com</span> / <span class="text-[#848e9c]">admin123</span>
        </div>
    </div>
</body>
</html>
