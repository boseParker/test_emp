<?php

namespace Database\Seeders;

use App\Models\Employee;
use Illuminate\Database\Seeder;

class EmployeeSeeder extends Seeder
{
    public function run(): void
    {
        $quals = Employee::QUALIFICATIONS;

        for ($i = 1; $i <= 25; $i++) {
            Employee::create([
                'firstname'               => 'Employee' . $i,
                'lastname'                => 'Test' . $i,
                'date_of_birth'           => now()->subYears(rand(22, 45))->format('Y-m-d'),
                'education_qualification' => $quals[array_rand($quals)],
                'address'                 => $i . ', Main Street, Chennai, Tamil Nadu',
                'email'                   => "employee{$i}@example.com",
                'phone'                   => '98' . rand(10000000, 99999999),
            ]);
        }
    }
}