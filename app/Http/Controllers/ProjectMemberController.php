<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProjectMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Project $project)
    {
        if (!$project->isMember(Auth::user())) {
            abort(403);
        }

        $members = $project->members()->with('user')->paginate(10);
        return view('members.index', compact('project', 'members'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Project $project)
    {
        if (!$project->isOwner(Auth::user())) {
            abort(403);
        }

        $users = User::whereNotIn('id', $project->members()->pluck('user_id'))->get();
        return view('members.create', compact('project', 'users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, Project $project)
    {
        if (!$project->isOwner(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:project_members,user_id,NULL,id,project_id,' . $project->id,
            'role' => 'required|in:owner,member',
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $validated['user_id'],
            'role' => $validated['role'],
            'joined_at' => now(),
        ]);

        return redirect()->route('projects.members', $project)->with('success', 'Member added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProjectMember $member)
    {
        if (!$member->project->isOwner(Auth::user()) || $member->user_id === Auth::id()) {
            abort(403);
        }

        if ($member->role === 'owner') {
            abort(403, 'Cannot modify project owner');
        }

        $validated = $request->validate([
            'role' => 'required|in:owner,member,admin,viewer',
        ]);

        $member->update($validated);

        return back()->with('success', 'Member role updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProjectMember $member)
    {
        if (!$member->project->isOwner(Auth::user()) || $member->user_id === Auth::id()) {
            abort(403);
        }

        if ($member->role === 'owner') {
            abort(403, 'Cannot remove project owner');
        }

        $member->delete();

        return back()->with('success', 'Member removed successfully');
    }
}