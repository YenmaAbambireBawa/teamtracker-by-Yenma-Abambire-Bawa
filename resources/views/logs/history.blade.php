@extends('layouts.app')
@section('title', 'Activity History')
@section('page-title', 'Activity History')

@section('content')

<div class="mb-3">
  <a href="{{ route('logs.daily', ['date' => $date]) }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Daily View
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-8">

    <div class="card border-0 shadow-sm mb-4">
      <div class="card-body">
        <h5 class="fw-bold mb-1">{{ $activity->title }}</h5>
        <p class="text-muted small mb-0">
          <i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($date)->format('l, d M Y') }}
          &nbsp;·&nbsp;
          <span class="badge bg-light text-dark">{{ $activity->category }}</span>
        </p>
      </div>
    </div>

    @if($logs->isEmpty())
      <div class="card border-0 shadow-sm">
        <div class="card-body text-center py-5 text-muted">
          <i class="bi bi-clock-history display-4 d-block mb-3"></i>
          No updates recorded for this activity on this date.
        </div>
      </div>
    @else
      <div class="card border-0 shadow-sm">
        <div class="card-header bg-white fw-semibold">
          <i class="bi bi-clock-history me-2 text-primary"></i>
          {{ $logs->count() }} update(s) recorded
        </div>
        <div class="list-group list-group-flush">
          @foreach($logs as $log)
            <div class="list-group-item row-{{ $log->status }} py-3">
              <div class="d-flex justify-content-between align-items-start">
                <div>
                  <span class="badge badge-{{ $log->status }} me-2">{{ ucfirst($log->status) }}</span>
                  <span class="fw-semibold">{{ $log->user->name }}</span>
                  @if($log->user->employee_id)
                    <span class="text-muted small ms-1">({{ $log->user->employee_id }})</span>
                  @endif
                  @if($log->user->department)
                    <span class="badge bg-light text-muted ms-2 small">{{ $log->user->department }}</span>
                  @endif
                </div>
                <div class="text-end">
                  <div class="fw-medium small">{{ $log->created_at->format('H:i:s') }}</div>
                  <div class="text-muted" style="font-size:.75rem">
                    {{ \Carbon\Carbon::parse($date)->format('d M Y') }}
                  </div>
                </div>
              </div>
              @if($log->remark)
                <div class="mt-2 text-muted small fst-italic border-start ps-3 ms-1">
                  {{ $log->remark }}
                </div>
              @endif
            </div>
          @endforeach
        </div>
      </div>
    @endif

  </div>
</div>

@endsection
