<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class EmployeeApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_get_all(): void
    {
        $response = $this->get('/api/employees');
        $response->assertStatus(200);
    }

    public function test_can_store(): void
    {
        $data = [
            'name' => 'Osy',
            'job_title' => 'Web Developer',
            'department' => 'DAD',
            'address' => 'Jatim',
        ];

        $response = $this->post('/api/employees', $data);
        $response->assertStatus(201);
    }

    public function test_can_get_data_by_id(): void
    {
        $employeeExists = Employee::where('id', 3)->exists();

        if ($employeeExists) {
            $response = $this->get("/api/employees/3");
            $response->assertStatus(200);
        } else {
            $response = $this->get("/api/employees/3");
            $response->assertStatus(404);
        }
    }
}
