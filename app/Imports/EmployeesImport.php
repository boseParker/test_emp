<?php

namespace App\Imports;

use App\Models\Employee;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class EmployeesImport implements ToModel, WithHeadingRow, WithValidation
{
    private function parseDate(mixed $value): ?string
    {
        if (empty($value)) {
            return null;
        }
        if (is_numeric($value)) {
            return Date::excelToDateTimeObject($value)->format('Y-m-d');
        }
        try {
            return Carbon::parse($value)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }

    public function prepareForValidation($data, $index)
    {
        $data['date_of_birth'] = $this->parseDate($data['date_of_birth'] ?? null);
        $data['phone'] = isset($data['phone']) ? (string) $data['phone'] : null;

        return $data;
    }

    public function model(array $row)
    {
        return new Employee([
            'firstname'               => $row['firstname'],
            'lastname'                => $row['lastname'],
            'date_of_birth'           => $this->parseDate($row['date_of_birth']),
            'education_qualification' => $row['education_qualification'],
            'address'                 => $row['address'],
            'email'                   => $row['email'],
            'phone'                   => (string) $row['phone'],
        ]);
    }

    public function rules(): array
    {
        return [
            'firstname'               => 'required|string|max:100',
            'lastname'                => 'required|string|max:100',
            'date_of_birth'           => 'required|date',
            'education_qualification' => 'required|string|max:100',
            'address'                 => 'required|string',
            'email'                   => 'required|email|unique:employees,email',
            'phone'                   => 'required|digits:10',
        ];
    }
}