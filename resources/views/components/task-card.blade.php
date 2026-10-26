@props(['task'])

<div class="bg-white dark:bg-gray-800 rounded-xl p-4 border border-gray-100 dark:border-gray-700 shadow-sm hover:shadow-md transition-shadow relative group">
    <div class="flex justify-between items-start mb-2">
        <h4 class="font-bold text-gray-900 dark:text-white text-sm">{{ $task->title }}</h4>
        @if($task->priority === 'high')
            <span class="w-2 h-2 rounded-full bg-red-500 mt-1.5" title="High Priority"></span>
        @elseif($task->priority === 'medium')
            <span class="w-2 h-2 rounded-full bg-blue-500 mt-1.5" title="Medium Priority"></span>
        @else
            <span class="w-2 h-2 rounded-full bg-gray-400 mt-1.5" title="Low Priority"></span>
        @endif
    </div>
    
    @if($task->description)
        <p class="text-xs text-gray-500 dark:text-gray-400 mb-3 line-clamp-2">{{ $task->description }}</p>
    @endif

    <div class="flex items-center justify-between text-xs mt-4 pt-3 border-t border-gray-100 dark:border-gray-700">
        <div class="flex items-center text-gray-500 dark:text-gray-400">
            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
            {{ $task->assignee ? $task->assignee->name : 'Unassigned' }}
        </div>
        
        @if($task->due_date)
            <div class="flex items-center {{ $task->due_date < now() && $task->status !== 'completed' ? 'text-red-500 font-medium' : 'text-gray-500 dark:text-gray-400' }}">
                <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                {{ $task->due_date->format('M d') }}
            </div>
        @endif
    </div>

    <!-- Quick Actions Overlay -->
    <div class="absolute inset-0 bg-gray-900/40 backdrop-blur-[1px] rounded-xl opacity-0 group-hover:opacity-100 transition-opacity flex items-center justify-center space-x-2">
        <form action="{{ route('tasks.update', $task->id) }}" method="POST" class="m-0">
            @csrf
            @method('PUT')
            <select name="status" onchange="this.form.submit()" class="text-xs bg-white dark:bg-gray-800 text-gray-900 dark:text-white border-0 rounded-lg shadow-sm py-1 pl-2 pr-6 cursor-pointer">
                <option value="pending" {{ $task->status === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="in_progress" {{ $task->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
                <option value="completed" {{ $task->status === 'completed' ? 'selected' : '' }}>Completed</option>
            </select>
        </form>

        <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" class="m-0" onsubmit="return confirm('Delete this task?');">
            @csrf
            @method('DELETE')
            <button type="submit" class="p-1.5 bg-white dark:bg-gray-800 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg shadow-sm transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
            </button>
        </form>
    </div>
</div>
