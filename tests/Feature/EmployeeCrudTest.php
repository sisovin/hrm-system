<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;

class EmployeeCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_create_employee()
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->post(route('hr.attendance.check-in'))
            ->assertRedirect();
    }
}
