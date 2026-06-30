<?php

namespace App\Http\Controllers;

use App\Models\Subtask;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubtaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask = $task->subtasks()->create([
            'title' => $validated['title'],
            'order' => $task->subtasks()->count(),
        ]);

        // Log activity
        // activity()
        //     ->performedOn($task)
        //     ->withProperties(['subtask' => $subtask->title])
        //     ->log('Added subtask');

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask,
                'message' => 'Subtask added successfully',
            ]);
        }

        return back()->with('success', 'Subtask added successfully');
    }

    // Update subtask (toggle complete status)
    public function toggle(Subtask $subtask)
    {
        if (!$subtask->task->project->isMember(Auth::user())) {
            abort(403);
        }

        $subtask->update([
            'is_completed' => !$subtask->is_completed,
        ]);

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'subtask' => $subtask,
            ]);
        }

        return back()->with('success', 'Subtask updated');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Subtask $subtask)
    {
        if (!$subtask->task->project->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
        ]);

        $subtask->update($validated);

        return back()->with('success', 'Subtask updated');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Subtask $subtask)
    {
        if (!$subtask->task->project->isMember(Auth::user())) {
            abort(403);
        }

        $taskId = $subtask->task_id;
        $subtask->delete();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Subtask deleted',
            ]);
        }

        return back()->with('success', 'Subtask deleted');
    }
}