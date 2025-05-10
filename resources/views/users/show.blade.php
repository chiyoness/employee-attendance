@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">Employee Details</h1>
            <p class="lead text-muted">View employee information</p>
        </div>
        <div class="d-flex">
            <a href="{{ route('users.index') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Users
            </a>
            @if(Auth::user()->hasRole('admin'))
                <a href="{{ route('users.edit', $user->id) }}" class="btn btn-warning">
                    <i class="fas fa-edit"></i> Edit User
                </a>
            @endif
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-user text-primary me-2"></i>Employee Profile
            </h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3 text-center mb-4">
                    @if($user->image)
                        <img src="{{ asset('storage/'.$user->image) }}" class="rounded-circle img-fluid mb-3" style="max-width: 150px; height: auto;" alt="{{ $user->name }}">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" style="width: 150px; height: 150px; font-size: 60px;">
                            {{ strtoupper(substr($user->name, 0, 1)) }}
                        </div>
                    @endif                    <div>
                        <h4 class="fw-bold">{{ $user->name }}</h4>
                        <span class="badge bg-{{ $user->hasRole('admin') ? 'danger' : 'success' }} fs-6 mb-2">
                            {{ ucfirst($user->getRoleNames()->first() ?? 'User') }}
                        </span>
                    </div>
                </div>
                <div class="col-md-9">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted fs-6">Email Address</h6>
                                <p class="fs-5"><i class="fas fa-envelope text-primary me-2"></i>{{ $user->email }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted fs-6">Phone Number</h6>
                                <p class="fs-5"><i class="fas fa-phone text-primary me-2"></i>{{ $user->phone ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted fs-6">Job Title</h6>
                                <p class="fs-5"><i class="fas fa-briefcase text-primary me-2"></i>{{ $user->job ?? 'Not specified' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-4">
                                <h6 class="text-muted fs-6">Member Since</h6>
                                <p class="fs-5"><i class="fas fa-calendar text-primary me-2"></i>{{ $user->created_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-4">
        <div class="card-header bg-white py-3">
            <h5 class="card-title mb-0">
                <i class="fas fa-history text-primary me-2"></i>Recent Attendance
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover">
                    <thead class="table-light">
                        <tr>
                            <th>Check-In Time</th>
                            <th>Check-Out Time</th>
                            <th>Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($user->attendances()->latest()->take(5)->get() as $attendance)
                            <tr>
                                <td>{{ $attendance->check_in_time->format('M d, Y - h:i A') }}</td>
                                <td>
                                    @if($attendance->check_out_time)
                                        {{ $attendance->check_out_time->format('M d, Y - h:i A') }}
                                    @else
                                        <span class="badge bg-success">Currently Active</span>
                                    @endif
                                </td>
                                <td>
                                    @if($attendance->check_out_time)
                                        {{ $attendance->check_in_time->diff($attendance->check_out_time)->format('%H:%I:%S') }}
                                    @else
                                        <span class="badge bg-info text-dark">
                                            {{ $attendance->check_in_time->diffForHumans(null, true) }}
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">No attendance records found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="text-end mt-3">
                <a href="{{ route('attendance.history', $user->id) }}" class="btn btn-sm btn-outline-primary">
                    <i class="fas fa-eye"></i> View Full Attendance History
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
