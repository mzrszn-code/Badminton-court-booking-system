@extends('layouts.app')

@section('title', 'New Booking')

@section('content')
<div style="margin-bottom: 1rem;">
    <a href="{{ route('bookings.index') }}" style="color: var(--text-secondary); text-decoration: none; font-size: 0.875rem;">
        <i class="fas fa-arrow-left"></i> Back to My Bookings
    </a>
</div>

<div class="page-header">
    <h1 class="page-title">Book a Court</h1>
    <p class="page-subtitle">Select your preferred court, date, and click on an available time slot.</p>
</div>

<div class="grid grid-cols-2" style="grid-template-columns: 1fr 420px;">
    <!-- Booking Form -->
    <div class="card">
        <form method="POST" action="{{ route('bookings.store') }}" id="bookingForm">
            @csrf
            
            <div class="form-group">
                <label class="form-label">Select Court *</label>
                <div id="courtCards" style="display: grid; gap: 0.75rem; margin-bottom: 1rem; max-height: 280px; overflow-y: auto; padding-right: 0.5rem;">
                    @foreach($courts as $court)
                        <div class="court-card {{ ($selectedCourt && $selectedCourt->id == $court->id) ? 'selected' : '' }}" 
                             data-court-id="{{ $court->id }}" 
                             data-rate="{{ $court->hourly_rate }}"
                             data-name="{{ $court->court_name }}"
                             style="padding: 1rem; background: var(--bg-card-hover); border-radius: var(--radius-md); cursor: pointer; transition: var(--transition-fast); border: 2px solid {{ ($selectedCourt && $selectedCourt->id == $court->id) ? 'var(--primary)' : 'transparent' }};">
                            <div style="display: flex; justify-content: space-between; align-items: center;">
                                <div>
                                    <div style="font-weight: 700;">{{ $court->court_name }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-secondary);">{{ ucfirst($court->court_type) }} • {{ $court->location }}</div>
                                </div>
                                <div style="text-align: right;">
                                    <div style="font-weight: 700; color: var(--primary);">RM {{ number_format($court->hourly_rate, 2) }}</div>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">per hour</div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
                <input type="hidden" name="court_id" id="courtId" value="{{ $selectedCourt ? $selectedCourt->id : '' }}" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">Booking Date *</label>
                <input type="date" name="booking_date" id="bookingDate" class="form-control" 
                       value="{{ $selectedDate }}" min="{{ now()->format('Y-m-d') }}" required>
            </div>
            
            <input type="hidden" name="start_time" id="startTime" required>
            <input type="hidden" name="end_time" id="endTime" required>
            
            <div class="form-group">
                <label class="form-label">Notes <span style="color: var(--text-muted);">(optional)</span></label>
                <textarea name="notes" class="form-control" rows="2" placeholder="Any special requests...">{{ old('notes') }}</textarea>
            </div>
            
            <button type="submit" id="submitBtn" class="btn btn-primary btn-lg" style="width: 100%;" disabled>
                <i class="fas fa-credit-card"></i> <span id="submitBtnText">Select Time Slots to Book</span>
            </button>
        </form>
    </div>
    
    <!-- Availability Panel -->
    <div>
        <div class="card">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-clock" style="color: var(--primary); margin-right: 0.5rem;"></i> Available Time Slots</h2>
                <div id="refreshBtn" style="cursor: pointer; color: var(--text-secondary);" title="Refresh">
                    <i class="fas fa-sync-alt"></i>
                </div>
            </div>
            
            <div style="background: var(--bg-card-hover); padding: 0.75rem 1rem; border-radius: var(--radius-sm); margin-bottom: 1rem; font-size: 0.875rem;">
                <i class="fas fa-info-circle" style="color: var(--primary);"></i>
                <strong>Each slot = 1 hour</strong> • Click one slot or multiple consecutive slots to book
            </div>
            
            <div id="availabilitySlots" style="max-height: 320px; overflow-y: auto; padding-right: 0.5rem;">
                <div class="empty-state" style="padding: 2rem;">
                    <i class="fas fa-calendar-alt" style="font-size: 2rem;"></i>
                    <p>Select a court and date to see available time slots.</p>
                </div>
            </div>
        </div>
        
        <!-- Booking Summary -->
        <div class="card" style="margin-top: 1.5rem;" id="bookingSummary">
            <div class="card-header">
                <h2 class="card-title"><i class="fas fa-receipt" style="color: var(--accent); margin-right: 0.5rem;"></i> Booking Summary</h2>
            </div>
            
            <div id="summaryContent">
                <div class="empty-state" style="padding: 1.5rem;">
                    <p style="color: var(--text-secondary);">Select time slots to see summary</p>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    .court-card:hover {
        border-color: var(--primary) !important;
        transform: translateY(-2px);
    }
    .court-card.selected {
        border-color: var(--primary) !important;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.3);
    }
    .time-slot {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.75rem 1rem;
        border-radius: var(--radius-sm);
        margin-bottom: 0.5rem;
        transition: var(--transition-fast);
    }
    .time-slot.available {
        background: rgba(16, 185, 129, 0.1);
        border-left: 3px solid var(--success);
        cursor: pointer;
    }
    .time-slot.available:hover {
        background: rgba(16, 185, 129, 0.2);
        transform: translateX(4px);
    }
    .time-slot.booked {
        background: var(--bg-card-hover);
        border-left: 3px solid var(--text-muted);
        opacity: 0.6;
    }
    .time-slot.selected {
        background: var(--primary) !important;
        border-left: 3px solid var(--primary-dark);
        color: white !important;
    }
    .time-slot.selected .badge {
        background: rgba(255,255,255,0.2) !important;
        color: white !important;
    }
    .time-slot.in-range {
        background: rgba(99, 102, 241, 0.3);
        border-left-color: var(--primary);
    }
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const courtCards = document.querySelectorAll('.court-card');
    const courtIdInput = document.getElementById('courtId');
    const bookingDate = document.getElementById('bookingDate');
    const startTimeInput = document.getElementById('startTime');
    const endTimeInput = document.getElementById('endTime');
    const availabilitySlots = document.getElementById('availabilitySlots');
    const summaryContent = document.getElementById('summaryContent');
    const submitBtn = document.getElementById('submitBtn');
    const submitBtnText = document.getElementById('submitBtnText');
    const refreshBtn = document.getElementById('refreshBtn');
    
    let selectedStartSlot = null;
    let selectedEndSlot = null;
    let slotsData = [];
    
    // Court card selection
    courtCards.forEach(card => {
        card.addEventListener('click', function() {
            courtCards.forEach(c => {
                c.classList.remove('selected');
                c.style.borderColor = 'transparent';
            });
            this.classList.add('selected');
            this.style.borderColor = 'var(--primary)';
            courtIdInput.value = this.dataset.courtId;
            resetSlotSelection();
            fetchAvailability();
        });
    });
    
    // Date change
    bookingDate.addEventListener('change', function() {
        resetSlotSelection();
        fetchAvailability();
    });
    
    // Refresh button
    refreshBtn.addEventListener('click', fetchAvailability);
    
    function resetSlotSelection() {
        selectedStartSlot = null;
        selectedEndSlot = null;
        selectedStartIndex = -1;
        selectedEndIndex = -1;
        startTimeInput.value = '';
        endTimeInput.value = '';
        updateSummary();
        updateSubmitButton();
    }
    
    function fetchAvailability() {
        const courtId = courtIdInput.value;
        const date = bookingDate.value;
        
        if (!courtId || !date) {
            availabilitySlots.innerHTML = '<div class="empty-state" style="padding: 2rem;"><i class="fas fa-calendar-alt" style="font-size: 2rem;"></i><p>Select a court and date to see available time slots.</p></div>';
            return;
        }
        
        availabilitySlots.innerHTML = '<div style="text-align: center; padding: 2rem;"><i class="fas fa-spinner fa-spin" style="font-size: 2rem; color: var(--primary);"></i><p style="margin-top: 1rem; color: var(--text-secondary);">Loading availability...</p></div>';
        
        fetch(`/api/available-slots?court_id=${courtId}&date=${date}`)
            .then(response => response.json())
            .then(slots => {
                slotsData = slots;
                renderSlots();
            })
            .catch(error => {
                availabilitySlots.innerHTML = '<div class="alert alert-error">Failed to load availability. Please try again.</div>';
            });
    }
    
    function renderSlots() {
        let html = '<div style="display: grid; gap: 0.5rem;">';
        
        slotsData.forEach((slot, index) => {
            // Check if this slot is within the selected range by index
            const isInSelectedRange = isSlotSelected(index);
            
            let slotClass = slot.available ? 'available' : 'booked';
            if (isInSelectedRange) slotClass += ' selected';
            
            html += `
                <div class="time-slot ${slotClass}" 
                     data-index="${index}"
                     data-start="${slot.start}" 
                     data-end="${slot.end}" 
                     data-available="${slot.available}"
                     onclick="${slot.available ? 'selectSlot(this)' : ''}">
                    <span style="font-weight: 500; font-size: 0.875rem;">${formatTime(slot.start)} - ${formatTime(slot.end)}</span>
                    <span class="badge badge-${slot.available ? 'available' : 'booked'}">${isInSelectedRange ? 'Selected' : (slot.available ? 'Available' : 'Booked')}</span>
                </div>
            `;
        });
        
        html += '</div>';
        
        if (slotsData.length === 0) {
            html = '<div class="empty-state" style="padding: 2rem;"><i class="fas fa-exclamation-circle" style="font-size: 2rem;"></i><p>No time slots available.</p></div>';
        }
        
        availabilitySlots.innerHTML = html;
    }
    
    // Track selected slot indices instead of times
    let selectedStartIndex = -1;
    let selectedEndIndex = -1;
    
    // Check if a slot is within the selected range by index
    function isSlotSelected(index) {
        if (selectedStartIndex === -1 || selectedEndIndex === -1) return false;
        return index >= selectedStartIndex && index <= selectedEndIndex;
    }
    
    function formatTime(time) {
        const hour = parseInt(time.split(':')[0]);
        const ampm = hour >= 12 ? 'PM' : 'AM';
        const displayHour = hour > 12 ? hour - 12 : (hour === 0 ? 12 : hour);
        return `${displayHour}:00 ${ampm}`;
    }
    
    // Expose to global scope for onclick
    window.selectSlot = function(element) {
        const index = parseInt(element.dataset.index);
        
        if (selectedStartIndex === -1) {
            // First click - set start
            selectedStartIndex = index;
            selectedEndIndex = index;
        } else if (index === selectedStartIndex && index === selectedEndIndex) {
            // Clicked same single slot - deselect
            selectedStartIndex = -1;
            selectedEndIndex = -1;
        } else if (index > selectedEndIndex) {
            // Extend selection forward - check if all slots in between are available
            let allAvailable = true;
            for (let i = selectedStartIndex; i <= index; i++) {
                if (!slotsData[i].available) {
                    allAvailable = false;
                    break;
                }
            }
            
            if (allAvailable) {
                selectedEndIndex = index;
            } else {
                // Reset and start new selection
                selectedStartIndex = index;
                selectedEndIndex = index;
            }
        } else if (index < selectedStartIndex) {
            // Clicked earlier slot - new start
            selectedStartIndex = index;
            selectedEndIndex = index;
        } else {
            // Clicked within range - shrink to that slot
            selectedStartIndex = index;
            selectedEndIndex = index;
        }
        
        // Update hidden inputs
        if (selectedStartIndex !== -1 && selectedEndIndex !== -1) {
            startTimeInput.value = slotsData[selectedStartIndex].start;
            endTimeInput.value = slotsData[selectedEndIndex].end;
            selectedStartSlot = slotsData[selectedStartIndex].start;
            selectedEndSlot = slotsData[selectedEndIndex].end;
        } else {
            startTimeInput.value = '';
            endTimeInput.value = '';
            selectedStartSlot = null;
            selectedEndSlot = null;
        }
        
        renderSlots();
        updateSummary();
        updateSubmitButton();
    };
    
    function updateSummary() {
        if (!selectedStartSlot || !selectedEndSlot || !courtIdInput.value) {
            summaryContent.innerHTML = '<div class="empty-state" style="padding: 1.5rem;"><p style="color: var(--text-secondary);">Select time slots to see summary</p></div>';
            return;
        }
        
        const selectedCard = document.querySelector('.court-card.selected');
        if (!selectedCard) return;
        
        const courtName = selectedCard.dataset.name;
        const rate = parseFloat(selectedCard.dataset.rate);
        const date = bookingDate.value;
        
        const startHour = parseInt(selectedStartSlot.split(':')[0]);
        const endHour = parseInt(selectedEndSlot.split(':')[0]);
        const hours = endHour - startHour;
        const total = rate * hours;
        
        summaryContent.innerHTML = `
            <div style="display: grid; gap: 0.75rem;">
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-secondary);">Court:</span>
                    <span style="font-weight: 600;">${courtName}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-secondary);">Date:</span>
                    <span style="font-weight: 600;">${new Date(date).toLocaleDateString('en-MY', { weekday: 'short', month: 'short', day: 'numeric' })}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-secondary);">Time:</span>
                    <span style="font-weight: 600;">${formatTime(selectedStartSlot)} - ${formatTime(selectedEndSlot)}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 0.75rem; background: var(--bg-card-hover); border-radius: var(--radius-sm);">
                    <span style="color: var(--text-secondary);">Duration:</span>
                    <span style="font-weight: 600;">${hours} hour${hours > 1 ? 's' : ''}</span>
                </div>
                <div style="display: flex; justify-content: space-between; padding: 1rem; background: var(--gradient-primary); border-radius: var(--radius-sm); color: white;">
                    <span style="font-weight: 600;">Total:</span>
                    <span style="font-size: 1.5rem; font-weight: 800;">RM ${total.toFixed(2)}</span>
                </div>
            </div>
        `;
    }
    
    function updateSubmitButton() {
        const isValid = courtIdInput.value && bookingDate.value && startTimeInput.value && endTimeInput.value;
        submitBtn.disabled = !isValid;
        
        if (isValid) {
            const startHour = parseInt(startTimeInput.value.split(':')[0]);
            const endHour = parseInt(endTimeInput.value.split(':')[0]);
            const hours = endHour - startHour;
            submitBtnText.textContent = `Book & Pay (${hours} hour${hours > 1 ? 's' : ''})`;
        } else {
            submitBtnText.textContent = 'Select Time Slots to Book';
        }
    }
    
    // Initial load if court is preselected
    if (courtIdInput.value) {
        fetchAvailability();
    }
    
    // Auto-refresh every 30 seconds
    setInterval(() => {
        if (courtIdInput.value && bookingDate.value) {
            fetchAvailability();
        }
    }, 30000);
});
</script>
@endpush
@endsection
