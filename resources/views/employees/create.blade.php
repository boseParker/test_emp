@extends('layouts.app')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">Add Employee</h5></div>
    <div class="card-body">
        <form action="{{ route('employees.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            @include('employees._form')
            <div class="mt-4">
                <button class="btn btn-primary">Save</button>
                <a href="{{ route('employees.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection