@extends('layouts.app')
@section('title', 'Reports')
@section('page-title', 'Activity Reports')

@section('content')

{{-- Filters --}}
<div class="card border-0 shadow-sm mb-4">
  <div class="card-header bg-white fw-semibold">
    <i class="bi bi-funnel me-2 text-primary"></i>Filter Reports
  </div>
  <div class="card-body">
    <form method="GET" action="{{ route('reports') }}">
      <div class="row g-3">
        <div class="col-md-2">
          <label class="form-label small fw-semibold">From Date</label>
          <input type="date" name="from" value="{{ $fromDate }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">To Date</label>
          <input type="date" name="to" value="{{ $toDate }}" max="{{ date('Y-m-d') }}" class="form-control form-control-sm">
        </div>
        <div class="col-md-3">
          <label class="form-label small fw-semibold">Activity</label>
          <select name="activity_id" class="form-select form-select-sm">
            <option value="">All Activities</option>
            @foreach($activities as $act)
              <option value="{{ $act->id }}" {{ request('activity_id') == $act->id ? 'selected' : '' }}>
                {{ $act->title }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Team Member</label>
          <select name="user_id" class="form-select form-select-sm">
            <option value="">All Members</option>
            @foreach($users as $u)
              <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>
                {{ $u->name }}
              </option>
            @endforeach
          </select>
        </div>
        <div class="col-md-2">
          <label class="form-label small fw-semibold">Status</label>
          <select name="status" class="form-select form-select-sm">
            <option value="">All</option>
            <option value="done"    {{ request('status') === 'done'    ? 'selected' : '' }}>Done</option>
            <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
          </select>
        </div>
        <div class="col-md-1 d-flex align-items-end">
          <button type="submit" class="btn btn-primary btn-sm w-100">
            <i class="bi bi-search"></i>
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

{{-- Summary --}}
<div class="row g-3 mb-4">
  <div class="col-4">
    <div class="card stat-card border-0 shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-primary">{{ $summary['total'] }}</div>
      <div class="text-muted small">Total Logs</div>
    </div>
  </div>
  <div class="col-4">
    <div class="card stat-card border-0 shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-success">{{ $summary['done'] }}</div>
      <div class="text-muted small">Done</div>
    </div>
  </div>
  <div class="col-4">
    <div class="card stat-card border-0 shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-warning">{{ $summary['pending'] }}</div>
      <div class="text-muted small">Pending</div>
    </div>
  </div>
</div>

{{-- Results table --}}
<div class="card border-0 shadow-sm">
  <div class="card-header bg-white fw-semibold d-flex justify-content-between align-items-center">
    <span><i class="bi bi-table me-2 text-primary"></i>Results</span>
    <span class="text-muted small">{{ $summary['total'] }} record(s)</span>
  </div>
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Date</th>
          <th>Activity</th>
          <th>Category</th>
          <th>Status</th>
          <th>Updated By</th>
          <th>Time</th>
          <th>Remark</th>
        </tr>
      </thead>
      <tbody>
        @forelse($logs as $log)
          <tr class="row-{{ $log->status }}">
            <td class="small">{{ \Carbon\Carbon::parse($log->log_date)->format('D, d M Y') }}</td>
            <td class="fw-medium small">{{ $log->activity->title }}</td>
            <td><span class="badge bg-light text-dark small">{{ $log->activity->category }}</span></td>
            <td><span class="badge badge-{{ $log->status }}">{{ ucfirst($log->status) }}</span></td>
            <td>
              <div class="small fw-medium">{{ $log->user->name }}</div>
              @if($log->user->employee_id)
                <div class="text-muted" style="font-size:.75rem">{{ $log->user->employee_id }}</div>
              @endif
            </td>
            <td class="small text-muted">{{ $log->created_at->format('H:i') }}</td>
            <td class="small text-muted fst-italic">
              {{ $log->remark ? Str::limit($log->remark, 60) : '—' }}
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-5">
              <i class="bi bi-search display-6 d-block mb-2"></i>
              No activity logs found for the selected filters.
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
