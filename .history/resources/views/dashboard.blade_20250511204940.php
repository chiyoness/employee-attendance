@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1 class="display-5 fw-bold text-primary">Dashboard</h1>
            <p class="lead text-muted">Welcome to your employee attendance dashboard</p>
        </div>
    </div>

    <div class="row mb-4">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-gradient text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user fa-2x"></i>
                        </div>
                        <div>
                            <h4 class="card-title">Welcome, {{ Auth::user()->name }}</h4>
                            <h6 class="card-subtitle mb-2 text-muted">
                                <span class="badge bg-{{ Auth::user()->roles->pluck('name')->first() === 'admin' ? 'danger' : 'success' }}">
                                    {{ ucfirst(Auth::user()->roles->pluck('name')->first()) }}
                                </span>
                            </h6>
                        </div>
                    </div>
                    <p class="card-text">
                        <strong>Email:</strong> {{ Auth::user()->email }}<br>
                        <strong>Phone:</strong> {{ Auth::user()->phone ?? 'Not set' }}<br>
                        <strong>Job:</strong> {{ Auth::user()->job ?? 'Not set' }}
                    </p>
                    <div class="mt-auto">
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary">Edit Profile</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-success bg-gradient text-white rounded-circle p-3 me-3">
                            <i class="fas fa-clock fa-2x"></i>
                        </div>
                        <h4 class="card-title mb-0">Attendance</h4>
                    </div>
                    
                    <p class="card-text">Track your work hours by checking in and out.</p>
                      <div class="mt-auto">
                        <button id="checkInBtn" class="btn btn-success me-2">Check In</button>
                        <button id="checkOutBtn" class="btn btn-warning">Check Out</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Additional Features Section -->
    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-info bg-gradient text-white rounded-circle p-3 me-3">
                            <i class="fas fa-users fa-2x"></i>
                        </div>
                        <h4 class="card-title mb-0">Active Users</h4>
                    </div>
                    <p class="card-text">View all currently active users in the system.</p>
                    <div class="mt-auto">
                        <a href="{{ route('attendance.active-users') }}" class="btn btn-outline-info">View Active Users</a>
                    </div>
                </div>
            </div>
        </div>

        @if(Auth::user()->hasRole('admin'))
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-danger bg-gradient text-white rounded-circle p-3 me-3">
                            <i class="fas fa-user-shield fa-2x"></i>
                        </div>
                        <h4 class="card-title mb-0">Admin Tools</h4>
                    </div>
                    <p class="card-text">Manage users and export employee data.</p>
                    <div class="mt-auto">
                        <a href="{{ route('users.index') }}" class="btn btn-outline-danger">Manage Users</a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body d-flex flex-column">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-secondary bg-gradient text-white rounded-circle p-3 me-3">
                            <i class="fas fa-file-export fa-2x"></i>
                        </div>
                        <h4 class="card-title mb-0">Export Data</h4>
                    </div>
                    <p class="card-text">Download employee data in different formats.</p>
                    <div class="mt-auto">
                        <a href="{{ route('employees.export.csv') }}" class="btn btn-outline-secondary me-2">CSV</a>
                        <a href="{{ route('employees.export.pdf') }}" class="btn btn-outline-secondary">PDF</a>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@push('scripts')
<script src="https://kit.fontawesome.com/3b0c858f61.js" crossorigin="anonymous"></script>
@endpush
