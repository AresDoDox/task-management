<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        $totalProjects = $user->projects()->count();
        $totalTasks = $user->projects()->with('tasks')->get()->flatMap->tasks->count();
        $completedTasks = $user->assignedTasks()->where('status', 'done')->count();
        $pendingTasks = $user->assignedTasks()->where('status', '!=', 'done')->count();
        $overdueTasks = $user->assignedTasks()->where('status', '!=', 'done')->where('due_date', '<', now())->count();

        // Recent projects
        $recentProjects = $user->projects()->orderBy('updated_at', 'desc')->take(5)->get();

        // Recent tasks
        $recentTasks = $user->assignedTasks()->orderBy('updated_at', 'desc')->take(10)->get();

        return view('dashboard', compact(
            'totalProjects',
            'totalTasks',
            'completedTasks',
            'pendingTasks',
            'overdueTasks',
            'recentProjects',
            'recentTasks'
        ));
    }
}