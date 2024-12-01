<?php
namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Task;
use App\Models\Category;

class TaskControllerTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_user_can_get_all_tasks()
    {
        Task::factory()->count(5)->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->getJson('/api/tasks');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'data' => [
                             '*' => [
                                 'id',
                                 'title',
                                 'description',
                                 'due_date',
                                 'status',
                                 'user_id',
                                 'category_id',
                             ]
                         ]
                     ]
                 ]);
    }

    public function test_user_can_filter_tasks_by_status()
    {
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'completed']);
        Task::factory()->create(['user_id' => $this->user->id, 'status' => 'pending']);

        $response = $this->actingAs($this->user)->getJson('/api/tasks?status=completed');

        $response->assertStatus(200)
                 ->assertJsonFragment(['status' => 'completed']);
    }

    public function test_user_can_create_task()
    {
        $taskData = [
            'title' => 'New Task',
            'description' => 'Task description',
            'due_date' => now()->addDays(5)->toDateString(),
            'category_id' => null,
        ];

        $response = $this->actingAs($this->user)->postJson('/api/tasks', $taskData);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('tasks', [
            'title' => 'New Task',
            'user_id' => $this->user->id,
        ]);
    }

    public function test_user_can_update_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $updateData = [
            'title' => 'Updated Task',
            'description' => 'Updated description',
            'due_date' => now()->addDays(7)->toDateString(),
            'status' => 'completed',
        ];

        $response = $this->actingAs($this->user)->putJson("/api/tasks/{$task->id}", $updateData);

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        $this->assertDatabaseHas('tasks', ['id' => $task->id, 'title' => 'Updated Task']);
    }

    public function test_user_can_delete_task()
    {
        $task = Task::factory()->create(['user_id' => $this->user->id]);

        $response = $this->actingAs($this->user)->deleteJson("/api/tasks/{$task->id}");

        $response->assertStatus(200)
                 ->assertJson(['status' => 'success']);
        $this->assertDatabaseMissing('tasks', ['id' => $task->id]);
    }

    public function test_user_cannot_access_tasks_without_authentication()
    {
        $response = $this->getJson('/api/tasks');
        $response->assertStatus(401);
    }
}
