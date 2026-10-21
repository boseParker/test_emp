<?php

namespace App\Exports;

use App\Models\Employee;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Employee::orderBy('employee_id')->get();
    }

    public function headings(): array
    {
        return ['employee_id', 'firstname', 'lastname', 'date_of_birth',
                'education_qualification', 'address', 'email', 'phone'];
    }

    public function map($employee): array
    {
        return [
            $employee->employee_id,
            $employee->firstname,
            $employee->lastname,
            $employee->date_of_birth->format('Y-m-d'),
            $employee->education_qualification,
            $employee->address,
            $employee->email,
            $employee->phone,
        ];
    }
}