<?php

namespace App\Policies;

use App\Models\Project;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Access\Response;

class ProjectPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    // Kiểm tra user có thể view project không
    public function view(User $user, Project $project): bool
    {
        return $project->isMember($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return Auth::check();
    }

    // Kiểm tra user có thể update project không
    public function update(User $user, Project $project): bool
    {
        return $project->isOwner($user);
    }

    // Kiểm tra user có thể delete project không
    public function delete(User $user, Project $project): bool
    {
        return $project->isOwner($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Project $project): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Project $project): bool
    {
        return false;
    }
}
