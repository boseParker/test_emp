<?php

namespace App\Exports;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class EmployeesExport implements FromCollection, WithHeadings, WithMapping
{
    public function __construct(
        private Builder $query,
        private string $sort = 'employee_id',
        private string $direction = 'desc'
    ) {
    }

    public function collection()
    {
        return $this->query->orderBy($this->sort, $this->direction)->get();
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