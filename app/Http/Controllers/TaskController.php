<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        if (!$project->isMember(Auth::user())) {
            abort(403);
        }

        $tasks = $project->tasks()->with(['assignee', 'creator', 'category'])->get();
        return view('tasks.index', compact('tasks'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        if (!$project->isMember(Auth::user())) {
            abort(403);
        }

        $categories = Auth::user()->categories();

        $members = $project->members()->with('user')->get();

        return view('tasks.create', compact('project', 'categories', 'members'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        if (!$project->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $project->tasks()->create([
            ...$validated,
            'created_by' => Auth::id(),
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Task created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project, Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $task->load(['project', 'assignee', 'creator', 'category', 'subtasks', 'comments', 'attachments']);
        return view('tasks.show', compact('task'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $categories = Auth::user()->categories;
        $members = $task->project->members()->with('user')->get();

        return view('tasks.edit', compact('task', 'categories', 'members'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:todo,in_progress,done',
            'priority' => 'required|in:low,medium,high',
            'assigned_to' => 'nullable|exists:users,id',
            'category_id' => 'nullable|exists:categories,id',
            'due_date' => 'nullable|date',
        ]);

        $task->update($validated);

        return redirect()->route('projects.tasks.show', [$task->project, $task])->with('success', 'Task updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $projectId = $task->project_id;
        $task->delete();

        return redirect()->route('projects.show', $projectId)->with('success', 'Task deleted successfully');
    }

    /**
     * Mark task as completed.
     */
    public function complete(Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $task->update([
            'status' => 'done',
            'completion_date' => now(),
        ]);

        return back()->with('success', 'Task marked as complete');
    }
}