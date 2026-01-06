@extends('layouts.app')

@section('title', 'About Us')

@section('content')
<div class="page-header" style="text-align: center; margin-bottom: 3rem;">
    <h1 class="page-title" style="font-size: 2.5rem;">About BadmintonBook</h1>
    <p class="page-subtitle">Your premier badminton court booking system</p>
</div>

<div class="grid grid-cols-2" style="gap: 2rem; margin-bottom: 3rem;">
    <!-- Mission Section -->
    <div class="card">
        <div style="text-align: center; padding: 1rem;">
            <div style="width: 80px; height: 80px; background: var(--gradient-primary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-bullseye" style="font-size: 2rem; color: white;"></i>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Our Mission</h2>
            <p style="color: var(--text-secondary); line-height: 1.8;">
                To provide a seamless and convenient platform for badminton enthusiasts to book courts, 
                manage their games, and enjoy the sport they love without the hassle of manual reservations.
            </p>
        </div>
    </div>
    
    <!-- Vision Section -->
    <div class="card">
        <div style="text-align: center; padding: 1rem;">
            <div style="width: 80px; height: 80px; background: var(--gradient-secondary); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 1.5rem;">
                <i class="fas fa-eye" style="font-size: 2rem; color: white;"></i>
            </div>
            <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 1rem;">Our Vision</h2>
            <p style="color: var(--text-secondary); line-height: 1.8;">
                To become the leading court booking platform in Malaysia, connecting players with 
                premium facilities and fostering a vibrant badminton community.
            </p>
        </div>
    </div>
</div>

<!-- Features Section -->
<div class="card" style="margin-bottom: 3rem;">
    <h2 class="card-title" style="text-align: center; margin-bottom: 2rem;">
        <i class="fas fa-star" style="color: var(--accent);"></i> Why Choose Us
    </h2>
    
    <div class="grid grid-cols-3" style="gap: 2rem;">
        <div style="text-align: center;">
            <div style="width: 60px; height: 60px; background: var(--gradient-success); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <i class="fas fa-clock" style="font-size: 1.5rem; color: white;"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Easy Booking</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Book your preferred court in just a few clicks, anytime, anywhere.</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 60px; height: 60px; background: var(--gradient-accent); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <i class="fas fa-credit-card" style="font-size: 1.5rem; color: white;"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">Secure Payment</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Safe and verified payment process with admin confirmation.</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 60px; height: 60px; background: var(--gradient-primary); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center; margin: 0 auto 1rem;">
                <i class="fas fa-qrcode" style="font-size: 1.5rem; color: white;"></i>
            </div>
            <h3 style="font-weight: 600; margin-bottom: 0.5rem;">QR Check-In</h3>
            <p style="color: var(--text-secondary); font-size: 0.875rem;">Quick and contactless check-in with your unique QR code.</p>
        </div>
    </div>
</div>

<!-- Team Section -->
<div class="card" style="margin-bottom: 3rem;">
    <h2 class="card-title" style="text-align: center; margin-bottom: 2rem;">
        <i class="fas fa-users" style="color: var(--primary);"></i> Development Team
    </h2>
    
    <div style="display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;">
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4a90d9 0%, #357abd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 2rem; color: white; font-weight: 700;">
                G
            </div>
            <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Wazir</h4>
            <p style="color: var(--text-muted); font-size: 0.75rem;">222222</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4a90d9 0%, #357abd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 2rem; color: white; font-weight: 700;">
                A
            </div>
            <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Zafir</h4>
            <p style="color: var(--text-muted); font-size: 0.75rem;">237859</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4a90d9 0%, #357abd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 2rem; color: white; font-weight: 700;">
                N
            </div>
            <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Budin</h4>
            <p style="color: var(--text-muted); font-size: 0.75rem;">2312257</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4a90d9 0%, #357abd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 2rem; color: white; font-weight: 700;">
                I
            </div>
            <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Izwan</h4>
            <p style="color: var(--text-muted); font-size: 0.75rem;">231777</p>
        </div>
        
        <div style="text-align: center;">
            <div style="width: 80px; height: 80px; background: linear-gradient(135deg, #4a90d9 0%, #357abd 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 0.75rem; font-size: 2rem; color: white; font-weight: 700;">
                <i class="fas fa-user" style="font-size: 1.5rem;"></i>
            </div>
            <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Moshu</h4>
            <p style="color: var(--text-muted); font-size: 0.75rem;">2211234</p>
        </div>
    </div>
</div>

<!-- Contact & Location Section -->
<div class="grid grid-cols-2" style="gap: 2rem;">
    <!-- Contact Us -->
    <div class="card">
        <h2 class="card-title" style="margin-bottom: 1.5rem;">
            <i class="fas fa-phone-alt" style="color: var(--secondary);"></i> Contact Us
        </h2>
        
        <div style="display: flex; flex-direction: column; gap: 1.5rem;">
            <!-- Email -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: rgba(239, 68, 68, 0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-envelope" style="font-size: 1.25rem; color: #ef4444;"></i>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Email</h4>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">badmintonbook@iium.edu.my</p>
                </div>
            </div>
            
            <!-- Phone -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: rgba(99, 102, 241, 0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <i class="fas fa-phone" style="font-size: 1.25rem; color: #6366f1;"></i>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem;">Phone</h4>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">+60 12-345 6789</p>
                </div>
            </div>
            
            <!-- WhatsApp -->
            <div style="display: flex; align-items: center; gap: 1rem;">
                <div style="width: 50px; height: 50px; background: rgba(16, 185, 129, 0.15); border-radius: var(--radius-md); display: flex; align-items: center; justify-content: center;">
                    <i class="fab fa-whatsapp" style="font-size: 1.5rem; color: #10b981;"></i>
                </div>
                <div>
                    <h4 style="font-weight: 600; margin-bottom: 0.25rem;">WhatsApp</h4>
                    <p style="color: var(--text-secondary); font-size: 0.875rem;">+60 12-345 6789</p>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Location -->
    <div class="card">
        <h2 class="card-title" style="margin-bottom: 1.5rem;">
            <i class="fas fa-map-marker-alt" style="color: var(--primary);"></i> Location
        </h2>
        
        <!-- Address -->
        <div style="display: flex; align-items: flex-start; gap: 1rem; margin-bottom: 1.5rem;">
            <div style="width: 40px; height: 40px; background: rgba(99, 102, 241, 0.15); border-radius: var(--radius-sm); display: flex; align-items: center; justify-content: center; flex-shrink: 0;">
                <i class="fas fa-building" style="color: var(--primary);"></i>
            </div>
            <div>
                <h4 style="font-weight: 600; margin-bottom: 0.25rem;">BadmintonBook</h4>
                <p style="color: var(--text-secondary); font-size: 0.875rem; line-height: 1.6;">
                    KICT, International Islamic University Malaysia,<br>
                    53100 Gombak, Selangor, Malaysia
                </p>
            </div>
        </div>
        
        <!-- Google Maps Embed -->
        <div style="border-radius: var(--radius-md); overflow: hidden; margin-bottom: 1rem;">
            <iframe 
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3983.543!2d101.7357!3d3.2508!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31cc38add4d87985%3A0x1c37182a714ba968!2sKulliyyah%20of%20Information%20and%20Communication%20Technology!5e0!3m2!1sen!2smy!4v1704567890"
                width="100%" 
                height="180" 
                style="border:0;" 
                allowfullscreen="" 
                loading="lazy" 
                referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
        
        <!-- View on Google Maps Button -->
        <a href="https://maps.app.goo.gl/21522vVu4y6msyE39" 
           target="_blank" 
           class="btn btn-primary" 
           style="width: 100%; background: var(--gradient-secondary);">
            <i class="fas fa-map-marker-alt"></i> View on Google Maps
        </a>
    </div>
</div>
@endsection
