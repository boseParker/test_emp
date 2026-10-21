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
        .app-sidebar { position: fixed; z-index: 1030; inset: 0 auto 0 0; width: 270px; height: 100vh; display: flex; flex-direction: column; overflow-y: auto; background: var(--navy); color: #b8c4d9; }
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
        .app-main { min-width: 0; flex: 1; margin-left: 270px; }
        .topbar { min-height: 78px; display: flex; align-items: center; justify-content: space-between; gap: 24px; padding: 16px 32px; background: var(--surface); border-bottom: 1px solid var(--line); }
        .topbar > div:first-child { min-width: 0; }
        .page-title { margin: 0; font-size: 22px; font-weight: 750; letter-spacing: -.02em; }
        .breadcrumb { margin: 4px 0 0; color: var(--muted); font-size: 12px; }
        .breadcrumb span { color: #a5b0c0; padding: 0 6px; }
        .topbar-actions { display: flex; flex: 0 0 auto; flex-wrap: nowrap; justify-content: flex-end; gap: 10px; }
        .topbar-actions .btn { white-space: nowrap; }
        .dashboard-content { width: 100%; max-width: 1440px; margin: 0 auto; padding: 28px 32px 42px; }
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
        .filter-form { display: grid; grid-template-columns: minmax(280px, 1fr) minmax(220px, .7fr) auto auto; gap: 10px; align-items: center; }
        .filter-field { min-width: 0; position: relative; }
        .filter-card .form-control, .filter-card .form-select { width: 100%; min-height: 44px; border-color: var(--line); border-radius: 10px; color: var(--ink); }
        .filter-search .form-control { padding-left: 38px; }
        .filter-search > i { position: absolute; z-index: 1; top: 50%; left: 14px; color: #8ca0ba; transform: translateY(-50%); }
        .filter-card .form-control:focus, .filter-card .form-select:focus { border-color: #9cb3d8; box-shadow: 0 0 0 .2rem rgba(74, 110, 170, .12); }
        .filter-submit, .filter-reset { min-height: 44px; white-space: nowrap; }
        .filter-submit { min-width: 96px; }
        .filter-reset { min-width: 72px; }
        .records-heading { border-bottom: 1px solid var(--line); }
        .employee-table { min-width: 920px; }
        .employee-table thead th { padding: 15px 18px; background: #f8fafd; color: #71829d; border-bottom: 1px solid var(--line); font-size: 11px; font-weight: 750; letter-spacing: .05em; text-transform: uppercase; white-space: nowrap; }
        .employee-table tbody td { padding: 14px 18px; border-color: var(--line); font-size: 14px; }
        .employee-table tbody tr:last-child td { border-bottom: 0; }
        .employee-table .btn { border-radius: 8px; }
        .employee-pagination { min-height: 70px; padding: 14px 20px; background: #fbfcfe; border-top: 1px solid var(--line); }
        .employee-pagination nav { margin-bottom: 0; }
        .employee-pagination .pagination { margin-bottom: 0; }
        .employee-pagination > nav { flex: 1 1 360px; }
        .employee-pagination form .form-select { min-width: 72px; }
        .employee-pagination form { margin: 0; }
        .employee-pagination > nav > div { align-items: center; }
        .employee-pagination > nav > div:first-child { display: none !important; }
        .employee-pagination > nav > div:nth-child(2) { display: flex !important; }
        .employee-pagination > nav p { margin-bottom: 0; }

        @media (max-width: 992px) {
            .app-sidebar { width: 224px; }
            .app-main { margin-left: 224px; }
            .topbar, .dashboard-content { padding-left: 22px; padding-right: 22px; }
            .topbar { align-items: flex-start; flex-direction: column; }
            .topbar-actions { flex-wrap: wrap; justify-content: flex-start; }
            .filter-form { grid-template-columns: minmax(220px, 1fr) minmax(170px, 1fr) auto auto; }
            .filter-submit, .filter-reset { width: 100%; }
        }
        @media (max-width: 640px) {
            .app-shell { display: block; }
            .app-sidebar { position: relative; width: 100%; height: auto; max-height: none; overflow: visible; }
            .app-main { margin-left: 0; }
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
            .filter-form { grid-template-columns: 1fr; gap: 10px; }
            .filter-submit, .filter-reset { width: 100%; }
            .employee-pagination { align-items: stretch !important; flex-direction: column; }
            .employee-pagination form, .employee-pagination > nav { width: 100%; }
            .employee-pagination form { justify-content: space-between; }
            .employee-pagination > nav > div:nth-child(2) { align-items: center; justify-content: space-between; gap: 12px; }
            .employee-pagination > nav > div:nth-child(2) > div:first-child { flex: 1 1 auto; min-width: 0; }
            .employee-pagination > nav > div:nth-child(2) > div:last-child { flex: 0 0 auto; max-width: 100%; overflow-x: auto; }
            .employee-pagination > nav .pagination { justify-content: center; flex-wrap: nowrap; }
            .employee-pagination > nav { flex-basis: auto; }
            .employee-pagination { padding: 14px 16px; }
            .records-card { border-radius: 14px; }
            .records-card .table-responsive { overflow: visible; }
            .employee-table { min-width: 0; display: block; }
            .employee-table thead { display: none; }
            .employee-table tbody { display: block; padding: 10px; background: #f8fafd; }
            .employee-table tbody tr { display: block; margin-bottom: 10px; padding: 8px 14px; background: #fff; border: 1px solid var(--line); border-radius: 10px; box-shadow: 0 2px 7px rgba(24, 44, 75, .04); }
            .employee-table tbody tr:last-child { margin-bottom: 0; }
            .employee-table tbody td { display: flex; align-items: center; justify-content: space-between; gap: 14px; min-height: 42px; padding: 9px 0; border: 0; text-align: right; overflow-wrap: anywhere; }
            .employee-table tbody td::before { flex: 0 0 92px; color: #71829d; content: attr(data-label); font-size: 10px; font-weight: 750; letter-spacing: .05em; text-align: left; text-transform: uppercase; }
            .employee-table tbody td[data-label="Photo"] { justify-content: space-between; }
            .employee-table tbody td[data-label="Photo"]::before { flex: 0 0 92px; align-self: center; }
            .employee-table tbody td[data-label="Photo"] img { width: 44px; height: 44px; flex: 0 0 44px; margin-left: auto; }
            .employee-table tbody td[data-label="Actions"] { justify-content: space-between; padding-top: 12px; border-top: 1px solid var(--line); }
            .employee-table tbody td[data-label="Actions"]::before { align-self: center; }
            .employee-table tbody td[data-label="Actions"] .d-inline { display: inline-flex !important; gap: 5px; }
            .employee-table tbody td[data-label="Actions"] form { margin-left: 0; }
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
            <a href="{{ route('employees.export', request()->only(['search', 'education', 'sort', 'dir'])) }}" class="sidebar-link">
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
                <a href="{{ route('employees.export', request()->only(['search', 'education', 'sort', 'dir'])) }}" class="btn btn-dark text-white"><i class="bi bi-download me-1"></i> Export Excel</a>
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