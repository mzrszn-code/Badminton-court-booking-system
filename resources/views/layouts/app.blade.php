<!DOCTYPE html>
<html lang="en" data-theme="dark">
<script>
    // Set theme immediately to avoid flash of wrong theme
    (function () {
        const savedTheme = localStorage.getItem('theme') || 'dark';
        document.documentElement.setAttribute('data-theme', savedTheme);
    })();
</script>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Flotilla Badminton Center') - Badminton Court Booking System</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">

    <!-- FullCalendar (for calendar view) -->
    @stack('styles')

    <!-- Livewire Styles -->
    @livewireStyles

    <style>
        :root {
            /* Color Palette - Base Colors */
            --primary: #6366f1;
            --primary-light: #818cf8;
            --primary-dark: #4f46e5;
            --secondary: #06b6d4;
            --accent: #f59e0b;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;

            /* Gradients */
            --gradient-primary: linear-gradient(135deg, #6366f1 0%, #8b5cf6 100%);
            --gradient-secondary: linear-gradient(135deg, #06b6d4 0%, #3b82f6 100%);
            --gradient-success: linear-gradient(135deg, #10b981 0%, #059669 100%);
            --gradient-accent: linear-gradient(135deg, #f59e0b 0%, #d97706 100%);

            /* Border Radius */
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;

            /* Transitions */
            --transition-fast: 0.15s ease;
            --transition-normal: 0.3s ease;
        }

        /* Dark Theme (Default) */
        [data-theme="dark"],
        :root {
            --bg-dark: #0f172a;
            --bg-card: #1e293b;
            --bg-card-hover: #334155;
            --bg-input: #1e293b;
            --border-color: #334155;
            --text-primary: #f8fafc;
            --text-secondary: #94a3b8;
            --text-muted: #64748b;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.3);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.4);
            --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
            --navbar-bg: rgba(30, 41, 59, 0.8);
        }

        /* Light Theme */
        [data-theme="light"] {
            --bg-dark: #f8fafc;
            --bg-card: #ffffff;
            --bg-card-hover: #f1f5f9;
            --bg-input: #ffffff;
            --border-color: #e2e8f0;
            --text-primary: #0f172a;
            --text-secondary: #475569;
            --text-muted: #94a3b8;
            --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
            --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
            --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.15);
            --navbar-bg: rgba(255, 255, 255, 0.8);
        }

        /* System preference detection for default theme */
        @media (prefers-color-scheme: light) {
            [data-theme="default"] {
                --bg-dark: #f8fafc;
                --bg-card: #ffffff;
                --bg-card-hover: #f1f5f9;
                --bg-input: #ffffff;
                --border-color: #e2e8f0;
                --text-primary: #0f172a;
                --text-secondary: #475569;
                --text-muted: #94a3b8;
                --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.05);
                --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.07);
                --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.1);
                --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.15);
                --navbar-bg: rgba(255, 255, 255, 0.8);
            }
        }

        @media (prefers-color-scheme: dark) {
            [data-theme="default"] {
                --bg-dark: #0f172a;
                --bg-card: #1e293b;
                --bg-card-hover: #334155;
                --bg-input: #1e293b;
                --border-color: #334155;
                --text-primary: #f8fafc;
                --text-secondary: #94a3b8;
                --text-muted: #64748b;
                --shadow-sm: 0 1px 2px rgba(0, 0, 0, 0.3);
                --shadow-md: 0 4px 6px rgba(0, 0, 0, 0.3);
                --shadow-lg: 0 10px 15px rgba(0, 0, 0, 0.4);
                --shadow-glow: 0 0 20px rgba(99, 102, 241, 0.3);
                --navbar-bg: rgba(30, 41, 59, 0.8);
            }
        }

        /* Theme transition for smooth switching */
        body,
        .navbar,
        .card,
        .btn,
        .form-control,
        .dropdown-menu {
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease, box-shadow 0.3s ease;
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

        /* Theme Toggle Button */
        .theme-toggle {
            position: relative;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .theme-toggle-btn {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-secondary);
            cursor: pointer;
            transition: var(--transition-fast);
        }

        .theme-toggle-btn:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
            border-color: var(--primary);
        }

        .theme-toggle-btn i {
            font-size: 1rem;
        }

        .theme-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem);
            right: 0;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-md);
            min-width: 160px;
            padding: 0.5rem;
            box-shadow: var(--shadow-lg);
            display: none;
            z-index: 1001;
        }

        .theme-toggle:hover .theme-dropdown,
        .theme-dropdown.show {
            display: block;
        }

        .theme-option {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.625rem 1rem;
            color: var(--text-secondary);
            cursor: pointer;
            border-radius: var(--radius-sm);
            font-size: 0.875rem;
            border: none;
            background: none;
            width: 100%;
            text-align: left;
            transition: var(--transition-fast);
        }

        .theme-option:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .theme-option.active {
            background: var(--primary);
            color: white;
        }

        .theme-option i {
            width: 16px;
            text-align: center;
        }

        /* Navigation */
        .navbar {
            background: var(--navbar-bg);
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

        .nav-link:hover,
        .nav-link.active {
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

        .stat-icon.primary {
            background: var(--gradient-primary);
        }

        .stat-icon.secondary {
            background: var(--gradient-secondary);
        }

        .stat-icon.success {
            background: var(--gradient-success);
        }

        .stat-icon.accent {
            background: var(--gradient-accent);
        }

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

        th,
        td {
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

        .grid-cols-2 {
            grid-template-columns: repeat(2, 1fr);
        }

        .grid-cols-3 {
            grid-template-columns: repeat(3, 1fr);
        }

        .grid-cols-4 {
            grid-template-columns: repeat(4, 1fr);
        }

        /* Mobile Menu Toggle */
        .mobile-menu-toggle {
            display: none;
            align-items: center;
            justify-content: center;
            width: 44px;
            height: 44px;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            cursor: pointer;
            transition: var(--transition-fast);
            z-index: 1002;
        }

        .mobile-menu-toggle:hover {
            background: var(--bg-card-hover);
            border-color: var(--primary);
        }

        .mobile-menu-toggle i {
            font-size: 1.25rem;
        }

        /* Mobile Navigation Overlay */
        .mobile-nav-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1001;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease;
        }

        .mobile-nav-overlay.active {
            opacity: 1;
            pointer-events: auto;
        }

        /* Mobile Navigation Menu */
        .mobile-nav {
            display: none;
            position: fixed;
            top: 0;
            right: -100%;
            width: 280px;
            max-width: 85%;
            height: 100%;
            background: var(--bg-card);
            border-left: 1px solid var(--border-color);
            z-index: 1002;
            transition: right 0.3s ease;
            overflow-y: auto;
        }

        .mobile-nav.active {
            right: 0;
        }

        .mobile-nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .mobile-nav-close {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background: transparent;
            border: none;
            color: var(--text-secondary);
            cursor: pointer;
            border-radius: var(--radius-sm);
            transition: var(--transition-fast);
        }

        .mobile-nav-close:hover {
            background: var(--bg-card-hover);
            color: var(--text-primary);
        }

        .mobile-nav-links {
            padding: 1rem;
            list-style: none;
        }

        .mobile-nav-links li {
            margin-bottom: 0.5rem;
        }

        .mobile-nav-links .nav-link {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.875rem 1rem;
            font-size: 1rem;
            border-radius: var(--radius-sm);
        }

        .mobile-nav-links .nav-link i {
            width: 20px;
            text-align: center;
        }

        .mobile-nav-actions {
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
        }

        .mobile-nav-actions .btn {
            width: 100%;
            justify-content: center;
        }

        .mobile-user-info {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid var(--border-color);
        }

        .mobile-user-info .user-avatar {
            width: 48px;
            height: 48px;
            font-size: 1.25rem;
        }

        .mobile-user-info .user-details {
            flex: 1;
        }

        .mobile-user-info .user-name {
            font-weight: 600;
            color: var(--text-primary);
        }

        .mobile-user-info .user-email {
            font-size: 0.75rem;
            color: var(--text-muted);
        }

        @media (max-width: 1024px) {

            .grid-cols-4,
            .grid-cols-3 {
                grid-template-columns: repeat(2, 1fr);
            }

            .navbar {
                padding: 1rem 1.5rem;
            }

            .navbar-brand span {
                font-size: 1.25rem;
            }
        }

        @media (max-width: 768px) {

            .grid-cols-4,
            .grid-cols-3,
            .grid-cols-2 {
                grid-template-columns: 1fr;
            }

            .navbar {
                padding: 0.875rem 1rem;
            }

            .navbar-nav {
                display: none;
            }

            .mobile-menu-toggle {
                display: flex;
            }

            .mobile-nav-overlay {
                display: block;
            }

            .mobile-nav {
                display: block;
            }

            .main-container {
                padding: 1rem;
            }

            .navbar-brand img {
                height: 45px;
                width: 45px;
            }

            .navbar-brand span {
                font-size: 1.1rem;
            }

            .navbar-actions {
                gap: 0.5rem;
            }

            .navbar-actions .btn {
                display: none;
            }

            .navbar-actions .dropdown {
                display: none;
            }

            .page-title {
                font-size: 1.5rem;
            }

            .page-subtitle {
                font-size: 0.875rem;
            }

            .hero-section {
                padding: 4rem 1.5rem;
                min-height: 60vh;
                border-radius: 16px;
            }

            .hero-section .hero-subtitle {
                font-size: 1rem;
            }

            .courts-hero {
                padding: 2.5rem 1.5rem;
                border-radius: 16px;
            }

            .stat-card {
                padding: 1.25rem;
            }

            .stat-icon {
                width: 50px;
                height: 50px;
                font-size: 1.25rem;
            }

            .stat-content h3 {
                font-size: 1.5rem;
            }

            .card {
                padding: 1.25rem;
            }

            .card-title {
                font-size: 1.1rem;
            }

            .btn {
                padding: 0.5rem 1rem;
                font-size: 0.8rem;
            }

            .btn-lg {
                padding: 0.75rem 1.25rem;
                font-size: 0.9rem;
            }

            table {
                font-size: 0.8rem;
            }

            th,
            td {
                padding: 0.75rem 0.5rem;
            }

            .footer {
                padding: 1.5rem 1rem;
                margin-top: 2rem;
            }
        }

        @media (max-width: 480px) {
            .navbar-brand span {
                display: none;
            }

            .navbar-brand img {
                height: 40px;
                width: 40px;
            }

            .hero-section {
                padding: 3rem 1rem;
                min-height: 50vh;
            }

            .page-title {
                font-size: 1.25rem;
            }

            .stat-content h3 {
                font-size: 1.25rem;
            }

            .stat-content p {
                font-size: 0.75rem;
            }
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
            align-items: center;
            flex-wrap: wrap;
            margin-top: 2rem;
        }

        .pagination a,
        .pagination span {
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

        /* Laravel Tailwind pagination overrides */
        .pagination nav {
            display: contents;
        }

        .pagination > nav > div {
            display: flex;
            gap: 0.5rem;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }

        .pagination > nav > div:first-child {
            display: none;
        }

        .pagination svg {
            width: 1rem;
            height: 1rem;
            display: inline-block;
            vertical-align: middle;
        }

        .pagination span[aria-disabled="true"],
        .pagination span[aria-current="page"] span {
            padding: 0.5rem 1rem;
            background: var(--bg-card);
            border: 1px solid var(--border-color);
            border-radius: var(--radius-sm);
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .pagination span[aria-current="page"] span {
            background: var(--primary);
            border-color: var(--primary);
            color: white;
        }

        .pagination a[rel="prev"],
        .pagination a[rel="next"] {
            display: inline-flex;
            align-items: center;
            gap: 0.25rem;
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

        /* ===== Premium Footer Styles ===== */
        .premium-footer {
            background: var(--bg-card);
            border-top: 1px solid var(--border-color);
            margin-top: 4rem;
            position: relative;
            overflow: hidden;
        }

        .premium-footer::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: var(--gradient-primary);
        }

        .footer-glow {
            position: absolute;
            width: 400px;
            height: 400px;
            border-radius: 50%;
            filter: blur(100px);
            opacity: 0.1;
            pointer-events: none;
        }

        .footer-glow.glow-1 {
            background: var(--primary);
            top: -200px;
            left: 10%;
        }

        .footer-glow.glow-2 {
            background: var(--secondary);
            bottom: -200px;
            right: 10%;
        }

        .footer-main {
            padding: 4rem 2rem 2rem;
            position: relative;
            z-index: 1;
        }

        .footer-container {
            max-width: 1400px;
            margin: 0 auto;
        }

        .footer-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 3rem;
            margin-bottom: 3rem;
        }

        .footer-brand {
            max-width: 350px;
        }

        .footer-logo {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1.25rem;
            text-decoration: none;
        }

        .footer-logo img {
            height: 55px;
            width: 55px;
            object-fit: contain;
        }

        .footer-logo-text {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--text-primary);
        }

        .footer-description {
            color: var(--text-secondary);
            font-size: 0.9rem;
            line-height: 1.7;
            margin-bottom: 1.5rem;
        }

        .footer-social {
            display: flex;
            gap: 0.75rem;
        }

        .social-link {
            width: 42px;
            height: 42px;
            border-radius: 50%;
            background: var(--bg-card-hover);
            display: flex;
            align-items: center;
            justify-content: center;
            color: var(--text-secondary);
            text-decoration: none;
            transition: all var(--transition-normal);
            border: 1px solid var(--border-color);
        }

        .social-link:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 25px rgba(99, 102, 241, 0.3);
        }

        .social-link.facebook:hover {
            background: #1877f2;
            color: white;
            border-color: #1877f2;
        }

        .social-link.instagram:hover {
            background: linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%);
            color: white;
            border-color: #dc2743;
        }

        .social-link.twitter:hover {
            background: #1da1f2;
            color: white;
            border-color: #1da1f2;
        }

        .social-link.whatsapp:hover {
            background: #25d366;
            color: white;
            border-color: #25d366;
        }

        .footer-column h4 {
            font-size: 1rem;
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: 1.25rem;
            position: relative;
            padding-bottom: 0.75rem;
        }

        .footer-column h4::after {
            content: '';
            position: absolute;
            bottom: 0;
            left: 0;
            width: 30px;
            height: 2px;
            background: var(--gradient-primary);
            border-radius: 2px;
        }

        .footer-links {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .footer-links li {
            margin-bottom: 0.75rem;
        }

        .footer-links a {
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.9rem;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            transition: all var(--transition-fast);
        }

        .footer-links a i {
            font-size: 0.75rem;
            color: var(--primary);
            opacity: 0;
            transform: translateX(-10px);
            transition: all var(--transition-fast);
        }

        .footer-links a:hover {
            color: var(--primary);
            transform: translateX(5px);
        }

        .footer-links a:hover i {
            opacity: 1;
            transform: translateX(0);
        }

        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            gap: 0.875rem;
            margin-bottom: 1rem;
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        .footer-contact-item i {
            color: var(--primary);
            font-size: 1rem;
            margin-top: 0.15rem;
            width: 20px;
            text-align: center;
        }

        .footer-contact-item a {
            color: var(--text-secondary);
            text-decoration: none;
            transition: color var(--transition-fast);
        }

        .footer-contact-item a:hover {
            color: var(--primary);
        }

        .footer-divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--border-color), transparent);
            margin: 0;
        }

        .footer-bottom {
            padding: 1.5rem 2rem;
            position: relative;
            z-index: 1;
        }

        .footer-bottom-content {
            max-width: 1400px;
            margin: 0 auto;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 1rem;
        }

        .footer-copyright {
            color: var(--text-muted);
            font-size: 0.875rem;
        }

        .footer-copyright a {
            color: var(--primary);
            text-decoration: none;
            font-weight: 500;
            transition: color var(--transition-fast);
        }

        .footer-copyright a:hover {
            color: var(--primary-light);
        }

        .footer-legal {
            display: flex;
            gap: 1.5rem;
        }

        .footer-legal a {
            color: var(--text-muted);
            text-decoration: none;
            font-size: 0.875rem;
            transition: color var(--transition-fast);
        }

        .footer-legal a:hover {
            color: var(--text-primary);
        }

        /* Footer Responsive Styles */
        @media (max-width: 1024px) {
            .footer-grid {
                grid-template-columns: 1fr 1fr;
                gap: 2.5rem;
            }

            .footer-brand {
                grid-column: span 2;
                max-width: 100%;
            }
        }

        @media (max-width: 768px) {
            .footer-main {
                padding: 3rem 1.5rem 1.5rem;
            }

            .footer-grid {
                grid-template-columns: 1fr;
                gap: 2rem;
            }

            .footer-brand {
                grid-column: span 1;
                text-align: center;
            }

            .footer-logo {
                justify-content: center;
            }

            .footer-social {
                justify-content: center;
            }

            .footer-column {
                text-align: center;
            }

            .footer-column h4::after {
                left: 50%;
                transform: translateX(-50%);
            }

            .footer-links a {
                justify-content: center;
            }

            .footer-contact-item {
                justify-content: center;
                text-align: center;
            }

            .footer-bottom-content {
                flex-direction: column;
                text-align: center;
            }

            .footer-legal {
                flex-wrap: wrap;
                justify-content: center;
                gap: 1rem;
            }
        }

        @media (max-width: 480px) {
            .footer-main {
                padding: 2.5rem 1rem 1rem;
            }

            .footer-logo img {
                height: 45px;
                width: 45px;
            }

            .footer-logo-text {
                font-size: 1.1rem;
            }
        }

        /* Legacy Footer Class for Compatibility */
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
            from {
                opacity: 0;
                transform: translateY(10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .animate-fade-in {
            animation: fadeIn 0.3s ease forwards;
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .animate-pulse {
            animation: pulse 2s infinite;
        }

        /* Global Hero Section Styles - Theme Aware */
        .hero-section {
            text-align: center;
            padding: 8rem 2rem;
            margin-bottom: 3rem;
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-size: cover;
            background-position: center;
            position: relative;
            overflow: hidden;
            border-radius: 24px;
        }

        [data-theme="dark"] .hero-section,
        :root .hero-section {
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)), url('/images/hero-bg.png');
            background-size: cover;
            background-position: center;
        }

        [data-theme="light"] .hero-section {
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(248, 250, 252, 0.9)), url('/images/hero-bg.png');
            background-size: cover;
            background-position: center;
        }

        .hero-section .hero-subtitle {
            font-size: 1.25rem;
            margin-bottom: 2rem;
            line-height: 1.8;
        }

        [data-theme="dark"] .hero-section .hero-subtitle,
        :root .hero-section .hero-subtitle {
            color: #cbd5e1;
        }

        [data-theme="light"] .hero-section .hero-subtitle {
            color: #475569;
        }

        /* Courts Hero Section */
        .courts-hero {
            text-align: center;
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            background-size: cover;
            background-position: center;
            border-radius: 24px;
        }

        [data-theme="dark"] .courts-hero,
        :root .courts-hero {
            background: linear-gradient(rgba(15, 23, 42, 0.7), rgba(15, 23, 42, 0.8)), url('/images/court-bg.png');
            background-size: cover;
            background-position: center;
        }

        [data-theme="light"] .courts-hero {
            background: linear-gradient(rgba(255, 255, 255, 0.85), rgba(248, 250, 252, 0.9)), url('/images/court-bg.png');
            background-size: cover;
            background-position: center;
        }

        [data-theme="light"] .courts-hero .page-title {
            color: #0f172a;
        }

        [data-theme="light"] .courts-hero .page-subtitle {
            color: #475569;
        }
    </style>
</head>

<body>
    <!-- Navigation -->
    <nav class="navbar">
        <div class="navbar-container">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="/images/logo.png" alt="FBC Logo" style="height: 55px; width: 55px; object-fit: contain;">
                <span>Flotilla Badminton Center</span>
            </a>

            <ul class="navbar-nav">
                <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><i
                            class="fas fa-home"></i> Home</a></li>
                <li><a href="{{ route('courts.index') }}"
                        class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }}"><i
                            class="fas fa-table-tennis-paddle-ball"></i> Courts</a></li>
                <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"><i
                            class="fas fa-info-circle"></i> About Us</a></li>
                @auth
                    <li><a href="{{ route('bookings.index') }}"
                            class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"><i
                                class="fas fa-ticket"></i> My Bookings</a></li>
                    @if(auth()->user()->isAdmin())
                        <li><a href="{{ route('admin.dashboard') }}"
                                class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"><i
                                    class="fas fa-gauge-high"></i> Admin</a></li>
                    @endif
                @endauth
            </ul>

            <div class="navbar-actions">
                <!-- Theme Toggle -->
                <div class="theme-toggle">
                    <button class="theme-toggle-btn" id="themeToggleBtn" aria-label="Toggle theme">
                        <i class="fas fa-moon" id="themeIcon"></i>
                    </button>
                    <div class="theme-dropdown" id="themeDropdown">
                        <button class="theme-option" data-theme="light">
                            <i class="fas fa-sun"></i>
                            <span>Light</span>
                        </button>
                        <button class="theme-option" data-theme="dark">
                            <i class="fas fa-moon"></i>
                            <span>Dark</span>
                        </button>
                        <button class="theme-option" data-theme="default">
                            <i class="fas fa-desktop"></i>
                            <span>Default</span>
                        </button>
                    </div>
                </div>

                @guest
                    <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                    <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
                @else
                    <div class="dropdown">
                        <div style="display: flex; align-items: center; gap: 0.75rem; cursor: pointer;">
                            @if(auth()->user()->getProfilePictureUrl())
                                <img src="{{ auth()->user()->getProfilePictureUrl() }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="object-fit: cover;">
                            @else
                                <div class="user-avatar">
                                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                                </div>
                            @endif
                            <span style="font-weight: 500; color: var(--text-primary);">{{ auth()->user()->name }}</span>
                        </div>
                        <div class="dropdown-menu">
                            <a href="{{ route('dashboard') }}" class="dropdown-item"><i class="fas fa-gauge"></i>
                                Dashboard</a>
                            <a href="{{ route('profile.show') }}" class="dropdown-item"><i class="fas fa-user"></i>
                                Profile</a>
                            <a href="{{ route('bookings.index') }}" class="dropdown-item"><i class="fas fa-ticket"></i> My
                                Bookings</a>
                            <hr style="border-color: var(--border-color); margin: 0.5rem 0;">
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item"
                                    style="width: 100%; border: none; background: none; cursor: pointer;">
                                    <i class="fas fa-sign-out-alt"></i> Logout
                                </button>
                            </form>
                        </div>
                    </div>
                @endguest

                <!-- Mobile Menu Toggle -->
                <button class="mobile-menu-toggle" id="mobileMenuToggle" aria-label="Open menu">
                    <i class="fas fa-bars"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Mobile Navigation Overlay -->
    <div class="mobile-nav-overlay" id="mobileNavOverlay"></div>

    <!-- Mobile Navigation Menu -->
    <div class="mobile-nav" id="mobileNav">
        <div class="mobile-nav-header">
            <a href="{{ route('home') }}" class="navbar-brand">
                <img src="/images/logo.png" alt="FBC Logo" style="height: 40px; width: 40px; object-fit: contain;">
                <span style="font-size: 1rem;">FBC</span>
            </a>
            <button class="mobile-nav-close" id="mobileNavClose" aria-label="Close menu">
                <i class="fas fa-times"></i>
            </button>
        </div>

        @auth
            <div class="mobile-user-info">
                @if(auth()->user()->getProfilePictureUrl())
                    <img src="{{ auth()->user()->getProfilePictureUrl() }}" alt="{{ auth()->user()->name }}" class="user-avatar" style="object-fit: cover;">
                @else
                    <div class="user-avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                @endif
                <div class="user-details">
                    <div class="user-name">{{ auth()->user()->name }}</div>
                    <div class="user-email">{{ auth()->user()->email }}</div>
                </div>
            </div>
        @endauth

        <ul class="mobile-nav-links">
            <li><a href="{{ route('home') }}" class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}"><i class="fas fa-home"></i> Home</a></li>
            <li><a href="{{ route('courts.index') }}" class="nav-link {{ request()->routeIs('courts.*') ? 'active' : '' }}"><i class="fas fa-table-tennis-paddle-ball"></i> Courts</a></li>
            <li><a href="{{ route('about') }}" class="nav-link {{ request()->routeIs('about') ? 'active' : '' }}"><i class="fas fa-info-circle"></i> About Us</a></li>
            @auth
                <li><a href="{{ route('bookings.index') }}" class="nav-link {{ request()->routeIs('bookings.*') ? 'active' : '' }}"><i class="fas fa-ticket"></i> My Bookings</a></li>
                <li><a href="{{ route('dashboard') }}" class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}"><i class="fas fa-gauge"></i> Dashboard</a></li>
                <li><a href="{{ route('profile.show') }}" class="nav-link {{ request()->routeIs('profile.*') ? 'active' : '' }}"><i class="fas fa-user"></i> Profile</a></li>
                @if(auth()->user()->isAdmin())
                    <li><a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.*') ? 'active' : '' }}"><i class="fas fa-gauge-high"></i> Admin Panel</a></li>
                @endif
            @endauth
        </ul>

        <div class="mobile-nav-actions">
            @guest
                <a href="{{ route('login') }}" class="btn btn-secondary">Login</a>
                <a href="{{ route('register') }}" class="btn btn-primary">Register</a>
            @else
                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <i class="fas fa-sign-out-alt"></i> Logout
                    </button>
                </form>
            @endguest
        </div>
    </div>

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

    <!-- Premium Footer -->
    <footer class="premium-footer">
        <!-- Decorative Glow Effects -->
        <div class="footer-glow glow-1"></div>
        <div class="footer-glow glow-2"></div>

        <!-- Main Footer Content -->
        <div class="footer-main">
            <div class="footer-container">
                <div class="footer-grid">
                    <!-- Brand Column -->
                    <div class="footer-brand">
                        <a href="{{ route('home') }}" class="footer-logo">
                            <img src="/images/logo.png" alt="FBC Logo">
                            <span class="footer-logo-text">Flotilla Badminton Center</span>
                        </a>
                        <p class="footer-description">
                            Your premier destination for badminton excellence. Experience world-class facilities, 
                            professional coaching, and a vibrant community of players at all skill levels.
                        </p>
                        <div class="footer-social">
                            <a href="https://facebook.com" target="_blank" rel="noopener noreferrer" class="social-link facebook" aria-label="Facebook">
                                <i class="fab fa-facebook-f"></i>
                            </a>
                            <a href="https://instagram.com" target="_blank" rel="noopener noreferrer" class="social-link instagram" aria-label="Instagram">
                                <i class="fab fa-instagram"></i>
                            </a>
                            <a href="https://twitter.com" target="_blank" rel="noopener noreferrer" class="social-link twitter" aria-label="Twitter">
                                <i class="fab fa-twitter"></i>
                            </a>
                            <a href="https://wa.me/601234567890" target="_blank" rel="noopener noreferrer" class="social-link whatsapp" aria-label="WhatsApp">
                                <i class="fab fa-whatsapp"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links Column -->
                    <div class="footer-column">
                        <h4>Quick Links</h4>
                        <ul class="footer-links">
                            <li><a href="{{ route('home') }}"><i class="fas fa-chevron-right"></i> Home</a></li>
                            <li><a href="{{ route('courts.index') }}"><i class="fas fa-chevron-right"></i> Our Courts</a></li>
                            <li><a href="{{ route('about') }}"><i class="fas fa-chevron-right"></i> About Us</a></li>
                            @auth
                            <li><a href="{{ route('bookings.index') }}"><i class="fas fa-chevron-right"></i> My Bookings</a></li>
                            <li><a href="{{ route('dashboard') }}"><i class="fas fa-chevron-right"></i> Dashboard</a></li>
                            @endauth
                        </ul>
                    </div>

                    <!-- Contact Column -->
                    <div class="footer-column">
                        <h4>Contact Us</h4>
                        <div class="footer-contact-item">
                            <i class="fas fa-map-marker-alt"></i>
                            <span>KICT, International Islamic University Malaysia, 53100 Gombak, Selangor, Malaysia</span>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-phone-alt"></i>
                            <a href="tel:+601234567890">+60 12-345 6789</a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-envelope"></i>
                            <a href="mailto:info@flotillabadminton.com">info@flotillabadminton.com</a>
                        </div>
                        <div class="footer-contact-item">
                            <i class="fas fa-clock"></i>
                            <span>Mon - Sun: 8:00 AM - 3:00 AM</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer Divider -->
        <div class="footer-divider"></div>

        <!-- Footer Bottom -->
        <div class="footer-bottom">
            <div class="footer-bottom-content">
                <p class="footer-copyright">
                    &copy; {{ date('Y') }} <a href="{{ route('home') }}">Flotilla Badminton Center</a>. All rights reserved. 
                    Crafted with <i class="fas fa-heart" style="color: #ef4444;"></i> by Group Flotilla
                </p>
                <div class="footer-legal">
                    <a href="#">Privacy Policy</a>
                    <a href="#">Terms of Service</a>
                    <a href="#">Cookie Policy</a>
                </div>
            </div>
        </div>
    </footer>

    @stack('scripts')

    <!-- Theme Toggle Script -->
    <script>
        (function () {
            const themeToggleBtn = document.getElementById('themeToggleBtn');
            const themeDropdown = document.getElementById('themeDropdown');
            const themeIcon = document.getElementById('themeIcon');
            const themeOptions = document.querySelectorAll('.theme-option');

            // Initialize theme
            function initTheme() {
                const savedTheme = localStorage.getItem('theme') || 'dark';
                setTheme(savedTheme);
            }

            // Set theme
            function setTheme(theme) {
                document.documentElement.setAttribute('data-theme', theme);
                localStorage.setItem('theme', theme);
                updateIcon(theme);
                updateActiveOption(theme);
            }

            // Update the toggle button icon
            function updateIcon(theme) {
                themeIcon.className = 'fas';
                if (theme === 'light') {
                    themeIcon.classList.add('fa-sun');
                } else if (theme === 'dark') {
                    themeIcon.classList.add('fa-moon');
                } else {
                    themeIcon.classList.add('fa-desktop');
                }
            }

            // Update active state on options
            function updateActiveOption(theme) {
                themeOptions.forEach(option => {
                    option.classList.remove('active');
                    if (option.dataset.theme === theme) {
                        option.classList.add('active');
                    }
                });
            }

            // Toggle dropdown visibility on click
            themeToggleBtn.addEventListener('click', function (e) {
                e.stopPropagation();
                themeDropdown.classList.toggle('show');
            });

            // Handle theme option click
            themeOptions.forEach(option => {
                option.addEventListener('click', function () {
                    const theme = this.dataset.theme;
                    setTheme(theme);
                    themeDropdown.classList.remove('show');
                });
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function (e) {
                if (!themeToggleBtn.contains(e.target) && !themeDropdown.contains(e.target)) {
                    themeDropdown.classList.remove('show');
                }
            });

            // Initialize
            initTheme();
        })();
    </script>

    <!-- Mobile Menu Script -->
    <script>
        (function() {
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const mobileNav = document.getElementById('mobileNav');
            const mobileNavOverlay = document.getElementById('mobileNavOverlay');
            const mobileNavClose = document.getElementById('mobileNavClose');

            function openMobileMenu() {
                mobileNav.classList.add('active');
                mobileNavOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }

            function closeMobileMenu() {
                mobileNav.classList.remove('active');
                mobileNavOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }

            if (mobileMenuToggle) {
                mobileMenuToggle.addEventListener('click', openMobileMenu);
            }

            if (mobileNavClose) {
                mobileNavClose.addEventListener('click', closeMobileMenu);
            }

            if (mobileNavOverlay) {
                mobileNavOverlay.addEventListener('click', closeMobileMenu);
            }

            // Close menu on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape') {
                    closeMobileMenu();
                }
            });

            // Close menu when window is resized to desktop size
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeMobileMenu();
                }
            });
        })();
    </script>

    <!-- Livewire Scripts -->
    @livewireScripts
</body>
</html>