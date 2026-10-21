@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Edit Employee #{{ $employee->employee_id }}</h5></div>
    <div class="card-body">
        <form action="{{ route('employees.update', $employee) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            @include('employees._form')
            <div class="mt-4">
                <button class="btn btn-primary">Update</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection