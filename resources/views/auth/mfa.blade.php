<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MFA Challenge - OviTech</title>
    @vite(['resources/css/app.css'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>body { background: #0f172a; color: white; }</style>
</head>
<body class="min-h-screen flex items-center justify-center">
    <div class="w-full max-w-md p-8 bg-slate-800/50 rounded-3xl border border-white/10 text-center">
        <svg class="w-16 h-16 text-emerald-500 mx-auto mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 3.517-1.009 6.799-2.753 9.571m-3.44-2.04l.054-.09A13.916 13.916 0 008 11a4 4 0 118 0c0 1.017-.07 2.019-.203 3m-2.118 6.844A21.88 21.88 0 0015.171 17m3.839 1.132c.645-2.266.99-4.659.99-7.132A8 8 0 008 4.07M3 15.364c.64-1.319 1-2.8 1-4.364 0-1.457.39-2.823 1.07-4"></path></svg>
        <h2 class="text-2xl font-bold mb-2">Two-Factor Authentication</h2>
        <p class="text-slate-400 text-sm mb-8">Enter the 6-digit code from your authenticator app.</p>
        
        <form method="POST" action="{{ route('mfa.verify') }}">
            @csrf
            <input type="text" name="code" placeholder="000000" maxlength="6" required class="w-full text-center text-3xl tracking-widest px-4 py-4 rounded-xl bg-slate-900 border border-slate-700 focus:border-emerald-500 focus:outline-none mb-6">
            <button type="submit" class="w-full py-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 font-semibold text-white">Verify Code</button>
        </form>
    </div>
</body>
</html>
