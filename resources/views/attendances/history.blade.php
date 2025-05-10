@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">Attendance History</h1>
            <p class="lead text-muted">{{ $user->id === Auth::id() ? 'Your' : $user->name . '\'s' }} attendance records</p>
        </div>
        <a href="{{ route('dashboard') }}" class="btn btn-outline-primary">
            <i class="fas fa-arrow-left"></i> Back to Dashboard
        </a>
    </div>

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Date</th>
                            <th scope="col">Check In</th>
                            <th scope="col">Check Out</th>
                            <th scope="col">Duration</th>
                            <th scope="col">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($attendances as $index => $attendance)
                            <tr>
                                <td>{{ $attendances->firstItem() + $index }}</td>
                                <td>{{ $attendance->check_in_time->format('Y-m-d') }}</td>
                                <td>{{ $attendance->check_in_time->format('h:i A') }}</td>
                                <td>
                                    @if ($attendance->check_out_time)
                                        {{ $attendance->check_out_time->format('h:i A') }}
                                    @else
                                        <span class="badge bg-warning">Not checked out</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance->check_out_time)
                                        {{ $attendance->check_out_time->diffForHumans($attendance->check_in_time, true) }}
                                    @else
                                        <span class="badge bg-info">Ongoing</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($attendance->check_out_time)
                                        <span class="badge bg-success">Complete</span>
                                    @else
                                        <span class="badge bg-primary">Active</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4">
                                    <div class="text-muted mb-2">
                                        <i class="fas fa-calendar-times fa-3x"></i>
                                    </div>
                                    <h5>No attendance records found</h5>
                                    <p class="mb-0">There are no attendance records available.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <div class="d-flex justify-content-center mt-4">
        {{ $attendances->links() }}
    </div>
</div>
@endsection
