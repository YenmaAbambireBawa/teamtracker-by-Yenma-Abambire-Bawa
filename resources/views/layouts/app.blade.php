<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Team Activity Tracker')</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
  <style>
    :root {
      --sidebar-width: 240px;
      --topbar-height: 56px;
      --color-primary: #2563eb;
      --color-done: #16a34a;
      --color-pending: #d97706;
    }

    body { background: #f1f5f9; font-family: 'Segoe UI', system-ui, sans-serif; }

    /* ── Sidebar ── */
    .sidebar {
      position: fixed; top: 0; left: 0; bottom: 0;
      width: var(--sidebar-width);
      background: #1e293b;
      display: flex; flex-direction: column;
      z-index: 100;
    }
    .sidebar-brand {
      padding: 1rem 1.25rem;
      border-bottom: 1px solid #334155;
    }
    .sidebar-brand .brand-name { color: #f8fafc; font-weight: 700; font-size: 1rem; }
    .sidebar-brand .brand-sub  { color: #94a3b8; font-size: .72rem; }
    .sidebar-nav { flex: 1; padding: .75rem 0; overflow-y: auto; }
    .sidebar-nav .nav-link {
      color: #94a3b8; padding: .55rem 1.25rem;
      border-radius: 0; display: flex; align-items: center; gap: .6rem;
      font-size: .875rem; transition: background .15s, color .15s;
    }
    .sidebar-nav .nav-link:hover { background: #334155; color: #f8fafc; }
    .sidebar-nav .nav-link.active { background: var(--color-primary); color: #fff; }
    .sidebar-nav .nav-section {
      padding: .75rem 1.25rem .25rem;
      color: #475569; font-size: .7rem; font-weight: 700;
      text-transform: uppercase; letter-spacing: .05em;
    }
    .sidebar-user {
      padding: .75rem 1.25rem;
      border-top: 1px solid #334155;
    }
    .sidebar-user .user-name { color: #f8fafc; font-size: .825rem; font-weight: 600; }
    .sidebar-user .user-role { color: #94a3b8; font-size: .72rem; }

    /* ── Main ── */
    .main-wrap { margin-left: var(--sidebar-width); min-height: 100vh; }
    .topbar {
      height: var(--topbar-height);
      background: #fff;
      border-bottom: 1px solid #e2e8f0;
      display: flex; align-items: center; padding: 0 1.5rem;
      position: sticky; top: 0; z-index: 50;
    }
    .page-content { padding: 1.75rem 1.5rem; }

    /* ── Cards & tables ── */
    .stat-card { border: none; border-radius: .75rem; }
    .table th { font-size: .78rem; text-transform: uppercase; letter-spacing: .04em; color: #64748b; }

    /* ── Status badges & row colours ── */
    .badge-done    { background: #dcfce7; color: var(--color-done); }
    .badge-pending { background: #fef3c7; color: var(--color-pending); }
    .row-done    { background: #f0fdf4; }
    .row-pending { background: #fffbeb; }

    /* ── Mobile ── */
    @media (max-width: 767px) {
      .sidebar { transform: translateX(-100%); transition: transform .25s; }
      .sidebar.open { transform: translateX(0); }
      .main-wrap { margin-left: 0; }
      .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,.4); z-index: 99; }
      .sidebar-overlay.open { display: block; }
    }
  </style>
  @stack('styles')
</head>
<body>

{{-- Sidebar overlay (mobile) --}}
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

{{-- Sidebar --}}
<nav class="sidebar" id="sidebar">
  <div class="sidebar-brand">
    <div class="brand-name"><i class="bi bi-activity me-2"></i>Activity Tracker</div>
    <div class="brand-sub">Applications Support Team</div>
  </div>

  <div class="sidebar-nav">
    <div class="nav-section">Main</div>
    <a href="{{ route('dashboard') }}"
       class="nav-link {{ request()->routeIs('dashboard') || request()->routeIs('logs.*') ? 'active' : '' }}">
      <i class="bi bi-grid-1x2"></i> Daily Dashboard
    </a>
    <a href="{{ route('reports') }}"
       class="nav-link {{ request()->routeIs('reports') ? 'active' : '' }}">
      <i class="bi bi-bar-chart-line"></i> Reports
    </a>

    @if(session('user_role') === 'admin')
    <div class="nav-section mt-2">Admin</div>
    <a href="{{ route('activities.index') }}"
       class="nav-link {{ request()->routeIs('activities.*') ? 'active' : '' }}">
      <i class="bi bi-list-check"></i> Activities
    </a>
    <a href="{{ route('users.index') }}"
       class="nav-link {{ request()->routeIs('users.*') ? 'active' : '' }}">
      <i class="bi bi-people"></i> Team Members
    </a>
    @endif
  </div>

  <div class="sidebar-user">
    <div class="user-name">{{ session('user_name') }}</div>
    <div class="user-role">{{ ucfirst(session('user_role')) }}</div>
    <form method="POST" action="{{ route('logout') }}" class="mt-2">
      @csrf
      <button type="submit" class="btn btn-sm btn-outline-secondary w-100">
        <i class="bi bi-box-arrow-left me-1"></i> Logout
      </button>
    </form>
  </div>
</nav>

{{-- Main --}}
<div class="main-wrap">
  <div class="topbar">
    <button class="btn btn-sm btn-outline-secondary d-md-none me-3" onclick="openSidebar()">
      <i class="bi bi-list"></i>
    </button>
    <span class="fw-semibold text-secondary">@yield('page-title')</span>
    <span class="ms-auto text-muted small d-none d-md-block">{{ now()->format('l, d M Y') }}</span>
  </div>

  <div class="page-content">
    {{-- Flash messages --}}
    @if(session('success'))
      <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if(session('error'))
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif
    @if($errors->any())
      <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="bi bi-exclamation-circle me-2"></i>
        {{ $errors->first() }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
      </div>
    @endif

    @yield('content')
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  function openSidebar()  { document.getElementById('sidebar').classList.add('open'); document.getElementById('sidebar-overlay').classList.add('open'); }
  function closeSidebar() { document.getElementById('sidebar').classList.remove('open'); document.getElementById('sidebar-overlay').classList.remove('open'); }
</script>
@stack('scripts')
</body>
</html>
