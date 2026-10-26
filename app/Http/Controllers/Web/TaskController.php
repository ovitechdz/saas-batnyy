<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Task;
use App\Models\Employee;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $farm_id = auth()->user()->farm_id;
        
        $tasks = Task::with('assignee')
            ->where('farm_id', $farm_id)
            ->orderBy('due_date', 'asc')
            ->get();
            
        $workers = Employee::where('farm_id', $farm_id)->get();

        return view('tasks.index', compact('tasks', 'workers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'assigned_to' => 'nullable|exists:employees,id',
            'due_date' => 'nullable|date',
            'priority' => 'required|in:low,medium,high',
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $validated['farm_id'] = auth()->user()->farm_id;

        Task::create($validated);

        return redirect()->route('tasks.index')->with('success', 'Task created successfully.');
    }

    public function update(Request $request, $id)
    {
        $task = Task::where('farm_id', auth()->user()->farm_id)->findOrFail($id);
        
        $validated = $request->validate([
            'status' => 'required|in:pending,in_progress,completed',
        ]);

        $task->update($validated);

        return back()->with('success', 'Task status updated.');
    }

    public function destroy($id)
    {
        $task = Task::where('id', $id)->where('farm_id', auth()->user()->farm_id)->firstOrFail();
        $task->delete();
        
        return back()->with('success', 'Task deleted successfully.');
    }
}
