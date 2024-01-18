<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index(Request $request)
    {
        $tasks = Task::all();
    
        return response()->json($tasks);
    }
    
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string',
            'task_title' => 'required|string',
            'professor' => 'required|string',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|integer|in:0,1,2,3',
        ]);

        $task = Task::create($validatedData);
        return response()->json(['success' => 'Task created successfully'], 201);
    }


    public function show($id)
    {
        $task = Task::findOrFail($id);
    
        if (request()->wantsJson()) {
            return response()->json(['task' => $this->formatTaskDates($task)]);
        }
    
        return response()->json(['task' => $this->formatTaskDates($task)]);
    }

    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'subject' => 'required|string',
            'task_title' => 'required|string',
            'professor' => 'required|string',
            'description' => 'nullable|string',
            'deadline' => 'required|date',
            'completed_at' => 'nullable|date',
            'status' => 'required|integer|in:0,1,2,3',
        ]);

        $task = Task::findOrFail($id);
        $task->update($validatedData);

        return response()->json(['success' => 'Task updated successfully'], 200);
    }

    public function destroy($id)
    {
        $task = Task::find($id);
    
        if ($task) {
            $task->delete();
            return response()->json(['success' => 'Task deleted successfully']);
        }
        return response()->json(['success' => 'Task not found, but operation completed']);
    }

    private function formatTaskDates($task)
    {
        $task['deadline'] = Carbon::parse($task['deadline'])->toDateTimeString();
        $task['completed_at'] = $task['completed_at'] ? Carbon::parse($task['completed_at'])->toDateTimeString() : null;

        return $task;
    }
}
