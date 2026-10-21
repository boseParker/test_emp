<?php

namespace Tests\Feature;

use App\Models\Employee;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EmployeeManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_employee_crud_flow(): void
    {
        $data = [
            'firstname' => 'Arun',
            'lastname' => 'Kumar',
            'date_of_birth' => '1995-05-14',
            'education_qualification' => 'B.E',
            'address' => '1 Main Street',
            'email' => 'arun@example.com',
            'phone' => '9841012345',
        ];

        $this->post(route('employees.store'), $data)
            ->assertRedirect(route('employees.index'));

        $employee = Employee::where('email', $data['email'])->firstOrFail();
        $this->assertDatabaseHas('employees', ['employee_id' => $employee->employee_id]);

        $this->get(route('employees.show', $employee))->assertOk();
        $this->get(route('employees.edit', $employee))->assertOk();

        $this->put(route('employees.update', $employee), [
            ...$data,
            'firstname' => 'Arun Updated',
        ])->assertRedirect(route('employees.index'));

        $this->assertDatabaseHas('employees', [
            'employee_id' => $employee->employee_id,
            'firstname' => 'Arun Updated',
        ]);

        $this->delete(route('employees.destroy', $employee))
            ->assertRedirect(route('employees.index'));

        $this->assertDatabaseMissing('employees', ['employee_id' => $employee->employee_id]);
    }

    public function test_employee_list_can_search_filter_and_export_matching_records(): void
    {
        Employee::create([
            'firstname' => 'Arun',
            'lastname' => 'Kumar',
            'date_of_birth' => '1995-05-14',
            'education_qualification' => 'B.E',
            'address' => '1 Main Street',
            'email' => 'arun@example.com',
            'phone' => '9841012345',
        ]);
        Employee::create([
            'firstname' => 'Meena',
            'lastname' => 'Devi',
            'date_of_birth' => '1994-06-15',
            'education_qualification' => 'MBA',
            'address' => '2 Main Street',
            'email' => 'meena@example.com',
            'phone' => '9841012346',
        ]);

        $this->get(route('employees.index', [
            'search' => 'Arun',
            'education' => 'B.E',
            'per_page' => 25,
        ]))
            ->assertOk()
            ->assertSee('Arun')
            ->assertDontSee('Meena');

        $this->get(route('employees.export', [
            'search' => 'Arun',
            'education' => 'B.E',
        ]))
            ->assertOk()
            ->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_employee_validation_rejects_invalid_data(): void
    {
        $this->from(route('employees.create'))
            ->post(route('employees.store'), [])
            ->assertRedirect(route('employees.create'))
            ->assertSessionHasErrors([
                'firstname',
                'lastname',
                'date_of_birth',
                'education_qualification',
                'address',
                'email',
                'phone',
            ]);
    }
}
