<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProjectVerificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_manager_can_create_view_edit_and_delete_own_project(): void
    {
        $manager = User::factory()->create(['role' => 'manager']);
        $other = User::factory()->create(['role' => 'manager']);
        $this->actingAs($manager)->get('/projects')->assertOk();
        $this->get('/projects/create')->assertOk();
        $this->post('/projects', ['name' => 'Verification project', 'description' => 'Demo', 'user_id' => $other->id])->assertRedirect();
        $project = Project::where('name', 'Verification project')->firstOrFail();
        $this->assertSame($manager->id, $project->user_id);
        $this->get('/projects/'.$project->id)->assertOk()->assertSee('Verification project');
        $this->get('/projects/'.$project->id.'/edit')->assertOk();
        $this->patch('/projects/'.$project->id, ['name' => 'Updated project', 'description' => 'Changed'])->assertRedirect();
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Updated project']);
        $this->delete('/projects/'.$project->id)->assertRedirect('/projects');
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_executor_and_other_manager_cannot_modify_foreign_project(): void
    {
        $owner = User::factory()->create(['role' => 'manager']);
        $project = Project::create(['name' => 'Protected project', 'user_id' => $owner->id]);
        foreach (['executor', 'manager'] as $role) {
            $user = User::factory()->create(['role' => $role]);
            $this->actingAs($user)->get('/projects/'.$project->id)->assertOk();
            $this->get('/projects/'.$project->id.'/edit')->assertForbidden();
            $this->patch('/projects/'.$project->id, ['name' => 'Forbidden'])->assertForbidden();
            $this->delete('/projects/'.$project->id)->assertForbidden();
            if ($role === 'executor') {
                $this->get('/projects/create')->assertForbidden();
                $this->post('/projects', ['name' => 'Forbidden'])->assertForbidden();
            }
        }
        $this->assertDatabaseHas('projects', ['id' => $project->id, 'name' => 'Protected project']);
    }

    public function test_admin_can_modify_foreign_project_and_validation_rejects_empty_name(): void
    {
        $owner = User::factory()->create(['role' => 'manager']);
        $admin = User::factory()->create(['role' => 'admin']);
        $project = Project::create(['name' => 'Admin project', 'user_id' => $owner->id]);
        $this->actingAs($admin)->post('/projects', ['name' => ''])->assertSessionHasErrors('name');
        $this->get('/projects/'.$project->id.'/edit')->assertOk();
        $this->patch('/projects/'.$project->id, ['name' => 'Admin changed'])->assertRedirect();
        $this->delete('/projects/'.$project->id)->assertRedirect();
        $this->assertDatabaseMissing('projects', ['id' => $project->id]);
    }

    public function test_guests_are_redirected_to_login(): void
    {
        $this->get('/projects')->assertRedirect('/login');
    }
}
