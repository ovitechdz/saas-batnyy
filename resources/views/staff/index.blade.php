@extends('layouts.app')
@section('title', 'Team Management')

@section('content')
<div class="space-y-6" x-data="staffManager()">

    {{-- ═══ PAGE HERO ═══ --}}
    <div class="page-hero animate-fade-up" style="background: linear-gradient(135deg, #0f172a 0%, #1a1010 50%, #0a1a10 100%);">
        <div class="absolute -top-16 -right-8 w-72 h-72 rounded-full opacity-15 blur-3xl pointer-events-none"
             style="background: radial-gradient(circle, #f97316, transparent 70%)"></div>
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-6">
            <div>
                <nav class="breadcrumb mb-3">
                    <a href="{{ route('dashboard') }}" class="breadcrumb-item flex items-center gap-1">
                        <i data-lucide="home" class="w-3 h-3"></i> Dashboard
                    </a>
                    <span class="breadcrumb-sep">/</span>
                    <span class="breadcrumb-active text-white">Employees</span>
                </nav>
                <div class="flex items-center gap-3 mb-1">
                    <div class="p-2 rounded-xl" style="background: rgba(249,115,22,0.2); border: 1px solid rgba(249,115,22,0.3);">
                        <i data-lucide="users" class="w-5 h-5 text-orange-400"></i>
                    </div>
                    <span class="text-orange-400 text-xs font-bold uppercase tracking-widest">Human Resources</span>
                </div>
                <h1 class="text-3xl sm:text-4xl font-black tracking-tight text-white">
                    Team <span class="text-gradient-gold">Management</span>
                </h1>
                <p class="text-slate-400 mt-1.5 text-sm">
                    Manage {{ $staff->count() }} team members, roles, and access permissions.
                </p>
            </div>
            <button @click="addModal=true"
                    class="btn-primary flex items-center gap-2 w-full sm:w-auto justify-center">
                <i data-lucide="user-plus" class="w-4 h-4"></i> Add Team Member
            </button>
        </div>
    </div>

    {{-- ═══ STATS ═══ --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 animate-fade-up delay-100">
        @php
            $total   = $staff->count();
            $active  = $staff->where('is_super_admin', false)->count();
            $admins  = $staff->where('is_super_admin', true)->count();
        @endphp
        <div class="modern-card p-5 border-t-[4px] border-orange-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-orange-500"></div>
            <div class="w-10 h-10 rounded-xl bg-orange-100 dark:bg-orange-500/10 text-orange-600 dark:text-orange-400 flex items-center justify-center mb-3">
                <i data-lucide="users" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $total }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-semibold uppercase tracking-wider">Total Members</p>
        </div>
        <div class="modern-card p-5 border-t-[4px] border-emerald-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-emerald-500"></div>
            <div class="w-10 h-10 rounded-xl bg-emerald-100 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center mb-3">
                <i data-lucide="check-circle" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $active }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-semibold uppercase tracking-wider">Active Staff</p>
        </div>
        <div class="modern-card p-5 border-t-[4px] border-red-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-red-500"></div>
            <div class="w-10 h-10 rounded-xl bg-red-100 dark:bg-red-500/10 text-red-600 dark:text-red-400 flex items-center justify-center mb-3">
                <i data-lucide="shield" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $admins }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-semibold uppercase tracking-wider">Administrators</p>
        </div>
        <div class="modern-card p-5 border-t-[4px] border-blue-500 group hover:-translate-y-2 hover:shadow-xl transition-all duration-300">
            <div class="stat-card-orb bg-blue-500"></div>
            <div class="w-10 h-10 rounded-xl bg-blue-100 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center mb-3">
                <i data-lucide="key-round" class="w-5 h-5"></i>
            </div>
            <p class="text-2xl font-black text-slate-900 dark:text-white">{{ $roles->count() }}</p>
            <p class="text-xs text-slate-500 mt-0.5 font-semibold uppercase tracking-wider">Active Roles</p>
        </div>
    </div>

    {{-- ═══ STAFF TABLE ═══ --}}
    <div class="modern-card overflow-hidden animate-fade-up delay-150">
        {{-- Table header --}}
        <div class="flex items-center justify-between px-6 py-4 border-b border-slate-200/50 dark:border-white/[0.05]">
            <h3 class="font-bold text-slate-900 dark:text-white">All Team Members</h3>
            <div class="flex items-center gap-2">
                <div class="relative">
                    <i data-lucide="search" class="w-4 h-4 absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 pointer-events-none"></i>
                    <input type="text" placeholder="Search team..." x-model="searchQuery"
                           class="input-field pl-9 py-2 w-48 text-sm">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Team Member</th>
                        <th>Contact</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th class="w-12"></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($staff as $member)
                    <tr x-show="!searchQuery || '{{ strtolower($member->name . ' ' . $member->email) }}'.includes(searchQuery.toLowerCase())">
                        <td>
                            <div class="flex items-center gap-3">
                                <div class="relative flex-shrink-0">
                                    <img src="{{ $member->profile_photo_url ?? 'https://ui-avatars.com/api/?name=' . urlencode($member->name) . '&background=10b981&color=fff&bold=true&size=48' }}"
                                         class="w-10 h-10 rounded-xl object-cover border border-slate-200 dark:border-slate-700 shadow-sm"
                                         alt="{{ $member->name }}">
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 rounded-full {{ $member->id === auth()->id() ? 'bg-emerald-500' : 'bg-slate-300 dark:bg-slate-600' }} border-2 border-white dark:border-neutral-950"></span>
                                </div>
                                <div>
                                    <p class="font-bold text-slate-900 dark:text-white text-sm">
                                        {{ $member->name }}
                                        @if($member->id === auth()->id())
                                            <span class="badge badge-info ml-1 text-[9px]">You</span>
                                        @endif
                                    </p>
                                    <p class="text-xs text-slate-500 truncate max-w-[180px]">{{ $member->email }}</p>
                                </div>
                            </div>
                        </td>
                        <td>
                            @if($member->phone)
                                <span class="text-xs text-slate-600 dark:text-slate-400 flex items-center gap-1">
                                    <i data-lucide="phone" class="w-3 h-3"></i> {{ $member->phone }}
                                </span>
                            @else
                                <span class="text-slate-400 text-xs">—</span>
                            @endif
                        </td>
                        <td>
                            @if($member->is_super_admin)
                                <span class="badge badge-danger badge-dot">Super Admin</span>
                            @elseif($member->roles->isNotEmpty())
                                @foreach($member->roles->take(2) as $role)
                                    <span class="badge badge-info">{{ ucfirst($role->name) }}</span>
                                @endforeach
                            @else
                                <span class="badge badge-neutral">No Role</span>
                            @endif
                        </td>
                        <td>
                            @if($member->id === auth()->id())
                                <span class="badge badge-success badge-dot">Online</span>
                            @else
                                <span class="badge badge-neutral">Inactive</span>
                            @endif
                        </td>
                        <td class="text-xs text-slate-500">
                            {{ $member->created_at?->format('M d, Y') ?? '—' }}
                        </td>
                        <td @click.stop class="relative">
                            @if($member->id !== auth()->id())
                            <div x-data="{ open: false }" @click.away="open=false">
                                <button @click="open=!open"
                                        class="p-1.5 rounded-lg text-slate-400 hover:text-slate-700 dark:hover:text-slate-200 hover:bg-slate-100 dark:hover:bg-slate-800 transition-colors">
                                    <i data-lucide="more-vertical" class="w-4 h-4"></i>
                                </button>
                                <div x-show="open" x-cloak
                                     x-transition:enter="transition ease-out duration-150"
                                     x-transition:enter-start="opacity-0 scale-95"
                                     x-transition:enter-end="opacity-100 scale-100"
                                     class="absolute right-8 top-8 w-44 modern-card shadow-xl z-50 overflow-hidden p-1">
                                    <div class="h-px bg-slate-200/60 dark:bg-white/[0.05] my-1"></div>
                                    <form action="{{ route('staff.destroy', $member->id) }}" method="POST">
                                        @csrf @method('DELETE')
                                        <button type="submit"
                                                onclick="return confirm('Remove {{ $member->name }} from the team?')"
                                                class="w-full flex items-center gap-2.5 px-3 py-2 rounded-lg text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                            <i data-lucide="user-x" class="w-4 h-4"></i> Remove Member
                                        </button>
                                    </form>
                                </div>
                            </div>
                            @else
                            <span class="text-xs text-slate-400">—</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="py-0">
                            <div class="empty-state">
                                <div class="empty-state-icon">
                                    <i data-lucide="users" class="w-8 h-8 text-slate-300 dark:text-slate-600"></i>
                                </div>
                                <p class="font-bold text-slate-700 dark:text-slate-300">No team members yet</p>
                                <button @click="addModal=true" class="btn-primary mt-2">Add First Member</button>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ═══ ADD MEMBER MODAL ═══ --}}
    <div x-show="addModal" x-cloak class="modal-backdrop"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100">
        <div class="modal-panel"
             @click.stop
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 scale-95"
             x-transition:enter-end="opacity-100 scale-100">

            <div class="modal-header">
                <div class="flex items-center gap-3">
                    <div class="w-9 h-9 rounded-xl gradient-emerald flex items-center justify-center">
                        <i data-lucide="user-plus" class="w-4 h-4 text-white"></i>
                    </div>
                    <h2 class="text-lg font-bold text-slate-900 dark:text-white">Add Team Member</h2>
                </div>
                <button @click="addModal=false" class="btn-ghost btn-icon text-slate-400">
                    <i data-lucide="x" class="w-4 h-4"></i>
                </button>
            </div>

            <form action="{{ route('staff.store') }}" method="POST">
                @csrf
                <div class="modal-body space-y-4">
                    <div class="form-group">
                        <label class="label-field">Full Name <span class="text-red-500">*</span></label>
                        <input type="text" name="name" required class="input-field" placeholder="John Smith">
                        @error('name')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="label-field">Email Address <span class="text-red-500">*</span></label>
                        <input type="email" name="email" required class="input-field" placeholder="john@farm.com">
                        @error('email')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="label-field">Role <span class="text-red-500">*</span></label>
                        <select name="role_id" required class="select-field">
                            <option value="">Select a role...</option>
                            @foreach($roles as $role)
                                <option value="{{ $role->id }}">{{ ucfirst($role->name) }}{{ $role->description ? ' — ' . $role->description : '' }}</option>
                            @endforeach
                        </select>
                        @error('role_id')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="form-group">
                        <label class="label-field">Phone Number</label>
                        <input type="tel" name="phone" class="input-field" placeholder="+213 5XX XXX XXX">
                    </div>
                    <div class="form-group">
                        <label class="label-field">Password <span class="text-red-500">*</span></label>
                        <input type="password" name="password" required class="input-field" placeholder="Min. 8 characters">
                        @error('password')<p class="text-xs text-red-500 mt-1">{{ $message }}</p>@enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" @click="addModal=false" class="btn-ghost">Cancel</button>
                    <button type="submit" class="btn-primary">
                        <i data-lucide="user-plus" class="w-4 h-4"></i> Create Account
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@push('scripts')
<script>
document.addEventListener('alpine:init', () => {
    Alpine.data('staffManager', () => ({
        addModal: {{ $errors->any() ? 'true' : 'false' }},
        searchQuery: '',
    }));
});
</script>
@endpush
