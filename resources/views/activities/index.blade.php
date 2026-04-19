@extends('layouts.app')
@section('title', 'Manage Activities')
@section('page-title', 'Manage Activities')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-0 fw-bold">Activities</h4>
    <p class="text-muted small mb-0">Manage the daily checklist for the team</p>
  </div>
  <a href="{{ route('activities.create') }}" class="btn btn-primary">
    <i class="bi bi-plus-circle me-2"></i>Add Activity
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Activity</th>
          <th>Category</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($activities as $activity)
          <tr>
            <td>
              <div class="fw-medium">{{ $activity->title }}</div>
              @if($activity->description)
                <div class="text-muted small">{{ Str::limit($activity->description, 70) }}</div>
              @endif
            </td>
            <td><span class="badge bg-light text-dark">{{ $activity->category }}</span></td>
            <td>
              <span class="badge bg-{{ $activity->is_active ? 'success' : 'secondary' }}">
                {{ $activity->is_active ? 'Active' : 'Inactive' }}
              </span>
            </td>
            <td>
              <a href="{{ route('activities.edit', $activity->id) }}"
                 class="btn btn-sm btn-outline-secondary me-1">Edit</a>
              <form method="POST" action="{{ route('activities.destroy', $activity->id) }}"
                    class="d-inline"
                    onsubmit="return confirm('Remove this activity? All its logs will also be deleted.')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
              </form>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="4" class="text-center text-muted py-5">
              No activities yet.
              <a href="{{ route('activities.create') }}">Create one.</a>
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
