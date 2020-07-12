<?php


namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProjectTest extends TestCase
{
    use WithFaker, RefreshDatabase;
    
    public function test_a_user_can_upload_a_csv_of_people_data() {
    
    }
    
    public function test_uploaded_csv_data_gets_added_to_the_db() {
        $this->withoutExceptionHandling();
        
        $attributes = [
            'first_name' => $this->faker->name,
            'last_name' => $this->faker->name,
            'email_address' => $this->faker->email,
            'status' => 'active'
        ];
        
        $this->post('/debug', $attributes);
        $this->assertDatabaseHas('people', $attributes);
    }
    
    public function test_user_can_create_project() {
        $this->withoutExceptionHandling();
        
        $attributes = [
            'title' => $this->faker->sentence,
            'description' => $this->faker->paragraph,
        ];
        
        $this->post('/projects', $attributes);
        
        $this->assertDatabaseHas('projects', $attributes);
        
        $this->get('/projects')->assertSee($attributes['title']);
    }
}