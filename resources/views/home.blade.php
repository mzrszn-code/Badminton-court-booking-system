@extends('layouts.app')

@section('title', 'Home')

@section('content')
    <style>
        /* Hero section theme-aware styles */
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

        /* Courts page hero */
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

        .courts-hero .page-title,
        .courts-hero .page-subtitle {
            color: var(--text-primary);
        }

        [data-theme="light"] .courts-hero .page-subtitle {
            color: #475569;
        }
    </style>

    <!-- Hero Section -->
    <section class="hero-section">
        <div style="max-width: 800px; margin: 0 auto; position: relative; z-index: 1;">
            <h1
                style="font-size: 3.5rem; font-weight: 800; margin-bottom: 1.5rem; background: linear-gradient(135deg, #6366f1 0%, #8b5cf6 50%, #06b6d4 100%); -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;">
                Book Your Court,<br>Play Your Game
            </h1>
            <p class="hero-subtitle">
                The ultimate badminton court booking system. Reserve your spot in seconds,
                track your sessions, and never miss a game again.
            </p>
            <div style="display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;">
                <a href="{{ route('courts.index') }}" class="btn btn-primary btn-lg">
                    <i class="fas fa-search"></i> Browse Courts
                </a>
                @guest
                    <a href="{{ route('register') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-user-plus"></i> Get Started
                    </a>
                @else
                    <a href="{{ route('bookings.create') }}" class="btn btn-secondary btn-lg">
                        <i class="fas fa-plus"></i> Book Now
                    </a>
                @endguest
            </div>
        </div>
    </section>

    <!-- Features Section -->
    <section style="margin-bottom: 4rem;">
        <h2 style="text-align: center; font-size: 2rem; font-weight: 700; margin-bottom: 2rem;">Why Choose Us?</h2>

        <div class="grid grid-cols-3">
            <div class="card" style="text-align: center;">
                <div
                    style="width: 70px; height: 70px; background: var(--gradient-primary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem; color: white;">
                    <i class="fas fa-bolt"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem;">Instant Booking</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Book your court in seconds with our streamlined
                    reservation system. Real-time availability updates.</p>
            </div>

            <div class="card" style="text-align: center;">
                <div
                    style="width: 70px; height: 70px; background: var(--gradient-secondary); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem; color: white;">
                    <i class="fas fa-qrcode"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem;">QR Check-In</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Quick and easy check-in with unique QR codes. No
                    more waiting in lines or searching for bookings.</p>
            </div>

            <div class="card" style="text-align: center;">
                <div
                    style="width: 70px; height: 70px; background: var(--gradient-success); border-radius: var(--radius-lg); display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem; font-size: 1.75rem; color: white;">
                    <i class="fas fa-bell"></i>
                </div>
                <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 0.75rem;">Smart Reminders</h3>
                <p style="color: var(--text-secondary); font-size: 0.9rem;">Never forget a booking with email notifications
                    and reminders 2 hours before your session.</p>
            </div>
        </div>
    </section>

    <!-- Available Courts Preview -->
    <section style="margin-bottom: 4rem;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
            <h2 style="font-size: 2rem; font-weight: 700;">Available Courts</h2>
            <a href="{{ route('courts.index') }}" class="btn btn-secondary">View All <i class="fas fa-arrow-right"></i></a>
        </div>

        @php
            $courts = \App\Models\Court::where('status', 'available')->get();
        @endphp

        @if($courts->count() > 0)
            <!-- Carousel Container -->
            <style>
                @keyframes scrollLeft {
                    0% {
                        transform: translateX(0);
                    }

                    100% {
                        transform: translateX(-50%);
                    }
                }

                .courts-carousel-wrapper {
                    overflow: hidden;
                    position: relative;
                    padding: 0.5rem 0;
                }

                .courts-carousel-wrapper::before,
                .courts-carousel-wrapper::after {
                    content: '';
                    position: absolute;
                    top: 0;
                    bottom: 0;
                    width: 80px;
                    z-index: 2;
                    pointer-events: none;
                }

                .courts-carousel-wrapper::before {
                    left: 0;
                    background: linear-gradient(to right, var(--bg-primary), transparent);
                }

                .courts-carousel-wrapper::after {
                    right: 0;
                    background: linear-gradient(to left, var(--bg-primary), transparent);
                }

                .courts-carousel-track {
                    display: flex;
                    gap: 1.5rem;
                    animation: scrollLeft 30s linear infinite;
                    width: max-content;
                }

                .courts-carousel-track:hover {
                    animation-play-state: paused;
                }

                .court-carousel-card {
                    flex-shrink: 0;
                    width: 350px;
                    background: var(--bg-secondary);
                    border: 1px solid var(--border-color);
                    border-radius: var(--radius-lg);
                    padding: 1.5rem;
                    transition: transform 0.3s ease, box-shadow 0.3s ease;
                }

                .court-carousel-card:hover {
                    transform: translateY(-5px);
                    box-shadow: 0 10px 40px rgba(99, 102, 241, 0.15);
                }
            </style>

            <div class="courts-carousel-wrapper">
                <div class="courts-carousel-track">
                    {{-- First set of courts --}}
                    @foreach($courts as $court)
                        <div class="court-carousel-card">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div
                                    style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                                    {{ strtoupper(substr($court->court_name, -1)) }}
                                </div>
                                <div>
                                    <h3 style="font-weight: 700;">{{ $court->court_name }}</h3>
                                    <span class="badge badge-{{ $court->status }}">{{ ucfirst($court->status) }}</span>
                                </div>
                            </div>
                            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                <i class="fas fa-location-dot"></i> {{ $court->location }}
                            </p>
                            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                {{ Str::limit($court->description, 80) }}
                            </p>
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">RM
                                    {{ number_format($court->hourly_rate, 2) }}<span
                                        style="font-size: 0.75rem; color: var(--text-secondary); font-weight: normal;">/hour</span></span>
                                <a href="{{ route('bookings.create', ['court' => $court->id]) }}"
                                    class="btn btn-primary btn-sm">Book Now</a>
                            </div>
                        </div>
                    @endforeach

                    {{-- Duplicate set for seamless infinite loop --}}
                    @foreach($courts as $court)
                        <div class="court-carousel-card">
                            <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                                <div
                                    style="width: 50px; height: 50px; background: var(--gradient-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 1.25rem;">
                                    {{ strtoupper(substr($court->court_name, -1)) }}
                                </div>
                                <div>
                                    <h3 style="font-weight: 700;">{{ $court->court_name }}</h3>
                                    <span class="badge badge-{{ $court->status }}">{{ ucfirst($court->status) }}</span>
                                </div>
                            </div>
                            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                <i class="fas fa-location-dot"></i> {{ $court->location }}
                            </p>
                            <p style="color: var(--text-secondary); font-size: 0.875rem; margin-bottom: 1rem;">
                                {{ Str::limit($court->description, 80) }}
                            </p>
                            <div
                                style="display: flex; justify-content: space-between; align-items: center; padding-top: 1rem; border-top: 1px solid var(--border-color);">
                                <span style="font-size: 1.25rem; font-weight: 700; color: var(--primary);">RM
                                    {{ number_format($court->hourly_rate, 2) }}<span
                                        style="font-size: 0.75rem; color: var(--text-secondary); font-weight: normal;">/hour</span></span>
                                <a href="{{ route('bookings.create', ['court' => $court->id]) }}"
                                    class="btn btn-primary btn-sm">Book Now</a>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="card" style="text-align: center; padding: 3rem;">
                <i class="fas fa-calendar-xmark" style="font-size: 3rem; color: var(--text-muted); margin-bottom: 1rem;"></i>
                <p style="color: var(--text-secondary);">No courts available at the moment.</p>
            </div>
        @endif
    </section>

    <!-- CTA Section -->
    <section class="card glassmorphism" style="text-align: center; padding: 3rem;">
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 1rem;">Ready to Play?</h2>
        <p
            style="color: var(--text-secondary); margin-bottom: 2rem; max-width: 600px; margin-left: auto; margin-right: auto;">
            Join hundreds of players who book their badminton sessions with us. Create your account today and get started!
        </p>
        @guest
            <a href="{{ route('register') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-rocket"></i> Create Free Account
            </a>
        @else
            <a href="{{ route('bookings.create') }}" class="btn btn-primary btn-lg">
                <i class="fas fa-calendar-plus"></i> Make a Booking
            </a>
        @endguest
    </section>
@endsection