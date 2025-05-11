@extends('layouts.app')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="display-5 fw-bold text-primary">Edit Profile</h1>
            <p class="lead text-muted">Update your account information and preferences</p>
        </div>
        <div>
            <a href="{{ route('profile.show') }}" class="btn btn-outline-primary me-2">
                <i class="fas fa-arrow-left"></i> Back to Profile
            </a>
            <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                <i class="fas fa-tachometer-alt"></i> Dashboard
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-md-4 mb-4 d-flex">
            <div class="card border-0 shadow-sm w-100">
                <div class="card-body text-center p-4">                    @if(Auth::user()->image)
                        <img src="../../../storage/{{ Auth::user()->image }}" class="img-fluid rounded-circle mb-3" 
                             style="width: 150px; height: 150px; object-fit: cover;" alt="{{ Auth::user()->name }}">
                    @else
                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center mx-auto mb-3" 
                             style="width: 150px; height: 150px; font-size: 60px;">
                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                        </div>
                    @endif
                    
                    <h3 class="fw-bold">{{ Auth::user()->name }}</h3>
                    <p class="text-muted">{{ Auth::user()->job ?? 'Not specified' }}</p>
                    <span class="badge bg-{{ Auth::user()->roles->pluck('name')->first() === 'admin' ? 'danger' : 'success' }} mb-3">
                        {{ ucfirst(Auth::user()->roles->pluck('name')->first()) }}
                    </span>
                    
                    <div class="d-flex justify-content-center">
                        <span class="badge bg-light text-dark me-2">
                            <i class="fas fa-envelope me-1"></i> {{ Auth::user()->email }}
                        </span>
                        @if(Auth::user()->phone)
                            <span class="badge bg-light text-dark">
                                <i class="fas fa-phone me-1"></i> {{ Auth::user()->phone }}
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-md-8">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-user-edit text-primary me-2"></i>Update Profile Information
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-lock text-primary me-2"></i>Update Password
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-white py-3 text-danger">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-exclamation-triangle text-danger me-2"></i>Delete Account
                    </h5>
                </div>
                <div class="card-body">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
