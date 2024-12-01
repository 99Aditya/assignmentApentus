<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Category;
use App\Models\Task;

class CategoryControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_fetch_all_categories()
    {
        Category::factory()->count(15)->create();

        $response = $this->getJson('/api/categories');

        $response->assertStatus(200)
                 ->assertJsonStructure([
                     'status',
                     'message',
                     'data' => [
                         'data' => [
                             '*' => ['id', 'name', 'created_at', 'updated_at'],
                         ],
                         'links',
                         'meta',
                     ],
                 ]);
    }

    public function test_fetch_categories_returns_empty_if_no_data()
    {
        $response = $this->getJson('/api/categories');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Category data not found!',
                 ]);
    }

    public function test_can_create_category()
    {
        $data = ['name' => 'Work'];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Category saved successfully!',
                 ]);

        $this->assertDatabaseHas('categories', ['name' => 'Work']);
    }

    public function test_create_category_validation_fails()
    {
        $data = ['name' => ''];

        $response = $this->postJson('/api/categories', $data);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_can_update_category()
    {
        $category = Category::factory()->create(['name' => 'Old Name']);

        $response = $this->putJson("/api/categories/{$category->id}", ['name' => 'New Name']);

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Category updated successfully!',
                 ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id, 'name' => 'New Name']);
    }

    public function test_update_category_validation_fails()
    {
        $category = Category::factory()->create();

        $response = $this->putJson("/api/categories/{$category->id}", ['name' => '']);

        $response->assertStatus(422)
                 ->assertJsonValidationErrors(['name']);
    }

    public function test_can_delete_category()
    {
        $category = Category::factory()->create();

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(200)
                 ->assertJson([
                     'status' => 'success',
                     'message' => 'Category deleted successfully!',
                 ]);

        $this->assertDatabaseMissing('categories', ['id' => $category->id]);
    }

    public function test_cannot_delete_category_assigned_to_task()
    {
        $category = Category::factory()->create();
        Task::factory()->create(['category_id' => $category->id]);

        $response = $this->deleteJson("/api/categories/{$category->id}");

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'This category assign to the task!',
                 ]);

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_cannot_delete_nonexistent_category()
    {
        $response = $this->deleteJson('/api/categories/999');

        $response->assertStatus(422)
                 ->assertJson([
                     'status' => 'error',
                     'message' => 'Category not found!',
                 ]);
    }
}

