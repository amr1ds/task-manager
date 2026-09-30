<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApplicationPagesTest extends TestCase
{
    use RefreshDatabase;

    public function test_seeded_pages_render_for_each_role(): void
    {
        $this->seed();
        $project = Project::firstOrFail();
        $task = Task::firstOrFail();

        $this->get('/login')->assertOk();
        $this->get('/register')->assertOk();

        foreach (['admin', 'manager', 'executor'] as $role) {
            $user = User::where('role', $role)->firstOrFail();
            $this->actingAs($user);

            foreach (['/dashboard', '/projects', '/projects/'.$project->id, '/tasks', '/tasks/'.$task->id] as $url) {
                $this->get($url)->assertOk();
            }

            if ($role === 'admin') {
                $this->get('/admin/users')->assertOk();
                $this->get('/admin/users/'.$user->id.'/edit')->assertOk();
            } else {
                $this->get('/admin/users')->assertForbidden();
            }

            if ($role !== 'executor') {
                $this->get('/tasks/create')->assertOk();
                $this->get('/tasks/'.$task->id.'/edit')->assertOk();
            } else {
                $this->get('/tasks/create')->assertForbidden();
            }
        }
    }
}
