<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\ProjectMember;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_update_task(): void
    {
        $user = User::factory()->create();

        $project = Project::factory()->create([
            'name' => 'Test Project',
            'owner_id' => $user->id,
        ]);

        ProjectMember::create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'role' => 'owner',
            'joined_at' => now(),
        ]);

        $task = Task::factory()->create([
            'project_id' => $project->id,
            'created_by' => $user->id,
            'title' => 'Old title',
            'description' => 'Old description',
            'status' => 'todo',
            'priority' => 'low',
        ]);

        $this->actingAs($user);

        $response = $this->put(route('projects.tasks.update', [$project, $task]), [
            'title' => 'Updated title',
            'description' => 'Updated description',
            'status' => 'in_progress',
            'priority' => 'high',
            'assigned_to' => null,
            'category_id' => null,
            'due_date' => null,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('tasks', [
            'id' => $task->id,
            'title' => 'Updated title',
            'status' => 'in_progress',
            'priority' => 'high',
        ]);
    }
}
