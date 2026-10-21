<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

use Illuminate\Validation\Rule;
use App\Models\Employee;

class EmployeeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $employee = $this->route('employee');
        
        $id = $employee instanceof Employee ? $employee->employee_id : null;

        return [
            'firstname'               => 'required|string|max:100',
            'lastname'                => 'required|string|max:100',
            'date_of_birth'           => 'required|date|before:today',
            'education_qualification' => ['required', Rule::in(Employee::QUALIFICATIONS)],
            'address'                 => 'required|string|max:500',
            'email'                   => ['required', 'email', Rule::unique('employees', 'email')->ignore($id, 'employee_id')],
            'phone'                   => 'required|digits:10',
            'photo'                   => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'resume'                  => 'nullable|mimes:pdf,doc,docx|max:5120',
        ];
    }
}
