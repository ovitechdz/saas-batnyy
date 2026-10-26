@extends('layouts.app')
@section('title', __('Farm Settings'))

@section('content')
<div class="space-y-6" x-data="{ tab: 'farm', saving: false }">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="page-hero animate-fade-up">
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <nav class="breadcrumb mb-3">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> {{ __('Dashboard') }}
                    </a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-active text-white">{{ __('Settings') }}</span>
                </nav>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(16,185,129,0.2); border: 1px solid rgba(16,185,129,0.3);">
                        <i data-lucide="settings-2" class="w-5 h-5 text-emerald-400"></i>
                    </div>
                    <span class="text-emerald-400 text-xs font-bold uppercase tracking-widest">{{ __('Configuration') }}</span>
                </div>
                <h1 class="text-3xl font-black tracking-tight text-white">
                    {{ __('Farm') }} <span class="text-gradient-ai">{{ __('Settings') }}</span>
                </h1>
                <p class="text-slate-400 mt-1.5 text-sm">{{ __('Manage your farm\'s global configuration, preferences, and integrations.') }}</p>
            </div>
        </div>
    </div>

    {{-- ═══ TAB NAVIGATION ═══ --}}
    <div class="flex items-center gap-1 p-1 rounded-xl bg-slate-100/80 dark:bg-white/[0.04] border border-slate-200/60 dark:border-white/[0.05] w-fit animate-fade-up delay-75">
        @foreach(['farm' => ['icon'=>'building-2','label'=>__('Farm Info')], 'profile' => ['icon'=>'user-circle','label'=>__('Profile')], 'preferences' => ['icon'=>'sliders-horizontal','label'=>__('Preferences')]] as $key => $item)
        <button @click="tab='{{ $key }}'"
                :class="tab==='{{ $key }}' ? 'bg-white dark:bg-slate-800 shadow-sm text-slate-900 dark:text-white' : 'text-slate-500 hover:text-slate-700 dark:hover:text-slate-300'"
                class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold transition-all duration-200">
            <i data-lucide="{{ $item['icon'] }}" class="w-4 h-4"></i>
            <span class="hidden sm:inline">{{ $item['label'] }}</span>
        </button>
        @endforeach
    </div>

    <form action="{{ route('settings.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6" @submit="saving=true">
        @csrf
        @method('PUT')

        {{-- ═══ FARM INFO TAB ═══ --}}
        <div x-show="tab==='farm'" class="grid grid-cols-1 lg:grid-cols-3 gap-6 animate-fade-up delay-100">

            {{-- Profile Photo Section --}}
            <div class="glass-panel p-6 flex flex-col items-center text-center"
                 x-data="{ photoPreview: null }">
                <div class="relative mb-4 cursor-pointer group" @click="$refs.photoInput.click()">
                    <img x-show="!photoPreview"
                         :src="photoPreview || '{{ auth()->user()->profile_photo_url ?? 'https://ui-avatars.com/api/?name=Farm&background=10b981&color=fff&size=128&bold=true' }}'"
                         class="w-28 h-28 rounded-2xl object-cover border-2 border-primary-500/30 shadow-lg">
                    <div x-show="photoPreview">
                        <img :src="photoPreview" class="w-28 h-28 rounded-2xl object-cover border-2 border-primary-500/50 shadow-xl">
                    </div>
                    <div class="absolute inset-0 bg-black/50 rounded-2xl flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                        <i data-lucide="camera" class="w-6 h-6 text-white"></i>
                    </div>
                </div>
                <input type="file" name="profile_photo" x-ref="photoInput" class="hidden" accept="image/*"
                       @change="const r=new FileReader();r.onload=e=>photoPreview=e.target.result;r.readAsDataURL($refs.photoInput.files[0])">
                <h3 class="font-bold text-slate-900 dark:text-white">{{ auth()->user()->name ?? 'Farm Admin' }}</h3>
                <p class="text-sm text-primary-500 font-medium">{{ auth()->user()->email }}</p>
                <button type="button" @click="$refs.photoInput.click()"
                        class="btn-secondary btn-sm mt-4 w-full justify-center">
                    <i data-lucide="upload" class="w-3.5 h-3.5"></i> {{ __('Change Photo') }}
                </button>
                <p class="text-xs text-slate-400 mt-2">{{ __('JPG, PNG or WEBP. Max 2MB.') }}</p>
                @error('profile_photo')
                    <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Farm Details --}}
            <div class="lg:col-span-2 glass-panel p-6">
                <h3 class="font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                    <i data-lucide="building-2" class="w-5 h-5 text-emerald-500"></i>
                    {{ __('Farm Information') }}
                </h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">

                    <div class="form-group">
                        <label class="label-field">{{ __('Farm Name') }} <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required
                               value="{{ old('name', $farm->name ?? '') }}"
                               class="input-field"
                               placeholder="{{ __('e.g. Green Valley Farm') }}">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="label-field">{{ __('Location / Region') }}</label>
                        <input type="text" name="location"
                               value="{{ old('location', $farm->location ?? '') }}"
                               class="input-field"
                               placeholder="{{ __('e.g. Algiers, Algeria') }}">
                        @error('location')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="label-field">{{ __('Currency') }}</label>
                        <select name="currency" class="select-field">
                            @foreach(['USD'=>'USD ($) — US Dollar','EUR'=>'EUR (€) — Euro','GBP'=>'GBP (£) — British Pound','DZD'=>'DZD (د.ج) — Algerian Dinar','SAR'=>'SAR (ر.س) — Saudi Riyal','AED'=>'AED (د.إ) — UAE Dirham','MAD'=>'MAD (DH) — Moroccan Dirham'] as $code => $label)
                            <option value="{{ $code }}" {{ old('currency', $farm->currency ?? 'USD') === $code ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                            @endforeach
                        </select>
                        @error('currency')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group">
                        <label class="label-field">{{ __('Weight Unit') }}</label>
                        <select name="weight_unit" class="select-field">
                            <option value="kg"  {{ old('weight_unit', $farm->weight_unit ?? 'kg') === 'kg'  ? 'selected' : '' }}>{{ __('Kilograms (kg)') }}</option>
                            <option value="lbs" {{ old('weight_unit', $farm->weight_unit ?? 'kg') === 'lbs' ? 'selected' : '' }}>{{ __('Pounds (lbs)') }}</option>
                        </select>
                        @error('weight_unit')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>

                    <div class="form-group sm:col-span-2">
                        <label class="label-field">{{ __('Farm Focus / Specialty') }}</label>
                        <select name="farm_focus" class="select-field">
                            <option value="">{{ __('Select specialty...') }}</option>
                            @foreach([__('Meat Production 🥩')=>__('Meat Production 🥩'),__('Dairy Production 🥛')=>__('Dairy Production 🥛'),__('Wool Production 🐑')=>__('Wool Production 🐑'),__('Breeding & Sales 📈')=>__('Breeding & Sales 📈'),__('Mixed Farming 🌿')=>__('Mixed Farming 🌿')] as $val => $label)
                            <option value="{{ $val }}" {{ old('farm_focus', $farm->farm_focus ?? '') === $val ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        @error('farm_focus')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
            </div>
        </div>

        {{-- ═══ PROFILE TAB ═══ --}}
        <div x-show="tab==='profile'" x-cloak class="glass-panel p-6 max-w-2xl animate-fade-up">
            <h3 class="font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <i data-lucide="user-circle" class="w-5 h-5 text-emerald-500"></i>
                {{ __('Account Profile') }}
            </h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div class="form-group">
                    <label class="label-field">{{ __('Full Name') }}</label>
                    <input type="text" name="user_name" class="input-field" value="{{ old('user_name', auth()->user()->name) }}" required>
                    @error('user_name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="label-field">{{ __('Email Address') }}</label>
                    <input type="email" name="user_email" class="input-field" value="{{ old('user_email', auth()->user()->email) }}" required>
                    @error('user_email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="label-field">{{ __('Phone Number') }}</label>
                    <input type="tel" name="user_phone" class="input-field" value="{{ old('user_phone', auth()->user()->phone ?? '') }}" placeholder="+213 XXX XXXXXX">
                    @error('user_phone')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                </div>
                <div class="form-group">
                    <label class="label-field">{{ __('Account Role') }}</label>
                    <input type="text" class="input-field" value="{{ auth()->user()->is_super_admin ? __('Super Administrator') : __('Farm Manager') }}" disabled>
                </div>
            </div>
        </div>

        {{-- ═══ PREFERENCES TAB ═══ --}}
        <div x-show="tab==='preferences'" x-cloak class="glass-panel p-6 max-w-2xl animate-fade-up">
            <h3 class="font-bold text-slate-900 dark:text-white mb-5 flex items-center gap-2">
                <i data-lucide="sliders-horizontal" class="w-5 h-5 text-emerald-500"></i>
                {{ __('Display Preferences') }}
            </h3>
            <div class="space-y-4">
                @foreach([
                    ['label'=>__('Dark Mode'),           'sub'=>__('Switch between light and dark theme'),        'key'=>'dark'],
                    ['label'=>__('Email Notifications'), 'sub'=>__('Receive health and stock alerts via email'),  'key'=>'email_notif'],
                    ['label'=>__('AI Insights'),         'sub'=>__('Enable AI-powered predictions and alerts'),   'key'=>'ai_insights'],
                ] as $pref)
                <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50/50 dark:bg-white/[0.03] border border-slate-200/50 dark:border-white/[0.04]">
                    <div>
                        <p class="text-sm font-semibold text-slate-800 dark:text-slate-200">{{ $pref['label'] }}</p>
                        <p class="text-xs text-slate-500 mt-0.5">{{ $pref['sub'] }}</p>
                    </div>
                    <div class="relative ml-4 flex-shrink-0" x-data="{ on: {{ (isset($farm->settings[$pref['key']]) && $farm->settings[$pref['key']]) ? 'true' : 'false' }} }">
                        <input type="hidden" name="{{ $pref['key'] }}" :value="on ? 1 : 0">
                        <button type="button" @click="on = !on"
                                :class="on ? 'bg-primary-500' : 'bg-slate-200 dark:bg-slate-700'"
                                class="w-11 h-6 rounded-full transition-colors duration-300 focus:outline-none focus-visible:ring-2 focus-visible:ring-primary-500">
                            <span :class="on ? 'translate-x-5' : 'translate-x-0.5'"
                                  class="block w-5 h-5 rounded-full bg-white shadow-md transition-transform duration-300"></span>
                        </button>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        {{-- Save Button --}}
        <div class="flex items-center justify-between pt-2 animate-fade-up delay-200">
            <p class="text-xs text-slate-400">{{ __('Changes are saved to your farm profile immediately.') }}</p>
            <button type="submit" class="btn-primary" :disabled="saving">
                <i data-lucide="save" class="w-4 h-4"></i>
                <span x-show="!saving">{{ __('Save Changes') }}</span>
                <span x-show="saving">{{ __('Saving...') }}</span>
            </button>
        </div>
    </form>
</div>
@endsection
