<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Registration - OviTech Smart Farm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #0f172a; color: white; }
        .glass-panel { background: rgba(255, 255, 255, 0.03); backdrop-filter: blur(16px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .floating-input { background: rgba(15, 23, 42, 0.4); border: 1px solid rgba(255, 255, 255, 0.1); color: white; transition: all 0.3s; }
        .floating-input:focus { border-color: #0ea5e9; box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.1); outline: none; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">
    <div class="w-full max-w-2xl glass-panel rounded-3xl p-8" x-data="{ step: 1 }">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Create Enterprise Account</h1>
            <p class="text-slate-400">Join OviTech Smart Farm</p>
        </div>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf
            
            <!-- Progress Bar -->
            <div class="flex gap-2 mb-8">
                <div class="h-2 flex-1 rounded bg-sky-500 transition-all duration-500"></div>
                <div class="h-2 flex-1 rounded transition-all duration-500" :class="step >= 2 ? 'bg-sky-500' : 'bg-slate-700'"></div>
                <div class="h-2 flex-1 rounded transition-all duration-500" :class="step >= 3 ? 'bg-sky-500' : 'bg-slate-700'"></div>
            </div>

            <!-- Step 1: Personal Info -->
            <div x-show="step === 1" x-transition>
                <h2 class="text-xl font-semibold mb-4 text-sky-400">1. Personal Information</h2>
                <input type="text" name="name" placeholder="Full Name" required class="floating-input w-full px-4 py-4 rounded-xl mb-4">
                <input type="email" name="email" placeholder="Email Address" required class="floating-input w-full px-4 py-4 rounded-xl mb-4">
                <button type="button" @click="step = 2" class="w-full py-4 rounded-xl bg-sky-500 hover:bg-sky-400 font-semibold mt-4">Next Step</button>
            </div>

            <!-- Step 2: Farm Info -->
            <div x-show="step === 2" x-transition x-cloak>
                <h2 class="text-xl font-semibold mb-4 text-emerald-400">2. Farm Details</h2>
                <input type="text" name="farm_name" placeholder="Farm Name" required class="floating-input w-full px-4 py-4 rounded-xl mb-4">
                <div class="flex gap-4">
                    <button type="button" @click="step = 1" class="w-1/3 py-4 rounded-xl bg-slate-700 hover:bg-slate-600 font-semibold mt-4">Back</button>
                    <button type="button" @click="step = 3" class="w-2/3 py-4 rounded-xl bg-emerald-500 hover:bg-emerald-400 font-semibold mt-4">Next Step</button>
                </div>
            </div>

            <!-- Step 3: Security -->
            <div x-show="step === 3" x-transition x-cloak>
                <h2 class="text-xl font-semibold mb-4 text-indigo-400">3. Security</h2>
                <input type="password" name="password" placeholder="Password" required class="floating-input w-full px-4 py-4 rounded-xl mb-4">
                <input type="password" name="password_confirmation" placeholder="Confirm Password" required class="floating-input w-full px-4 py-4 rounded-xl mb-4">
                
                <label class="flex items-center mb-6 mt-4">
                    <input type="checkbox" name="terms" required class="w-5 h-5 rounded border-slate-600 bg-slate-800 text-sky-500">
                    <span class="ml-3 text-sm text-slate-400">I accept the Enterprise Terms of Service and Privacy Policy</span>
                </label>

                <div class="flex gap-4">
                    <button type="button" @click="step = 2" class="w-1/3 py-4 rounded-xl bg-slate-700 hover:bg-slate-600 font-semibold">Back</button>
                    <button type="submit" class="w-2/3 py-4 rounded-xl bg-gradient-to-r from-indigo-500 to-sky-500 hover:from-indigo-400 hover:to-sky-400 font-semibold shadow-lg shadow-indigo-500/25">Complete Registration</button>
                </div>
            </div>
            
            <div class="mt-8 text-center">
                <a href="{{ route('login') }}" class="text-sm text-slate-400 hover:text-white transition-colors">Already have an account? Login</a>
            </div>
        </form>
    </div>
</body>
</html>
