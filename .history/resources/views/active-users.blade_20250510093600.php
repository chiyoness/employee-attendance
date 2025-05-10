@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">Active Employees</h1>
            <p class="lead text-muted">Employees currently checked in</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('dashboard') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Dashboard
            </a>
            <div class="dropdown">
                <button class="btn btn-success dropdown-toggle" type="button" id="exportDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-file-export"></i> Export
                </button>
                <ul class="dropdown-menu" aria-labelledby="exportDropdown">
                    <li><a class="dropdown-item" href="#" id="exportExcel">Excel</a></li>
                    <li><a class="dropdown-item" href="#" id="exportCSV">CSV</a></li>
                    <li><a class="dropdown-item" href="#" id="exportPDF">PDF</a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-users text-primary me-2"></i>Currently Active Employees
            </h5>
        </div>
        <div class="card-body">
            @if($activeUsers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover" id="employeesTable">
                        <thead class="table-light">
                            <tr>
                                <th><i class="fas fa-user text-primary me-2"></i>Name</th>
                                <th><i class="fas fa-envelope text-primary me-2"></i>Email</th>
                                <th><i class="fas fa-briefcase text-primary me-2"></i>Job</th>
                                <th><i class="fas fa-phone text-primary me-2"></i>Phone</th>
                                <th><i class="fas fa-clock text-primary me-2"></i>Check-In Time</th>
                                <th><i class="fas fa-hourglass-half text-primary me-2"></i>Duration</th>
                                <th><i class="fas fa-cogs text-primary me-2"></i>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($activeUsers as $user)
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
                                                <div class="fw-bold">{{ $user->name }}</div>
                                                <span class="badge bg-{{ $user->hasRole('admin') ? 'danger' : 'success' }}">
                                                    {{ ucfirst($user->roles->pluck('name')->first()) }}
                                                </span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ $user->email }}</td>
                                    <td>{{ $user->job ?? 'Not specified' }}</td>
                                    <td>{{ $user->phone ?? 'Not specified' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($user->attendance->check_in_time)->format('M d, Y - h:i A') }}</td>
                                    <td>
                                        <span class="badge bg-info text-dark">
                                            {{ $user->attendance->check_in_time->diffForHumans(null, true) }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('users.show', $user->id) }}" class="btn btn-sm btn-outline-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            @if(Auth::user()->hasRole('admin'))
                                                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-sm btn-outline-secondary">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="alert alert-info" role="alert">
                    <i class="fas fa-info-circle me-2"></i> No users are currently checked in.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/3b0c858f61.js" crossorigin="anonymous"></script>
@endpush
