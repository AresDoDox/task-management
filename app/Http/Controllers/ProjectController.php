<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $projects = Auth::user()->projects()->with('tasks')->get();
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $project = Auth::user()->projects()->create([
            'name' => $validated['name'],
            'description' => $validated['description'],
            'slug' => Str::slug($validated['name']),
        ]);

        // Tự động thêm creator vào project members
        $project->members()->create([
            'user_id' => Auth::id(),
            'role' => 'owner',
        ]);

        return redirect()->route('projects.show', $project)->with('success', 'Project created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Project $project)
    {
        if (!$project->isMember(Auth::user())) {
            abort(403);
        }

        $tasks = $project->tasks()->with(['assignee', 'creator', 'category'])->get();
        return view('projects.show', compact('project', 'tasks'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        if (!$project->isOwner(Auth::user())) {
            abort(403);
        }

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Project $project)
    {
        if (!$project->isOwner(Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
        ]);

        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        if (!$project->isOwner(Auth::user())) {
            abort(403);
        }

        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}