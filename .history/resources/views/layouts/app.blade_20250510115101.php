<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Employee Attendance') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
        
        <!-- Custom CSS -->
        <style>
            body {
                background-color: #f8f9fa;
                color: #212529;
            }
            .navbar {
                box-shadow: 0 2px 4px rgba(0,0,0,.08);
            }
            .navbar-brand {
                font-weight: 600;
                letter-spacing: 0.5px;
            }
            .nav-link {
                font-weight: 500;
            }
            .dropdown-menu {
                border: none;
                box-shadow: 0 4px 8px rgba(0,0,0,.1);
            }
            .footer {
                margin-top: 5rem;
                padding: 2rem 0;
                border-top: 1px solid #e9ecef;
            }
        </style>
        
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased d-flex flex-column min-vh-100">
        <nav class="navbar navbar-expand-lg navbar-light bg-white sticky-top">
            <div class="container">
                <a class="navbar-brand text-primary" href="{{ route('dashboard') }}">
                    <i class="fas fa-user-clock me-2"></i>
                    Employee Attendance
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <ul class="navbar-nav me-auto">
                        @auth
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-1"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('attendance.active-users') ? 'active' : '' }}" href="{{ route('attendance.active-users') }}">
                                    <i class="fas fa-users me-1"></i> Active Users
                                </a>
                            </li>                            @if(Auth::user()->hasRole('admin'))
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('users.index') ? 'active' : '' }}" href="{{ route('users.index') }}">
                                        <i class="fas fa-user-cog me-1"></i> Manage Users
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link {{ request()->routeIs('employees.all') ? 'active' : '' }}" href="{{ route('employees.all') }}">
                                        <i class="fas fa-id-badge me-1"></i> All Employees
                                    </a>
                                </li>
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="fas fa-file-export me-1"></i> Export
                                    </a>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <a class="dropdown-item" href="{{ route('employees.export.csv') }}">
                                                <i class="fas fa-file-csv me-2"></i> Export as CSV
                                            </a>
                                        </li>
                                        <li>
                                            <a class="dropdown-item" href="{{ route('employees.export.pdf') }}">
                                                <i class="fas fa-file-pdf me-2"></i> Export as PDF
                                            </a>
                                        </li>
                                    </ul>
                                </li>
                            @endif
                        @endauth
                    </ul>
                    
                    <ul class="navbar-nav">
                        @guest
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="fas fa-sign-in-alt me-1"></i> Login
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="{{ route('register') }}">
                                    <i class="fas fa-user-plus me-1"></i> Register
                                </a>
                            </li>
                        @else
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    @if(Auth::user()->image)
                                        <img src="{{ asset('storage/'.Auth::user()->image) }}" class="rounded-circle me-2" width="30" height="30" alt="{{ Auth::user()->name }}">
                                    @else
                                        <div class="rounded-circle bg-primary text-white d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px; font-size: 12px;">
                                            {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.show') }}">
                                            <i class="fas fa-user me-2"></i> My Profile
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="{{ route('profile.edit') }}">
                                            <i class="fas fa-user-edit me-2"></i> Edit Profile
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" id="logout-form">
                                            @csrf
                                            <a class="dropdown-item" href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                                <i class="fas fa-sign-out-alt me-2"></i> Logout
                                            </a>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        @endguest
                    </ul>
                </div>
            </div>
        </nav>

        <main class="flex-grow-1 py-4">
            @if(session('success'))
                <div class="container">
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="container">
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li><i class="fas fa-exclamation-triangle me-2"></i> {{ $error }}</li>
                            @endforeach
                        </ul>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                </div>
            @endif

            {{ $slot ?? '' }}
            @yield('content')
        </main>

        <footer class="footer bg-white mt-auto">
            <div class="container">
                <div class="row">
                    <div class="col-md-6">
                        <h5 class="text-primary mb-3">Employee Attendance System</h5>
                        <p class="text-muted">A comprehensive solution for tracking employee attendance and managing workforce data.</p>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-primary mb-3">Quick Links</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><a href="{{ route('dashboard') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Dashboard</a></li>
                            <li class="mb-2"><a href="{{ route('attendance.active-users') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>Active Users</a></li>
                            <li class="mb-2"><a href="{{ route('profile.show') }}" class="text-decoration-none text-muted"><i class="fas fa-angle-right me-2"></i>My Profile</a></li>
                        </ul>
                    </div>
                    <div class="col-md-3">
                        <h5 class="text-primary mb-3">Contact</h5>
                        <ul class="list-unstyled">
                            <li class="mb-2"><i class="fas fa-envelope me-2 text-muted"></i>support@example.com</li>
                            <li class="mb-2"><i class="fas fa-phone me-2 text-muted"></i>+1 (123) 456-7890</li>
                            <li class="mb-2"><i class="fas fa-map-marker-alt me-2 text-muted"></i>123 Main St, City</li>
                        </ul>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-md-6">
                        <p class="text-muted mb-0">&copy; {{ date('Y') }} Employee Attendance System. All rights reserved.</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item"><a href="#" class="text-muted text-decoration-none">Privacy Policy</a></li>
                            <li class="list-inline-item ms-3"><a href="#" class="text-muted text-decoration-none">Terms of Service</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>

        <!-- Bootstrap JS Bundle -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js"></script>
        <!-- Font Awesome -->
        <script src="https://kit.fontawesome.com/3b0c858f61.js" crossorigin="anonymous"></script>
        
        @stack('scripts')
    </body>
</html>
