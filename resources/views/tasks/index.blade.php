@extends('layouts.app')

@section('title', 'Task Management')

@section('content')
<div class="space-y-6 animate-fade-in" x-data="{ addModalOpen: false, editModalOpen: false, editTask: {} }">
    <!-- Hero -->
    <div class="page-hero">
        <div class="page-hero-content flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
            <div>
                <span class="text-blue-400 text-xs font-bold uppercase tracking-widest">{{ __('Team Operations') }}</span>
                <h2 class="text-3xl font-black tracking-tight text-white mt-0.5">{{ __('Tasks & Workflow') }}</h2>
                <p class="text-gray-400 text-sm mt-0.5">{{ __('Manage daily farm tasks and worker assignments.') }}</p>
            </div>
            <button @click="addModalOpen = true" class="premium-btn flex items-center space-x-2 flex-shrink-0 !from-emerald-600 !to-green-600 !shadow-green-900/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                <span>{{ __('New Task') }}</span>
            </button>
        </div>
    </div>

    <!-- Tasks Board (Kanban style or List style) -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        
        <!-- Pending Column -->
        <div class="glass-panel p-4 flex flex-col h-full border-t-4 border-t-amber-500">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                <span>{{ __('To Do') }}</span>
                <span class="bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 text-xs py-1 px-2.5 rounded-full">{{ $tasks->where('status', 'pending')->count() }}</span>
            </h3>
            <div class="space-y-4 flex-1">
                @forelse($tasks->where('status', 'pending') as $task)
                    <x-task-card :task="$task" />
                @empty
                    <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No pending tasks.') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- In Progress Column -->
        <div class="glass-panel p-4 flex flex-col h-full border-t-4 border-t-blue-500">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                <span>{{ __('In Progress') }}</span>
                <span class="bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 text-xs py-1 px-2.5 rounded-full">{{ $tasks->where('status', 'in_progress')->count() }}</span>
            </h3>
            <div class="space-y-4 flex-1">
                @forelse($tasks->where('status', 'in_progress') as $task)
                    <x-task-card :task="$task" />
                @empty
                    <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No active tasks.') }}
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Completed Column -->
        <div class="glass-panel p-4 flex flex-col h-full border-t-4 border-t-emerald-500">
            <h3 class="text-lg font-bold text-gray-900 dark:text-white mb-4 flex items-center justify-between">
                <span>{{ __('Done') }}</span>
                <span class="bg-emerald-100 dark:bg-emerald-900/30 text-emerald-700 dark:text-emerald-400 text-xs py-1 px-2.5 rounded-full">{{ $tasks->where('status', 'completed')->count() }}</span>
            </h3>
            <div class="space-y-4 flex-1">
                @forelse($tasks->where('status', 'completed') as $task)
                    <x-task-card :task="$task" />
                @empty
                    <div class="p-4 border-2 border-dashed border-gray-200 dark:border-gray-800 rounded-xl text-center text-sm text-gray-500 dark:text-gray-400">
                        {{ __('No completed tasks yet.') }}
                    </div>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Add Task Modal -->
    <template x-teleport="body">
        <div x-show="addModalOpen" x-cloak class="fixed inset-0 z-[100] overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div x-show="addModalOpen" x-transition.opacity class="fixed inset-0 bg-black/50 backdrop-blur-sm transition-opacity" @click="addModalOpen = false"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="addModalOpen" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-[#0B1511] border border-[#1C362B] rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    <form action="{{ route('tasks.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="status" value="pending">
                        
                        <!-- Header with close button -->
                        <div class="px-6 pt-6 pb-4 flex justify-between items-center border-b border-[#1C362B]">
                            <div class="flex items-center space-x-2">
                                <svg class="w-6 h-6 text-[#3DE082]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                <h3 class="text-xl font-bold text-white">{{ __('Create New Task') }}</h3>
                            </div>
                            <button @click="addModalOpen = false" type="button" class="text-gray-400 hover:text-white transition-colors">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                            </button>
                        </div>

                        <div class="px-6 py-6 space-y-5">
                            <div>
                                <label class="block text-[11px] font-black tracking-widest text-[#3DE082] uppercase mb-2">{{ __('Task Title') }}</label>
                                <input type="text" name="title" required class="block w-full px-4 py-3 rounded-xl border border-[#1C362B] bg-[#0E1A15] shadow-sm focus:border-[#3DE082] focus:ring-1 focus:ring-[#3DE082] text-white transition-all outline-none placeholder-[#4B5E55]" placeholder="{{ __('e.g. Vaccinate sick ewes') }}">
                            </div>
                            
                            <div>
                                <label class="block text-[11px] font-black tracking-widest text-[#3DE082] uppercase mb-2">{{ __('Description') }}</label>
                                <textarea name="description" rows="3" class="block w-full px-4 py-3 rounded-xl border border-[#1C362B] bg-[#0E1A15] shadow-sm focus:border-[#3DE082] focus:ring-1 focus:ring-[#3DE082] text-white transition-all outline-none resize-none placeholder-[#4B5E55]" placeholder="{{ __('Provide more details...') }}"></textarea>
                            </div>

                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-[11px] font-black tracking-widest text-[#3DE082] uppercase mb-2">{{ __('Assign To') }}</label>
                                    <select name="assigned_to" class="block w-full px-4 py-3 rounded-xl border border-[#1C362B] bg-[#0E1A15] shadow-sm focus:border-[#3DE082] focus:ring-1 focus:ring-[#3DE082] text-white transition-all outline-none cursor-pointer">
                                        <option value="">{{ __('Unassigned') }}</option>
                                        @foreach($workers as $worker)
                                            <option value="{{ $worker->id }}">{{ $worker->first_name }} {{ $worker->last_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-[11px] font-black tracking-widest text-[#3DE082] uppercase mb-2">{{ __('Due Date') }}</label>
                                    <input type="date" name="due_date" value="{{ date('Y-m-d') }}" required class="block w-full px-4 py-3 rounded-xl border border-[#1C362B] bg-[#0E1A15] shadow-sm focus:border-[#3DE082] focus:ring-1 focus:ring-[#3DE082] text-white transition-all outline-none cursor-pointer color-scheme-dark">
                                </div>
                            </div>

                            <div>
                                <label class="block text-[11px] font-black tracking-widest text-[#3DE082] uppercase mb-2">{{ __('Priority') }}</label>
                                <div class="flex space-x-4">
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="priority" value="low" class="w-4 h-4 text-gray-500 bg-[#0E1A15] border-[#1C362B] focus:ring-gray-500 focus:ring-offset-[#0B1511]">
                                        <span class="ml-2 text-sm font-medium text-gray-300">{{ __('Low') }}</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="priority" value="medium" checked class="w-4 h-4 text-[#3DE082] bg-[#0E1A15] border-[#1C362B] focus:ring-[#3DE082] focus:ring-offset-[#0B1511]">
                                        <span class="ml-2 text-sm font-medium text-white">{{ __('Medium') }}</span>
                                    </label>
                                    <label class="flex items-center cursor-pointer">
                                        <input type="radio" name="priority" value="high" class="w-4 h-4 text-red-500 bg-[#0E1A15] border-[#1C362B] focus:ring-red-500 focus:ring-offset-[#0B1511]">
                                        <span class="ml-2 text-sm font-medium text-gray-300">{{ __('High') }}</span>
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <div class="px-6 py-5 bg-transparent flex justify-end items-center space-x-4 border-t border-[#1C362B]/50">
                            <button @click="addModalOpen = false" type="button" class="text-white hover:text-[#3DE082] font-bold text-sm transition-colors">
                                {{ __('Cancel') }}
                            </button>
                            <button type="submit" class="px-6 py-2.5 bg-[#18A058] hover:bg-[#158C4D] text-white font-bold rounded-xl shadow-lg shadow-[#18A058]/20 transition-all">
                                {{ __('Create Task') }}
                            </button>
                        </div>
                    </form>
                    </form>
                </div>
            </div>
        </div>
    </template>
</div>
@endsection
