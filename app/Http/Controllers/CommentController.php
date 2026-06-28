<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    // Add new comment
    public function store(Request $request, Task $task)
    {
        if (!$task->project->isMember(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string|max:1000',
        ]);

        $task->comments()->create([
            'content' => $validated['content'],
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Comment added successfully');
    }

    // Delete comment
    public function destroy(Comment $comment)
    {
        if ($comment->user_id !== Auth::id() && !$comment->task->project->isOwner(Auth::user())) {
            abort(403);
        }

        $comment->delete();
        return back()->with('success', 'Comment deleted successfully');
    }
}