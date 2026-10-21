@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white d-flex justify-content-between">
        <h5 class="mb-0">Employee Details</h5>
        <a href="{{ route('employees.index') }}" class="btn btn-sm btn-secondary">Back</a>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-3 text-center mb-3">
                @if($employee->photo)
                    <img src="{{ asset('storage/' . $employee->photo) }}" class="img-thumbnail" style="max-height:220px" alt="photo">
                @else
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&size=200" class="img-thumbnail" alt="avatar">
                @endif
            </div>
            <div class="col-md-9">
                <table class="table table-borderless">
                    <tr><th width="220">Employee ID</th><td>{{ $employee->employee_id }}</td></tr>
                    <tr><th>Name</th><td>{{ $employee->full_name }}</td></tr>
                    <tr><th>Date of Birth</th><td>{{ $employee->date_of_birth->format('d M Y') }}</td></tr>
                    <tr><th>Education Qualification</th><td>{{ $employee->education_qualification }}</td></tr>
                    <tr><th>Address</th><td>{!! nl2br(e($employee->address)) !!}</td></tr>
                    <tr><th>Email</th><td>{{ $employee->email }}</td></tr>
                    <tr><th>Phone</th><td>{{ $employee->phone }}</td></tr>
                    <tr>
                        <th>Resume</th>
                        <td>
                            @if($employee->resume)
                                <a href="{{ asset('storage/' . $employee->resume) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-arrow-down"></i> Download Resume
                                </a>
                            @else
                                <span class="text-muted">Not uploaded</span>
                            @endif
                        </td>
                    </tr>
                </table>
                <a href="{{ route('employees.edit', $employee) }}" class="btn btn-warning">Edit</a>
            </div>
        </div>
    </div>
</div>
@endsection