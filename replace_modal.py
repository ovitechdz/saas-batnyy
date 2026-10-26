import sys

file_path = "c:/xampp/htdocs/newprjct/resources/views/tasks/index.blade.php"
with open(file_path, "r", encoding="utf-8") as f:
    lines = f.readlines()

start_idx = -1
end_idx = -1

for i, line in enumerate(lines):
    if '<div x-show="addModalOpen" x-transition.scale.origin.bottom class="relative' in line:
        start_idx = i
    if start_idx != -1 and '</form>' in line:
        end_idx = i
        break

if start_idx == -1 or end_idx == -1:
    print("Could not find bounds")
    sys.exit(1)

new_content = """                <div x-show="addModalOpen" x-transition.scale.origin.bottom class="relative z-10 inline-block align-bottom bg-[#0B1511] border border-[#1C362B] rounded-2xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
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
"""

new_lines = new_content.splitlines(True)
lines[start_idx:end_idx] = new_lines

with open(file_path, "w", encoding="utf-8") as f:
    f.writelines(lines)
print("Replaced successfully")
