<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Employee;
use App\Http\Requests\EmployeeRequest;
use App\Exports\EmployeesExport;
use App\Imports\EmployeesImport;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Validators\ValidationException as ExcelValidationException;
use Illuminate\Database\Eloquent\Builder;



class EmployeeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $this->employeeQuery($request);
        [$sort, $dir] = $this->sortOptions($request);

        $pageSizes = [10, 25, 50, 100];
        $perPage = (int) $request->input('per_page', 10);
        if (!in_array($perPage, $pageSizes, true)) {
            $perPage = 10;
        }

        $employees = $query->orderBY($sort,$dir)->paginate($perPage)->withQueryString();

        return view('employees.index',[
            'employees'=> $employees,
            'qualifications'=> Employee::QUALIFICATIONS,
            'pageSizes' => $pageSizes,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('employees.create', ['qualifications' => Employee::QUALIFICATIONS]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(EmployeeRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        }
        if ($request->hasFile('resume')) {
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        }

        Employee::create($data);

        return redirect()->route('employees.index')->with('success', 'Employee added successfully.');    }

    /**
     * Display the specified resource.
     */
    public function show(Employee $employee)
    {
        return view('employees.show', compact('employee'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Employee $employee)
    {
        return view('employees.edit', [
            'employee'       => $employee,
            'qualifications' => Employee::QUALIFICATIONS,
        ]);    }

    /**
     * Update the specified resource in storage.
     */
    public function update(EmployeeRequest $request, Employee $employee)
    {
        $data = $request->validated();

        if ($request->hasFile('photo')) {
            if ($employee->photo) {
                Storage::disk('public')->delete($employee->photo);
            }
            $data['photo'] = $request->file('photo')->store('photos', 'public');
        } else {
            unset($data['photo']);
        }

        if ($request->hasFile('resume')) {
            if ($employee->resume) {
                Storage::disk('public')->delete($employee->resume);
            }
            $data['resume'] = $request->file('resume')->store('resumes', 'public');
        } else {
            unset($data['resume']); 
        }

        $employee->update($data);

        return redirect()->route('employees.index')->with('success', 'Employee updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Employee $employee)
    {
        if ($employee->photo) {
            Storage::disk('public')->delete($employee->photo);
        }
        if ($employee->resume) {
            Storage::disk('public')->delete($employee->resume);
        }

        $employee->delete();

        return redirect()->route('employees.index')->with('success', 'Employee deleted successfully.');
    }


    public function export(Request $request)
    {
        [$sort, $dir] = $this->sortOptions($request);

        return Excel::download(
            new EmployeesExport($this->employeeQuery($request), $sort, $dir),
            'employees.xlsx'
        );
    }

    private function employeeQuery(Request $request): Builder
    {
        $query = Employee::query();

        if ($search = $request->search) {
            $query->where(function ($q) use ($search) {
                $q->where('firstname', 'like', "%{$search}%")
                    ->orWhere('lastname', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('education')) {
            $query->where('education_qualification', $request->education);
        }

        return $query;
    }

    private function sortOptions(Request $request): array
    {
        $sortable = ['employee_id', 'firstname', 'lastname', 'date_of_birth', 'education_qualification', 'email', 'phone'];
        $sort = in_array($request->sort, $sortable, true) ? $request->sort : 'employee_id';
        $dir = $request->dir === 'asc' ? 'asc' : 'desc';

        return [$sort, $dir];
    }


    public function import(Request $request)
    {
        $request->validate(['file' => 'required|mimes:xlsx,xls,csv|max:5120']);

        try {
            Excel::import(new EmployeesImport, $request->file('file'));
        } catch (ExcelValidationException $e) {
            $messages = collect($e->failures())
                ->map(fn ($f) => 'Row ' . $f->row() . ': ' . implode(', ', $f->errors()))
                ->all();

            return back()->withErrors($messages);
        }

        return redirect()->route('employees.index')->with('success', 'Employees imported successfully.');
    }
}
