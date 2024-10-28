<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\DatabaseMigrations;
//use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_common()
    {
        $response = $this->get('/api/tasks');
        $response->assertStatus(200);
    }

    public function test_points()
    {
        $task =[
            'title' => 'someTitle',
            'description' => 'someDescription',
            'finished' => '1',
            'finished_time' => '2024-10-25'
        ];
        $response = $this->post('/api/tasks', $task);
        $response->assertStatus(201);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'finished',
                'finished_time',
            ]
        ]);
        $task =[
            'title' => 'someNewTitle',
            'description' => 'someNewDescription',
            'finished' => '0',
            'finished_time' => '2024-10-24'
        ];
        $response = $this->put('/api/tasks/1', $task);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'finished',
                'finished_time',
            ]
        ]);

        $task =[
            
            'description' => 'someNewDescription',
            'finished' => '1',
            'finished_time' => '2024-10-24'
        ];
        $response = $this->patch('/api/tasks/1', $task);
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'finished',
                'finished_time',
            ]
        ]);

        $response = $this->get('/api/tasks/1');
        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                'id',
                'title',
                'description',
                'finished',
                'finished_time',
            ]
        ]);
        $response = $this->delete('/api/tasks/1');
        $response->assertStatus(204);
    }

}