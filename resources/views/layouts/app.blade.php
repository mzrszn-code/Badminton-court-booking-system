<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flotilla Badminton Centre') - Badminton Court Booking System</title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    
    <!-- FullCalendar (for calendar view) -->
    @stack('styles')
    
    <style>
        :root {
            /* Color Palette - Modern Dark Theme */
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            
            /* Dark Theme Colors */
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --bg-input: #1e293b;
            --border-color: #334155;
            
            /* Text Colors */
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            
            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --gradient-secondary: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);
            
            /* Shadows */
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.4);
            --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
            
            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
            
            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            line-height: 1.6;
            min-height: 100vh;
        }
        
        /* Scrollbar Styling */
        ::-webkit-scrollbar {
            width: 8px;
            height: 8px;
        }
        
        ::-webkit-scrollbar-track {
            background: var(--bg-dark);
        }
        
        ::-webkit-scrollbar-thumb {
            background: var(--border-color);
            border-radius: 4px;
        }
        
        ::-webkit-scrollbar-thumb:hover {
            background: var(--text-muted);
        }
        
        /* Navigation */
        .navbar {
            background: rgba(30, 41, 59, 0.8);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid var(--border-color);
            padding: 1.25rem 2rem;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-container {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .navbar-brand {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            text-decoration: none;
            color: var(--text-primary);
            font-weight: 700;
            font-size: 1.5rem;
        }
        
        .navbar-brand i {
            color: var(--primary);
            font-size: 1.75rem;
        }
        
        .navbar-nav {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            list-style: none;
        }
        
        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 0.625rem 1rem;
            border-radius: var(--radius-sm);
            font-weight: 500;
            font-size: 0.875rem;
            transition: var(--transition-fast);
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }
        
        .nav-link:hover, .nav-link.active {
            color: var(--text-primary);
            background: var(--bg-card-hover);
        }
        
        .nav-link.active {
            background: var(--primary);
            color: white;
        }
        
        .navbar-actions {
            display: flex;
            align-items: center;
            gap: 1rem;
        }
        
        /* Buttons */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.625rem 1.25rem;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.875rem;
            text-decoration: none;
            border: none;
            cursor: pointer;
            transition: var(--transition-fast);
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: white;
            box-shadow: var(--shadow-glow);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 0 30px rgba(99, 102, 241, 0.5);
        }
        
        .btn-secondary {
            background: var(--bg-card);
            color: var(--text-primary);
            border: 1px solid var(--border-color);
        }
        
        .btn-secondary:hover {
            background: var(--bg-card-hover);
        }
        
        .btn-success {
            background: var(--gradient-success);
            color: white;
        }
        
        .btn-danger {
            background: var(--danger);
            color: white;
        }
        
        .btn-sm {
            padding: 0.375rem 0.75rem;
            font-size: 0.75rem;
        }
        
        .btn-lg {
            padding: 0.875rem 1.75rem;
            font-size: 1rem;
        }
        
        /* Main Container */
        .main-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: 2rem;
        }
        
        /* Cards */
        .card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            transition: var(--transition-normal);
        }
        
        .card:hover {
            border-color: var(--primary);
            box-shadow: var(--shadow-glow);
        }
        
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid var(--border-color);
        }
        
        .card-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }
        
        .glassmorphism {
            background: rgba(30, 41, 59, 0.6);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .stat-card {
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-lg);
            padding: 1.5rem;
            display: flex;
            align-items: center;
            gap: 1rem;
            transition: var(--transition-normal);
        }
        
        .stat-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }
        
        .stat-icon {
            width: 60px;
            height: 60px;
            border-radius: var(--radius-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.5rem;
            color: white;
        }
        
        .stat-icon.primary { background: var(--gradient-primary); }
        .stat-icon.secondary { background: var(--gradient-secondary); }
        .stat-icon.success { background: var(--gradient-success); }
        .stat-icon.accent { background: var(--gradient-accent); }
        
        .stat-content h3 {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            line-height: 1;
        }
        
        .stat-content p {
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-top: 0.25rem;
        }
        
        /* Forms */
        .form-group {
            margin-bottom: 1.25rem;
        }
        
        .form-label {
            display: block;
            font-weight: 600;
            font-size: 0.875rem;
            color: var(--text-secondary);
            margin-bottom: 0.5rem;
        }
        
        .form-control {
            width: 100%;
            padding: 0.75rem 1rem;
            background: var(--bg-input);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            font-size: 0.875rem;
            transition: var(--transition-fast);
        }
        
        .form-control:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
        }
        
        .form-control::placeholder {
            color: var(--text-muted);
        }
        
        select.form-control {
            cursor: pointer;
        }
        
        textarea.form-control {
            min-height: 100px;
            resize: vertical;
        }
        
        /* Tables */
        .table-container {
            overflow-x: auto;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-color);
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 1rem;
            text-align: left;
            border-bottom: 1px solid var(--border-color);
        }
        
        th {
            background: var(--bg-card);
            font-weight: 600;
            font-size: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: var(--text-secondary);
        }
        
        tr:hover td {
            background: var(--bg-card-hover);
        }
        
        /* Badges */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            font-size: 0.75rem;
            font-weight: 600;
        }
        
        .badge-pending {
            background: rgba(245, 158, 11, 0.2);
            color: #f59e0b;
        }
        
        .badge-approved {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .badge-rejected {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .badge-cancelled {
            background: rgba(100, 116, 139, 0.2);
            color: #64748b;
        }
        
        .badge-completed {
            background: rgba(59, 130, 246, 0.2);
            color: #3b82f6;
        }
        
        .badge-available {
            background: rgba(16, 185, 129, 0.2);
            color: #10b981;
        }
        
        .badge-maintenance {
            background: rgba(239, 68, 68, 0.2);
            color: #ef4444;
        }
        
        .badge-booked {
            background: rgba(99, 102, 241, 0.2);
            color: #6366f1;
        }
        
        /* Alerts */
        .alert {
            padding: 1rem 1.25rem;
            border-radius: var(--radius-md);
            margin-bottom: 1.5rem;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        
        .alert-success {
            background: rgba(16, 185, 129, 0.15);
            border: 1px solid rgba(16, 185, 129, 0.3);
            color: #10b981;
        }
        
        .alert-error {
            background: rgba(239, 68, 68, 0.15);
            border: 1px solid rgba(239, 68, 68, 0.3);
            color: #ef4444;
        }
        
        .alert-warning {
            background: rgba(245, 158, 11, 0.15);
            border: 1px solid rgba(245, 158, 11, 0.3);
            color: #f59e0b;
        }
        
        /* Grid Layout */
        .grid {
            display: grid;
            gap: 1.5rem;
        }
        
        .grid-cols-2 { grid-template-columns: repeat(2, 1fr); }
        .grid-cols-3 { grid-template-columns: repeat(3, 1fr); }
        .grid-cols-4 { grid-template-columns: repeat(4, 1fr); }
        
        @media (max-width: 1024px) {
            .grid-cols-4, .grid-cols-3 { grid-template-columns: repeat(2, 1fr); }
        }
        
        @media (max-width: 768px) {
            .grid-cols-4, .grid-cols-3, .grid-cols-2 { grid-template-columns: 1fr; }
            .navbar-nav { display: none; }
            .main-container { padding: 1rem; }
        }
        
        /* Page Header */
        .page-header {
            margin-bottom: 2rem;
        }
        
        .page-title {
            font-size: 2rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: 0.5rem;
        }
        
        .page-subtitle {
            color: var(--text-secondary);
            font-size: 1rem;
        }
        
        /* Pagination */
        .pagination {
            display: flex;
            gap: 0.5rem;
            justify-content: center;
            margin-top: 2rem;
        }
        
        .pagination a, .pagination span {
            padding: 0.5rem 1rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.875rem;
        }
        
        .pagination a:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }
        
        .pagination .active span {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }
        
        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 3rem;
            color: var(--text-secondary);
        }
        
        .empty-state i {
            font-size: 3rem;
            margin-bottom: 1rem;
            color: var(--text-muted);
        }
        
        /* Dropdown */
        .dropdown {
            position: relative;
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            min-width: 200px;
            padding: 0.5rem;
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1000;
        }
        
        .dropdown:hover .dropdown-menu {
            display: block;
        }
        
        .dropdown-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.625rem 1rem;
            color: var(--text-secondary);
            text-decoration: none;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
        }
        
        .dropdown-item:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }
        
        /* User Avatar */
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--gradient-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: 700;
            color: white;
            font-size: 1rem;
        }
        
        /* Footer */
        .footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            padding: 2rem;
            margin-top: 4rem;
            text-align: center;
            color: var(--text-secondary);
            font-size: 0.875rem;
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .animate-fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.5; }
        }
        
        .animate-pulse {
            animation: pulse 2s infinite;
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="/images/logo.png" alt="FBC Logo" style="height: 50px; width: 50px; object-fit: contain;">
                <span>Flotilla Badminton Centre</span>
            </a>
            
            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="{{ route('courts.index') }}" class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }}"><i class="fas fa-table-tennis-paddle-ball"></i> Courts</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> About Us</a></li>
                @auth
                    <li><a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"><i class="fas fa-ticket"></i> My Bookings</a></li>
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fas fa-gauge-high"></i> Admin</a></li>
                    @endif
                @endauth
            </ul>
            
            <div class="navbar-actions">
                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @else
                    <div class="dropdown">
                        <div style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                            <div class="user-avatar">
                                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                            </div>
                            <span style="font-weight: 500; color: var(--text-primary);">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="dropdown-menu">
                            <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="fas fa-gauge"></i> Dashboard</a>
                            <a href="{{ route('profile.show') }}" class="dropdown-item"><i class="fas fa-user"></i> Profile</a>
                            <a href="{{ route('bookings.index') }}" class="dropdown-item"><i class="fas fa-ticket"></i> My Bookings</a>
                            <hr style="border-color: var(--border-color); margin: 0.5rem 0;">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item" style="width: 100%; border: none; background: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest
            </div>
        </div>
    </nav>
    
    <!-- Main Content -->
    <main class="main-container animate-fade-in">
        @if(session('success'))
            <div class="alert alert-success">
                <i class="fas fa-check-circle"></i>
                {{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                {{ session('error') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert alert-error">
                <i class="fas fa-exclamation-circle"></i>
                <div>
                    @foreach($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            </div>
        @endif
        
        @yield('content')
    </main>
    
    <!-- Footer -->
    <footer class="footer">
        <p>&copy; {{ date('Y') }} BadmintonBook by Group Flotilla. All rights reserved.</p>
    </footer>
    
    @stack('scripts')
</body>
</html>
