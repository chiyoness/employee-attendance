@extends('layouts.app')

@section('content')
<div class="container py-4">    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">Users Management</h1>
            <p class="lead text-muted">View and manage all users in the system</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export"></i> Export Employees
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="{{ route('employees.export.excel') }}">Excel</a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export.csv') }}">CSV</a></li>
                    <li><a class="dropdown-item" href="{{ route('employees.export.pdf') }}">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-filter text-primary me-2"></i>Filter Users
            </h5>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('users.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="role" class="form-label">Role</label>
                    <select name="role" id="role" class="form-select">
                        <option value="">All Roles</option>
                        <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="employee" {{ request('role') == 'employee' ? 'selected' : '' }}>Employee</option>
                    </select>
                </div>
                <div class="col-md-4">
                    <label for="search" class="form-label">Search by Name or Email</label>
                    <input type="text" class="form-control" id="search" name="search" value="{{ request('search') }}" placeholder="Enter name or email">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="fas fa-search me-1"></i> Search
                    </button>
                    <a href="{{ route('users.index') }}" class="btn btn-outline-secondary">
                        <i class="fas fa-redo me-1"></i> Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-body">
            @if($users->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user text-primary me-2"></i>User</th>
                                <th><i class="fas fa-envelope text-primary me-2"></i>Email</th>
                                <th><i class="fas fa-briefcase text-primary me-2"></i>Job</th>
                                <th><i class="fas fa-phone text-primary me-2"></i>Phone</th>
                                <th><i class="fas fa-user-tag text-primary me-2"></i>Role</th>
                                <th><i class="fas fa-tools text-primary me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($users as $user)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($user->image)
                                                <img src="{{ asset('storage/'.$user->image) }}" class="rounded-circle me-2" width="40" height="40" alt="{{ $user->name }}">
                                            @else
                                                <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 40px; height: 40px;">
                                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                                </div>
                                            @endif
                                            <div>
                                                {{ $user->name }}
                                                <div class="small text-muted">
                                                    {{ $user->created_at->format('M d, Y') }}
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->job ?? 'Not specified' }}</td>
                                    <td>{{ $user->phone ?? 'Not specified' }}</td>
                                    <td>
                                        <span class="badge bg-{{ $user->roles->pluck('name')->first() === 'admin' ? 'danger' : 'success' }}">
                                            {{ ucfirst($user->roles->pluck('name')->first()) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            @if(Auth::id() !== $user->id)
                                                <form method="POST" action="{{ route('users.destroy', $user->id) }}" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this user?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn btn-sm btn-outline-danger">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-4">
                    {{ $users->withQueryString()->links() }}
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i> No users found matching your criteria.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
