@extends('layouts.app')
@section('title', 'Team Members')
@section('page-title', 'Team Members')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">
  <div>
    <h4 class="mb-0 fw-bold">Team Members</h4>
    <p class="text-muted small mb-0">Manage accounts for the applications support team</p>
  </div>
  <a href="{{ route('users.create') }}" class="btn btn-primary">
    <i class="bi bi-person-plus me-2"></i>Add Member
  </a>
</div>

<div class="card border-0 shadow-sm">
  <div class="table-responsive">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-light">
        <tr>
          <th>Name</th>
          <th>Employee ID</th>
          <th>Department</th>
          <th>Email</th>
          <th>Role</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $member)
          <tr>
            <td class="fw-medium">{{ $member->name }}</td>
            <td class="small text-muted">{{ $member->employee_id ?: '—' }}</td>
            <td class="small">{{ $member->department ?: '—' }}</td>
            <td class="small text-muted">{{ $member->email }}</td>
            <td>
              <span class="badge bg-{{ $member->role === 'admin' ? 'danger' : 'secondary' }}">
                {{ ucfirst($member->role) }}
              </span>
            </td>
            <td>
              <a href="{{ route('users.edit', $member->id) }}"
                 class="btn btn-sm btn-outline-secondary">Edit</a>
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-5">No team members yet.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>

@endsection
