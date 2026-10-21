@extends('layouts.app')

@section('content')
@php
    $sortUrl = fn ($col) => request()->fullUrlWithQuery([
        'sort' => $col,
        'dir'  => (request('sort') === $col && request('dir') === 'asc') ? 'desc' : 'asc',
    ]);
    $arrow = fn ($col) => request('sort') === $col
        ? (request('dir') === 'asc' ? ' ▲' : ' ▼') : '';
@endphp

<div class="row g-3 mb-4">
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="text-uppercase small text-muted fw-semibold">Total employees</div><div class="fs-2 fw-bold mt-2">{{ $employees->total() }}</div></div>
                <span class="dashboard-icon icon-orange"><i class="bi bi-people"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="text-uppercase small text-muted fw-semibold">Qualifications listed</div><div class="fs-2 fw-bold mt-2">{{ count($qualifications) }}</div></div>
                <span class="dashboard-icon icon-purple"><i class="bi bi-mortarboard"></i></span>
            </div>
        </div>
    </div>
    <div class="col-12 col-sm-6 col-xl-4">
        <div class="dashboard-card p-3 h-100">
            <div class="d-flex justify-content-between align-items-start">
                <div><div class="text-uppercase small text-muted fw-semibold">Current page</div><div class="fs-2 fw-bold mt-2">{{ $employees->count() }}</div></div>
                <span class="dashboard-icon icon-green"><i class="bi bi-person-check"></i></span>
            </div>
        </div>
    </div>
</div>

{{-- Search + Filter --}}
<div class="filter-card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('employees.index') }}" class="filter-form">
            <div class="filter-field filter-search">
                <i class="bi bi-search" aria-hidden="true"></i>
                <input type="text" name="search" class="form-control"
                       placeholder="Search name, email or phone..." value="{{ request('search') }}">
            </div>
            <div class="filter-field">
                <select name="education" class="form-select">
                    <option value="">All Qualifications</option>
                    @foreach($qualifications as $q)
                        <option value="{{ $q }}" @selected(request('education') === $q)>{{ $q }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn btn-dark filter-submit"><i class="bi bi-search me-1"></i> Search</button>
            <a href="{{ route('employees.index') }}" class="btn btn-outline-secondary filter-reset">Reset</a>
        </form>
    </div>
</div>

{{-- Table --}}
<div class="records-card overflow-hidden">
    <div class="records-heading d-flex justify-content-between align-items-center gap-2 px-4 py-3">
        <h2 class="h6 mb-0 fw-bold"><i class="bi bi-briefcase me-2"></i>Employee Records ({{ $employees->total() }})</h2>
        <span class="small text-muted">Showing {{ $employees->firstItem() ?? 0 }}–{{ $employees->lastItem() ?? 0 }} of {{ $employees->total() }} results</span>
    </div>
    <div class="table-responsive">
        <table class="table employee-table table-hover align-middle mb-0">
            <thead class="table-light">
                <tr>
                    <th><a href="{{ $sortUrl('employee_id') }}">ID{{ $arrow('employee_id') }}</a></th>
                    <th>Photo</th>
                    <th><a href="{{ $sortUrl('firstname') }}">First Name{{ $arrow('firstname') }}</a></th>
                    <th><a href="{{ $sortUrl('lastname') }}">Last Name{{ $arrow('lastname') }}</a></th>
                    <th><a href="{{ $sortUrl('education_qualification') }}">Qualification{{ $arrow('education_qualification') }}</a></th>
                    <th><a href="{{ $sortUrl('email') }}">Email{{ $arrow('email') }}</a></th>
                    <th>Phone</th>
                    <th class="text-end">Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($employees as $employee)
                    <tr>
                        <td data-label="ID">{{ $employee->employee_id }}</td>
                        <td data-label="Photo">
                            @if($employee->photo)
                                <img src="{{ asset('storage/' . $employee->photo) }}" class="avatar" alt="photo">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($employee->full_name) }}&size=45" class="avatar" alt="avatar">
                            @endif
                        </td>
                        <td data-label="First Name">{{ $employee->firstname }}</td>
                        <td data-label="Last Name">{{ $employee->lastname }}</td>
                        <td data-label="Qualification">{{ $employee->education_qualification }}</td>
                        <td data-label="Email">{{ $employee->email }}</td>
                        <td data-label="Phone">{{ $employee->phone }}</td>
                        <td data-label="Actions" class="text-end">
                            <a href="{{ route('employees.show', $employee) }}" class="btn btn-sm btn-info text-white" title="View"><i class="bi bi-eye"></i></a>
                            <a href="{{ route('employees.edit', $employee) }}" class="btn btn-sm btn-warning" title="Edit"><i class="bi bi-pencil"></i></a>
                            <form action="{{ route('employees.destroy', $employee) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Delete this employee?')">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger" title="Delete"><i class="bi bi-trash"></i></button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="8" class="text-center text-muted py-4">No employees found.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="card-footer employee-pagination d-flex flex-wrap align-items-center gap-3">
        <form method="GET" action="{{ route('employees.index') }}" class="d-flex align-items-center gap-2">
            @foreach(['search', 'education', 'sort', 'dir'] as $filter)
                @if(request($filter) !== null)
                    <input type="hidden" name="{{ $filter }}" value="{{ request($filter) }}">
                @endif
            @endforeach
            <label for="per-page" class="small text-muted text-nowrap">Rows per page</label>
            <select id="per-page" name="per_page" class="form-select form-select-sm"
                    onchange="this.form.submit()" aria-label="Rows per page">
                @foreach($pageSizes as $size)
                    <option value="{{ $size }}" @selected($employees->perPage() === $size)>{{ $size }}</option>
                @endforeach
            </select>
        </form>
        {{ $employees->links('pagination::bootstrap-5') }}
    </div>
</div>

@endsection