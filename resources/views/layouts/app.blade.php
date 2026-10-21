<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Employee Management</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --navy: #0e172b;
            --navy-soft: #17223a;
            --ink: #132238;
            --muted: #728198;
            --line: #e5ebf3;
            --surface: #ffffff;
            --page: #f5f7fb;
            --orange: #f59b00;
        }

        * { box-sizing: border-box; }
        body { margin: 0; background: var(--page); color: var(--ink); font-family: Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        .app-shell { min-height: 100vh; display: flex; }
        .app-sidebar { width: 270px; flex: 0 0 270px; min-height: 100vh; display: flex; flex-direction: column; background: var(--navy); color: #b8c4d9; }
        .brand { display: flex; align-items: center; gap: 12px; min-height: 142px; padding: 24px 20px; border-bottom: 1px solid rgba(255,255,255,.08); }
        .brand-mark { width: 44px; height: 44px; display: grid; place-items: center; border-radius: 12px; background: var(--orange); color: var(--navy); font-size: 20px; font-weight: 800; box-shadow: 0 8px 18px rgba(245,155,0,.25); }
        .brand-name { color: #fff; font-size: 16px; font-weight: 750; line-height: 1.15; }
        .brand-subtitle { margin-top: 5px; color: var(--orange); font-size: 10px; font-weight: 700; letter-spacing: .08em; }
        .sidebar-section { padding: 32px 14px 0; }
        .sidebar-label { padding: 0 10px 10px; color: #72829f; font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; }
        .sidebar-link { display: flex; align-items: center; gap: 14px; padding: 12px 14px; border-left: 3px solid transparent; border-radius: 12px; color: #9eacc4; font-size: 14px; text-decoration: none; transition: .2s ease; }
        .sidebar-link:hover, .sidebar-link.active { color: var(--orange); background: var(--navy-soft); border-left-color: var(--orange); }
        .sidebar-link i { width: 18px; font-size: 17px; text-align: center; }
        .sidebar-user { display: flex; align-items: center; gap: 10px; margin-top: auto; padding: 18px 20px; border-top: 1px solid rgba(255,255,255,.08); }
        .user-avatar { width: 36px; height: 36px; display: grid; place-items: center; border: 1px solid var(--orange); border-radius: 50%; color: #fff; font-size: 12px; }
        .user-name { color: #fff; font-size: 12px; font-weight: 700; }
        .user-role { margin-top: 2px; color: #8291aa; font-size: 11px; }
        .app-main { min-width: 0; flex: 1; }
        .topbar { min-height: 78px; display: flex; align-items: center; justify-content: space-between; gap: 18px; padding: 16px 32px; background: var(--surface); border-bottom: 1px solid var(--line); }
        .page-title { margin: 0; font-size: 22px; font-weight: 750; letter-spacing: -.02em; }
        .breadcrumb { margin: 4px 0 0; color: var(--muted); font-size: 12px; }
        .breadcrumb span { color: #a5b0c0; padding: 0 6px; }
        .topbar-actions { display: flex; flex-wrap: wrap; justify-content: flex-end; gap: 10px; }
        .dashboard-content { max-width: 1440px; margin: 0 auto; padding: 28px 32px 42px; }
        .btn { border-radius: 11px; font-weight: 650; }
        .btn-primary { background: var(--orange); border-color: var(--orange); color: var(--navy); }
        .btn-primary:hover { background: #e58e00; border-color: #e58e00; color: var(--navy); }
        .btn-dark { background: #243149; border-color: #243149; }
        .avatar { width: 44px; height: 44px; object-fit: cover; border: 1px solid var(--line); border-radius: 50%; }
        th a { color: inherit; text-decoration: none; }
        th a:hover { text-decoration: underline; }
        .dashboard-card, .filter-card, .records-card { background: var(--surface); border: 1px solid var(--line); border-radius: 16px; box-shadow: 0 4px 14px rgba(24, 44, 75, .05); }
        .dashboard-icon { width: 52px; height: 52px; display: grid; place-items: center; border-radius: 12px; font-size: 22px; }
        .icon-orange { background: #fff0bf; color: #e79600; }
        .icon-purple { background: #e2e7ff; color: #5b62e9; }
        .icon-green { background: #d5f8e8; color: #0bb77a; }
        .icon-slate { background: #eef2f7; color: #43536b; }
        .status-online { color: #0bb77a; }
        .status-online i { font-size: 9px; vertical-align: middle; }
        .filter-card { padding: 4px; }
        .filter-card .form-control, .filter-card .form-select { min-height: 40px; border-color: var(--line); border-radius: 10px; color: var(--ink); }
        .filter-card .form-control:focus, .filter-card .form-select:focus { border-color: #9cb3d8; box-shadow: 0 0 0 .2rem rgba(74, 110, 170, .12); }
        .records-heading { border-bottom: 1px solid var(--line); }
        .employee-table { min-width: 920px; }
        .employee-table thead th { padding: 15px 18px; background: #f8fafd; color: #71829d; border-bottom: 1px solid var(--line); font-size: 11px; font-weight: 750; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }
        .employee-table tbody td { padding: 14px 18px; border-color: var(--line); font-size: 14px; }
        .employee-table tbody tr:last-child td { border-bottom: 0; }
        .employee-table .btn { border-radius: 8px; }
        .employee-pagination nav { margin-bottom: 0; }
        .employee-pagination .pagination { margin-bottom: 0; }
        .employee-pagination > nav { flex: 1 1 360px; }
        .employee-pagination form .form-select { min-width: 72px; }

        @media (max-width: 992px) {
            .app-sidebar { width: 224px; flex-basis: 224px; }
            .topbar, .dashboard-content { padding-left: 22px; padding-right: 22px; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .topbar-actions { justify-content: flex-start; }
        }
        @media (max-width: 640px) {
            .app-shell { display: block; }
            .app-sidebar { width: 100%; min-height: auto; }
            .brand { min-height: 76px; padding: 14px 18px; }
            .sidebar-section { display: none; }
            .sidebar-user { display: none; }
            .topbar, .dashboard-content { padding-left: 16px; padding-right: 16px; }
            .topbar { min-height: auto; padding-top: 18px; padding-bottom: 18px; }
            .page-title { font-size: 20px; }
            .topbar-actions, .topbar-actions .btn { width: 100%; }
            .topbar-actions .btn { flex: 1; }
            .dashboard-content { padding-top: 18px; }
            .records-heading { align-items: flex-start !important; flex-direction: column; padding-left: 16px !important; padding-right: 16px !important; }
            .filter-card .card-body { padding: 12px; }
        }
    </style>
</head>
<body>
<div class="app-shell">
    <aside class="app-sidebar">
        <a href="{{ route('employees.index') }}" class="brand text-decoration-none">
            <span class="brand-mark">CB</span>
            <span>
                <span class="brand-name d-block">creative bees</span>
                <span class="brand-subtitle d-block">HR SYSTEM TEST</span>
            </span>
        </a>
        <div class="sidebar-section">
            <div class="sidebar-label">Main operations</div>
            <a href="{{ route('employees.index') }}" class="sidebar-link {{ request()->routeIs('employees.index', 'employees.show', 'employees.edit') ? 'active' : '' }}">
                <i class="bi bi-people"></i><span>Employee Directory</span>
            </a>
            <a href="{{ route('employees.create') }}" class="sidebar-link {{ request()->routeIs('employees.create') ? 'active' : '' }}">
                <i class="bi bi-person-plus"></i><span>Add New Employee</span>
            </a>
        </div>
        <div class="sidebar-section">
            <div class="sidebar-label">Data exchange</div>
            <a href="{{ route('employees.export') }}" class="sidebar-link">
                <i class="bi bi-download"></i><span>Export to Excel (.xlsx)</span>
            </a>
            <a href="#" class="sidebar-link" data-bs-toggle="modal" data-bs-target="#importModal">
                <i class="bi bi-upload"></i><span>Import from Excel</span>
            </a>
        </div>
    </aside>
    <main class="app-main">
        <header class="topbar">
            <div>
                <h1 class="page-title">Employee Directory</h1>
                <p class="breadcrumb mb-0">Creative Bees <span>›</span> Employees List</p>
            </div>
            <div class="topbar-actions">
                <a href="{{ route('employees.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i> Add Employee</a>
                <a href="{{ route('employees.export') }}" class="btn btn-dark text-white"><i class="bi bi-download me-1"></i> Export Excel</a>
                <button class="btn btn-outline-secondary" data-bs-toggle="modal" data-bs-target="#importModal"><i class="bi bi-upload me-1"></i> Import Excel</button>
            </div>
        </header>
        <div class="dashboard-content">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @yield('content')
        </div>
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>