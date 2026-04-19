@extends('layouts.app')
@section('title', 'Daily Dashboard')
@section('page-title', 'Daily Dashboard')

@section('content')

{{-- Date picker + stats --}}
<div class="d-flex flex-wrap align-items-center justify-content-between mb-4 gap-3">
  <div>
    <h4 class="mb-0 fw-bold">{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}</h4>
    <p class="text-muted small mb-0">Activity status for the day</p>
  </div>
  <form method="GET" action="{{ route('logs.daily') }}" class="d-flex align-items-center gap-2">
    <input type="date" name="date" value="{{ $date }}" max="{{ date('Y-m-d') }}"
           class="form-control form-control-sm" onchange="this.form.submit()">
  </form>
</div>

{{-- Summary stats --}}
<div class="row g-3 mb-4">
  <div class="col-4">
    <div class="card stat-card shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-primary">{{ $totalCount }}</div>
      <div class="text-muted small">Total</div>
    </div>
  </div>
  <div class="col-4">
    <div class="card stat-card shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-success">{{ $doneCount }}</div>
      <div class="text-muted small">Done</div>
    </div>
  </div>
  <div class="col-4">
    <div class="card stat-card shadow-sm text-center py-3">
      <div class="fs-2 fw-bold text-warning">{{ $pendingCount }}</div>
      <div class="text-muted small">Pending</div>
    </div>
  </div>
</div>

{{-- Activities grouped by category --}}
@if($grouped->isEmpty())
  <div class="card border-0 shadow-sm">
    <div class="card-body text-center py-5 text-muted">
      <i class="bi bi-inbox display-4 d-block mb-3"></i>
      No active activities configured yet.
      @if(session('user_role') === 'admin')
        <a href="{{ route('activities.create') }}">Create your first activity</a>
      @endif
    </div>
  </div>
@else
  @foreach($grouped as $category => $activities)
    <div class="mb-4">
      <h6 class="text-uppercase text-muted fw-bold small mb-2 border-bottom pb-1">
        <i class="bi bi-tag me-1"></i>{{ $category }}
      </h6>
      <div class="card border-0 shadow-sm">
        <div class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th style="width:35%">Activity</th>
                <th style="width:12%">Status</th>
                <th style="width:20%">Updated By</th>
                <th style="width:13%">Time</th>
                <th style="width:20%">Actions</th>
              </tr>
            </thead>
            <tbody>
              @foreach($activities as $activity)
                @php $log = $activity->latest_log; @endphp
                <tr class="{{ $log ? 'row-'.$log->status : '' }}">
                  <td>
                    <div class="fw-medium">{{ $activity->title }}</div>
                    @if($activity->description)
                      <div class="text-muted small">{{ Str::limit($activity->description, 80) }}</div>
                    @endif
                    @if($log && $log->remark)
                      <div class="text-muted small fst-italic mt-1">
                        <i class="bi bi-chat-left-text me-1"></i>{{ Str::limit($log->remark, 80) }}
                      </div>
                    @endif
                  </td>
                  <td>
                    @if($log)
                      <span class="badge badge-{{ $log->status }}">{{ ucfirst($log->status) }}</span>
                    @else
                      <span class="badge bg-secondary">No update</span>
                    @endif
                  </td>
                  <td>
                    @if($log)
                      <div class="fw-medium small">{{ $log->user->name }}</div>
                      @if($log->user->employee_id)
                        <div class="text-muted" style="font-size:.75rem">{{ $log->user->employee_id }}</div>
                      @endif
                    @else
                      <span class="text-muted small">—</span>
                    @endif
                  </td>
                  <td class="small text-muted">
                    {{ $log ? $log->created_at->format('H:i') : '—' }}
                  </td>
                  <td>
                    <a href="{{ route('logs.update-form', ['id' => $activity->id, 'date' => $date]) }}"
                       class="btn btn-sm btn-primary me-1">
                      <i class="bi bi-pencil-square me-1"></i>Update
                    </a>
                    <a href="{{ route('logs.history', ['id' => $activity->id, 'date' => $date]) }}"
                       class="btn btn-sm btn-outline-secondary" title="View history">
                      <i class="bi bi-clock-history"></i>
                    </a>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
    </div>
  @endforeach
@endif

@endsection
