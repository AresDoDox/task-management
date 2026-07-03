<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\ProjectStoreRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Lấy danh sách các dự án mà người dùng hiện tại là thành viên hoặc chủ sở hữu
        // with('tasks') để lấy luôn danh sách các task liên quan đến project
        $projects = Auth::user()->projects()->with('tasks')->paginate(10);
        return view('projects.index', compact('projects'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        Gate::authorize('create', Project::class);
        return view('projects.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ProjectStoreRequest $request)
    {
        Gate::authorize('create', Project::class);

        $validated = $request->validated();

        // Tạo dự án mới và gán người dùng hiện tại làm chủ sở hữu
        $project = Auth::user()->projects()->create($validated);

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
    public function show(Request $request, Project $project)
    {
        // Kiểm tra quyền truy cập dự án
        Gate::authorize('view', $project);

        // Lấy danh sách các task liên quan đến dự án, có thể lọc và sắp xếp theo các tham số từ request
        // with(['project', 'assignee', 'creator', 'category']) để lấy luôn thông tin liên quan đến project, người được giao, người tạo và danh mục của task
        $query = $project->tasks()->with(['project', 'assignee', 'creator', 'category']);

        // Lấy các tham số lọc và sắp xếp từ request
        $search = $request->input('search') ?? '';
        $status = $request->input('status');
        $priority = $request->input('priority');
        $category_id = $request->input('category_id');
        $assigned_to = $request->input('assigned_to');
        $sort = $request->input('sort', 'created_at');
        $direction = 'desc';


        $tasks = $query->searchTask($search)
            ->filterTaskByStatus($status)
            ->filterTaskByPriority($priority)
            ->filterTaskByCategory($category_id)
            ->FilterTaskByAssignedUser($assigned_to)
            ->sortTask($sort, $direction)
            ->paginate(15);

        $categories = Auth::user()->categories()->get();

        return view('projects.show', compact('project', 'tasks', 'categories'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Project $project)
    {
        Gate::authorize('view', $project);

        return view('projects.edit', compact('project'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProjectStoreRequest $request, Project $project)
    {
        Gate::authorize('update', $project);

        $validated = $request->validated();

        // Cập nhật thông tin dự án
        $project->update($validated);

        return redirect()->route('projects.show', $project)->with('success', 'Project updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Project $project)
    {
        Gate::authorize('delete', $project);

        // Xóa dự án và tất cả các task liên quan
        $project->tasks()->delete();
        $project->delete();

        return redirect()->route('projects.index')->with('success', 'Project deleted successfully');
    }
}