@extends('layouts.app')
@section('title', 'Update Activity')
@section('page-title', 'Update Activity')

@section('content')

<div class="mb-3">
  <a href="{{ route('logs.daily', ['date' => $date]) }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Daily View
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-7">

    {{-- Activity info card --}}
    <div class="card border-0 shadow-sm mb-3">
      <div class="card-body">
        <div class="d-flex align-items-start gap-3">
          <div class="flex-shrink-0 bg-primary bg-opacity-10 rounded p-2">
            <i class="bi bi-activity text-primary fs-5"></i>
          </div>
          <div>
            <h5 class="mb-1 fw-bold">{{ $activity->title }}</h5>
            @if($activity->description)
              <p class="text-muted small mb-1">{{ $activity->description }}</p>
            @endif
            <span class="badge bg-light text-dark small">{{ $activity->category }}</span>
          </div>
        </div>
        <div class="mt-3 pt-3 border-top d-flex gap-4 text-muted small">
          <span><i class="bi bi-calendar3 me-1"></i>{{ \Carbon\Carbon::parse($date)->format('D, d M Y') }}</span>
          <span><i class="bi bi-person me-1"></i>{{ $currentUser->name }}
            @if($currentUser->employee_id)({{ $currentUser->employee_id }})@endif
          </span>
        </div>
      </div>
    </div>

    {{-- Previous log notice --}}
    @if($latestLog)
      <div class="alert alert-info small">
        <i class="bi bi-info-circle me-1"></i>
        Last updated at <strong>{{ $latestLog->created_at->format('H:i') }}</strong>
        by <strong>{{ $latestLog->user->name }}</strong>
        — status was <strong>{{ ucfirst($latestLog->status) }}</strong>
        @if($latestLog->remark) with remark: "{{ $latestLog->remark }}" @endif
      </div>
    @endif

    {{-- Update form --}}
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-pencil-square me-2 text-primary"></i>Record Update
      </div>
      <div class="card-body">
        <form method="POST" action="{{ route('logs.store') }}">
          @csrf
          <input type="hidden" name="activity_id" value="{{ $activity->id }}">
          <input type="hidden" name="log_date" value="{{ $date }}">

          <div class="mb-4">
            <label class="form-label fw-semibold">Status <span class="text-danger">*</span></label>
            <div class="d-flex gap-3">
              <div class="form-check form-check-inline p-0">
                <input class="btn-check" type="radio" name="status" id="status-done" value="done"
                       {{ ($latestLog && $latestLog->status === 'done') ? 'checked' : '' }} required>
                <label class="btn btn-outline-success px-4" for="status-done">
                  <i class="bi bi-check-circle me-2"></i>Done
                </label>
              </div>
              <div class="form-check form-check-inline p-0">
                <input class="btn-check" type="radio" name="status" id="status-pending" value="pending"
                       {{ ($latestLog && $latestLog->status === 'pending') ? 'checked' : '' }}>
                <label class="btn btn-outline-warning px-4" for="status-pending">
                  <i class="bi bi-clock me-2"></i>Pending
                </label>
              </div>
            </div>
            @error('status')
              <div class="text-danger small mt-1">{{ $message }}</div>
            @enderror
          </div>

          <div class="mb-4">
            <label for="remark" class="form-label fw-semibold">
              Remark <span class="text-muted fw-normal small">(optional)</span>
            </label>
            <textarea name="remark" id="remark" rows="3"
                      class="form-control"
                      placeholder="Add any notes, observations, or details about this activity…">{{ old('remark', $latestLog?->remark) }}</textarea>
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-2"></i>Save Update
            </button>
            <a href="{{ route('logs.daily', ['date' => $date]) }}" class="btn btn-outline-secondary">
              Cancel
            </a>
          </div>
        </form>
      </div>
    </div>

  </div>
</div>

@endsection
