<!DOCTYPE html>
<html lang="en" class="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Enterprise Login - OviTech Smart Farm</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <!-- Inter Font -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- AlpineJS -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <!-- Lucide Icons -->
    <script src="https://unpkg.com/lucide@latest"></script>
    <style>
        body { 
            font-family: 'Inter', sans-serif; 
            background-color: #030712; /* Deep space gray */
            color: #f8fafc;
        }
        
        /* Animated Orbs */
        .orb-1 {
            background: radial-gradient(circle, rgba(16,185,129,0.3) 0%, rgba(16,185,129,0) 70%);
            animation: float-1 15s ease-in-out infinite alternate;
        }
        .orb-2 {
            background: radial-gradient(circle, rgba(59,130,246,0.3) 0%, rgba(59,130,246,0) 70%);
            animation: float-2 18s ease-in-out infinite alternate-reverse;
        }
        .orb-3 {
            background: radial-gradient(circle, rgba(139,92,246,0.2) 0%, rgba(139,92,246,0) 70%);
            animation: float-3 20s ease-in-out infinite alternate;
        }

        @keyframes float-1 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, 150px) scale(1.2); }
        }
        @keyframes float-2 {
            0% { transform: translate(0, 0) scale(1.2); }
            100% { transform: translate(-150px, 100px) scale(1); }
        }
        @keyframes float-3 {
            0% { transform: translate(0, 0) scale(1); }
            100% { transform: translate(100px, -150px) scale(1.3); }
        }

        /* Glassmorphism Input */
        .glass-input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-input:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(16, 185, 129, 0.5); /* Emerald border */
            box-shadow: 0 0 0 4px rgba(16, 185, 129, 0.1);
        }

        /* WebKit Autofill Fix */
        input:-webkit-autofill,
        input:-webkit-autofill:hover, 
        input:-webkit-autofill:focus, 
        input:-webkit-autofill:active{
            -webkit-box-shadow: 0 0 0 30px #0f172a inset !important;
            -webkit-text-fill-color: white !important;
            transition: background-color 5000s ease-in-out 0s;
        }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 overflow-hidden relative selection:bg-emerald-500/30 selection:text-emerald-200" x-data="loginForm()">

    <!-- Animated Background Elements -->
    <div class="fixed inset-0 overflow-hidden pointer-events-none z-0">
        <div class="absolute -top-[20%] -left-[10%] w-[50vw] h-[50vw] rounded-full orb-1 blur-3xl"></div>
        <div class="absolute top-[30%] -right-[10%] w-[45vw] h-[45vw] rounded-full orb-2 blur-3xl"></div>
        <div class="absolute -bottom-[20%] left-[20%] w-[60vw] h-[60vw] rounded-full orb-3 blur-3xl"></div>
        
        <!-- Removed noise texture overlay -->
    </div>

    <!-- Login Container -->
    <div class="w-full relative z-10 perspective-1000" style="max-width: 380px; margin: 0 auto;">
        
        <!-- Glass Card -->
        <div class="bg-slate-900/40 backdrop-blur-2xl rounded-3xl p-6 sm:p-8 border border-white/10 shadow-2xl relative overflow-hidden transform transition-all duration-500 hover:shadow-emerald-500/20" style="background-color: rgba(15, 23, 42, 0.4); border-radius: 2.5rem;">
            
            <!-- Card Highlight/Reflection -->
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-emerald-500/50 to-transparent opacity-50"></div>
            <div class="absolute -top-40 -right-40 w-80 h-80 bg-white/5 rounded-full blur-3xl pointer-events-none"></div>

            <!-- Loading Overlay -->
            <div x-show="loading" 
                 x-transition.opacity.duration.300ms
                 class="absolute inset-0 bg-slate-900/80 backdrop-blur-md z-50 flex flex-col items-center justify-center rounded-3xl" style="background-color: rgba(15, 23, 42, 0.8); border-radius: 2.5rem;">
                <i data-lucide="loader-2" class="w-12 h-12 text-emerald-500 animate-spin mb-4"></i>
                <p class="text-white text-sm font-semibold tracking-wide animate-pulse">Authenticating securely...</p>
            </div>

            <!-- Header -->
            <div class="text-center mb-8 relative z-10">
                <div class="inline-flex items-center justify-center w-14 h-14 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 mb-4 shadow-[0_0_20px_rgba(16,185,129,0.4)] border border-emerald-300/30">
                    <i data-lucide="shield-check" class="w-8 h-8 text-white"></i>
                </div>
                <h1 class="text-3xl font-black text-white tracking-tight mb-2">OviTech Portal</h1>
                <p class="text-slate-400 text-sm font-medium">Enterprise Farm Management System</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 rounded-2xl bg-red-500/10 border border-red-500/20 backdrop-blur-sm relative z-10">
                    <div class="flex items-start gap-3">
                        <i data-lucide="alert-circle" class="w-5 h-5 text-red-400 flex-shrink-0 mt-0.5"></i>
                        <ul class="text-red-400 text-sm font-medium space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('login.post') }}" @submit="submitForm" class="relative z-10 space-y-5">
                @csrf
                
                <!-- Email Field -->
                <div class="space-y-1.5">
                    <label for="email" class="text-xs font-bold text-slate-300 uppercase tracking-widest pl-1">Enterprise Email</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="mail" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input type="email" 
                               id="email" 
                               name="email" 
                               required 
                               value="{{ old('email', 'admin@ovitech.com') }}"
                               class="glass-input w-full pl-11 pr-12 py-3 rounded-full text-white font-medium placeholder-slate-500 focus:outline-none"
                               placeholder="admin@ovitech.com"
                               x-model="email"
                               @input="validateEmail">
                        
                        <!-- Validation Icon -->
                        <div class="absolute inset-y-0 right-0 pr-4 flex items-center" x-show="isEmailValid" x-transition>
                            <i data-lucide="check" class="w-5 h-5 text-emerald-400"></i>
                        </div>
                    </div>
                </div>

                <!-- Password Field -->
                <div class="space-y-1.5">
                    <label for="password" class="text-xs font-bold text-slate-300 uppercase tracking-widest pl-1">Security Key</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <i data-lucide="lock" class="w-5 h-5 text-slate-500"></i>
                        </div>
                        <input :type="showPassword ? 'text' : 'password'" 
                               id="password" 
                               name="password" 
                               required
                               value="password"
                               class="glass-input w-full pl-11 pr-12 py-3 rounded-full text-white font-medium placeholder-slate-500 focus:outline-none tracking-wider"
                               placeholder="••••••••"
                               x-model="password"
                               @keyup="checkCapsLock">
                        
                        <!-- Toggle Visibility -->
                        <button type="button" 
                                @click="showPassword = !showPassword" 
                                class="absolute inset-y-0 right-0 pr-4 flex items-center text-slate-500 hover:text-white transition-colors focus:outline-none">
                            <i data-lucide="eye" x-show="!showPassword" class="w-5 h-5"></i>
                            <i data-lucide="eye-off" x-show="showPassword" x-cloak class="w-5 h-5"></i>
                        </button>
                    </div>
                </div>

                <!-- Caps Lock Warning -->
                <div x-show="capsLockOn" x-collapse x-cloak>
                    <p class="text-amber-400 text-xs mt-2 flex items-center font-medium bg-amber-500/10 border border-amber-500/20 px-3 py-2 rounded-lg">
                        <i data-lucide="keyboard" class="w-4 h-4 mr-2"></i>
                        Caps Lock is currently ON
                    </p>
                </div>

                <!-- Options -->
                <div class="flex items-center justify-between pt-2">
                    <label class="flex items-center cursor-pointer group">
                        <div class="relative flex items-center justify-center w-5 h-5 rounded-md border border-slate-600 bg-black/30 group-hover:border-emerald-500 transition-colors">
                            <input type="checkbox" name="remember" class="sr-only" x-model="remember">
                            <i data-lucide="check" class="w-3.5 h-3.5 text-emerald-500 opacity-0 transition-opacity" :class="{'opacity-100': remember}"></i>
                        </div>
                        <span class="ml-3 text-sm font-medium text-slate-400 group-hover:text-slate-300 transition-colors">Remember device</span>
                    </label>
                    <a href="{{ route('password.request') }}" class="text-sm font-semibold text-emerald-400 hover:text-emerald-300 transition-colors">Recover Access</a>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="submit" 
                            class="w-full py-3.5 rounded-full font-bold text-white bg-gradient-to-r from-emerald-500 to-teal-600 hover:from-emerald-400 hover:to-teal-500 focus:outline-none focus:ring-2 focus:ring-emerald-500/50 transform transition-all active:scale-[0.98] shadow-[0_0_20px_rgba(16,185,129,0.3)] hover:shadow-[0_0_30px_rgba(16,185,129,0.5)] flex items-center justify-center gap-2 group border border-emerald-400/30">
                        <span class="tracking-wide">Authenticate</span>
                        <i data-lucide="arrow-right" class="w-5 h-5 group-hover:translate-x-1 transition-transform"></i>
                    </button>
                </div>

                <!-- SSO Options -->
                <div class="mt-8 pt-6 border-t border-white/5 text-center">
                    <p class="text-[11px] font-bold text-slate-500 uppercase tracking-widest mb-4">Or authenticate with</p>
                    <div class="flex justify-center gap-4">
                        <button type="button" class="w-12 h-12 rounded-full bg-black/20 border border-white/5 hover:bg-white/5 hover:border-white/10 transition-all flex items-center justify-center group text-slate-400 hover:text-emerald-400 shadow-inner" title="WebAuthn / Passkey">
                            <i data-lucide="fingerprint" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        </button>
                        <button type="button" class="w-12 h-12 rounded-full bg-black/20 border border-white/5 hover:bg-white/5 hover:border-white/10 transition-all flex items-center justify-center group text-slate-400 hover:text-blue-400 shadow-inner" title="Microsoft Entra ID">
                            <i data-lucide="layout-grid" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
                        </button>
                    </div>
                </div>

            </form>
        </div>
        
        <p class="text-center text-slate-500 text-xs mt-8 font-medium">
            &copy; {{ date('Y') }} OviTech Solutions. Enterprise Security Active.
        </p>
    </div>

    <script>
        // Initialize Lucide Icons
        document.addEventListener('DOMContentLoaded', () => {
            if (typeof lucide !== 'undefined') lucide.createIcons();
        });

        function loginForm() {
            return {
                email: '{{ old('email', 'admin@ovitech.com') }}',
                password: 'password',
                showPassword: false,
                remember: false,
                loading: false,
                isEmailValid: true,
                capsLockOn: false,

                validateEmail() {
                    const re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                    this.isEmailValid = re.test(this.email);
                },

                checkCapsLock(e) {
                    if (e.getModifierState) {
                        this.capsLockOn = e.getModifierState('CapsLock');
                    }
                },

                submitForm(e) {
                    if(!this.email || !this.password) return;
                    this.loading = true;
                }
            }
        }
    </script>
</body>
</html>
