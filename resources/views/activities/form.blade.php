@extends('layouts.app')
@section('title', $activity ? 'Edit Activity' : 'New Activity')
@section('page-title', $activity ? 'Edit Activity' : 'Add Activity')

@section('content')

<div class="mb-3">
  <a href="{{ route('activities.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Activities
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-{{ $activity ? 'pencil-square' : 'plus-circle' }} me-2 text-primary"></i>
        {{ $activity ? 'Edit Activity' : 'Add New Activity' }}
      </div>
      <div class="card-body">
        <form method="POST"
              action="{{ $activity ? route('activities.update', $activity->id) : route('activities.store') }}">
          @csrf
          @if($activity) @method('PUT') @endif

          <div class="mb-3">
            <label class="form-label fw-semibold">Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control"
                   value="{{ old('title', $activity?->title) }}"
                   placeholder="e.g. Daily SMS count vs SMS count from logs" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Category <span class="text-danger">*</span></label>
            <input type="text" name="category" class="form-control" list="category-suggestions"
                   value="{{ old('category', $activity?->category) }}"
                   placeholder="e.g. SMS Monitoring" required>
            <datalist id="category-suggestions">
              <option value="SMS Monitoring">
              <option value="System Health">
              <option value="Transaction Monitoring">
              <option value="Operations">
              <option value="General">
            </datalist>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Description
              <span class="text-muted fw-normal small">(optional)</span>
            </label>
            <textarea name="description" rows="3" class="form-control"
                      placeholder="Describe what this activity involves…">{{ old('description', $activity?->description) }}</textarea>
          </div>

          @if($activity)
            <div class="mb-4">
              <div class="form-check form-switch">
                <input class="form-check-input" type="checkbox" name="is_active"
                       id="is_active" value="1"
                       {{ old('is_active', $activity->is_active) ? 'checked' : '' }}>
                <label class="form-check-label fw-semibold" for="is_active">Active</label>
              </div>
              <div class="text-muted small">Inactive activities won't appear on the daily dashboard.</div>
            </div>
          @endif

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-2"></i>{{ $activity ? 'Save Changes' : 'Create Activity' }}
            </button>
            <a href="{{ route('activities.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
