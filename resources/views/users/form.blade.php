@extends('layouts.app')
@section('title', isset($member) ? 'Edit Member' : 'Add Member')
@section('page-title', isset($member) ? 'Edit Team Member' : 'Add Team Member')

@section('content')

<div class="mb-3">
  <a href="{{ route('users.index') }}" class="btn btn-sm btn-outline-secondary">
    <i class="bi bi-arrow-left me-1"></i>Back to Team Members
  </a>
</div>

<div class="row justify-content-center">
  <div class="col-lg-6">
    <div class="card border-0 shadow-sm">
      <div class="card-header bg-white fw-semibold">
        <i class="bi bi-person-{{ isset($member) ? 'gear' : 'plus' }} me-2 text-primary"></i>
        {{ isset($member) ? 'Edit Team Member' : 'Add Team Member' }}
      </div>
      <div class="card-body">
        <form method="POST"
              action="{{ isset($member) ? route('users.update', $member->id) : route('users.store') }}">
          @csrf
          @if(isset($member)) @method('PUT') @endif

          <div class="mb-3">
            <label class="form-label fw-semibold">Full Name <span class="text-danger">*</span></label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $member?->name) }}" required>
          </div>

          <div class="mb-3">
            <label class="form-label fw-semibold">Email Address <span class="text-danger">*</span></label>
            <input type="email" name="email" class="form-control"
                   value="{{ old('email', $member?->email) }}"
                   {{ isset($member) ? 'readonly' : 'required' }}>
            @if(isset($member))
              <div class="text-muted small mt-1">Email cannot be changed after account creation.</div>
            @endif
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Employee ID</label>
              <input type="text" name="employee_id" class="form-control"
                     value="{{ old('employee_id', $member?->employee_id) }}"
                     placeholder="e.g. MEM002"
                     {{ isset($member) ? 'readonly' : '' }}>
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Role <span class="text-danger">*</span></label>
              <select name="role" class="form-select" required>
                <option value="member" {{ old('role', $member?->role) === 'member' ? 'selected' : '' }}>Member</option>
                <option value="admin"  {{ old('role', $member?->role) === 'admin'  ? 'selected' : '' }}>Admin</option>
              </select>
            </div>
          </div>

          <div class="row g-3 mb-3">
            <div class="col-md-6">
              <label class="form-label fw-semibold">Department</label>
              <input type="text" name="department" class="form-control"
                     value="{{ old('department', $member?->department) }}"
                     placeholder="e.g. Applications Support">
            </div>
            <div class="col-md-6">
              <label class="form-label fw-semibold">Phone</label>
              <input type="text" name="phone" class="form-control"
                     value="{{ old('phone', $member?->phone) }}"
                     placeholder="+233 XX XXX XXXX">
            </div>
          </div>

          <hr>

          <div class="mb-3">
            <label class="form-label fw-semibold">
              Password
              @if(!isset($member)) <span class="text-danger">*</span> @endif
              @if(isset($member))
                <span class="text-muted fw-normal small">(leave blank to keep current)</span>
              @endif
            </label>
            <input type="password" name="password" class="form-control"
                   {{ !isset($member) ? 'required' : '' }}
                   placeholder="{{ isset($member) ? 'Leave blank to keep current password' : 'Min. 6 characters' }}">
          </div>

          <div class="mb-4">
            <label class="form-label fw-semibold">
              Confirm Password
              @if(!isset($member)) <span class="text-danger">*</span> @endif
            </label>
            <input type="password" name="password_confirmation" class="form-control"
                   placeholder="Repeat password">
          </div>

          <div class="d-flex gap-2">
            <button type="submit" class="btn btn-primary px-4">
              <i class="bi bi-save me-2"></i>{{ isset($member) ? 'Save Changes' : 'Create Account' }}
            </button>
            <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">Cancel</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>

@endsection
