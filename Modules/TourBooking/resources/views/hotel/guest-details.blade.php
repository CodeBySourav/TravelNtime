@extends('layout_inner_page')

@section('front-content')
<div class="container py-5">
    <div class="row g-4">
        
        <!-- Left: Booking & Guest Details Form -->
        <div class="col-lg-8">
            <form action="{{ route('hotel.book') }}" method="POST" id="bookingForm" class="needs-validation" novalidate>
                @csrf
                <input type="hidden" name="trace_id" value="{{ $blockRoom['TraceId'] }}">
                <input type="hidden" name="booking_code" value="{{ $blockRoom['BookingCode'] ?? '' }}">

                <!-- Section 1: Contact Information -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center mb-3 text-brand">
                            <i class="bi bi-envelope-paper-fill me-2 fs-4"></i>
                            <h4 class="mb-0 fw-bold">{{ __('Contact Details') }}</h4>
                        </div>
                        <p class="text-muted small mb-4">Booking confirmation and updates will be sent to these details.</p>
                        
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-envelope"></i></span>
                                    <input type="email" name="email" class="form-control bg-light border-start-0" placeholder="alex@example.com" required>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-secondary small">{{ __('Mobile Number') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0 text-muted"><i class="bi bi-telephone"></i></span>
                                    <input type="tel" name="mobile" class="form-control bg-light border-start-0" placeholder="e.g. +91 98765 43210" required>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Section 2: Room Guests Info -->
                <div class="card border-0 shadow-sm rounded-4 mb-4 bg-white">
                    <div class="card-body p-4">
                        <div class="d-flex flex-wrap justify-content-between align-items-center gap-3 mb-4">
                            <div class="d-flex align-items-center text-brand">
                                <i class="bi bi-people-fill me-2 fs-4"></i>
                                <h4 class="mb-0 fw-bold">{{ __('Guest Information') }}</h4>
                            </div>
                            
                            <div style="min-width: 160px;">
                                <label class="visually-hidden" for="guestCount">{{ __('Number of Guests') }}</label>
                                <select id="guestCount" class="form-select form-select-sm border-2 border-brand rounded-pill fw-semibold text-brand-dark" name="guest_count" required>
                                    <option value="1">1 Guest</option>
                                    <option value="2">2 Guests</option>
                                    <option value="3">3 Guests</option>
                                    <option value="4">4 Guests</option>
                                </select>
                            </div>
                        </div>

                        <!-- Dynamic container for adding guest name cards -->
                        <div id="guestContainer" class="d-flex flex-column gap-3"></div>
                    </div>
                </div>

                <!-- Call to Action -->
                <div class="d-grid d-md-flex justify-content-md-end mb-5">
                    <button type="submit" class="btn btn-brand btn-lg px-5 py-3 rounded-pill fw-bold shadow-sm hover-up">
                        {{ __('Proceed to Payment') }}
                        <i class="bi bi-arrow-right ms-2"></i>
                    </button>
                </div>
            </form>
        </div>

        <!-- Right Side: Booking Summary Sticky Panel -->
        @if(isset($blockRoom['HotelRoomsDetails'][0]))
            @php $room = $blockRoom['HotelRoomsDetails'][0]; @endphp
            <div class="col-lg-4">
                <div class="card border-0 shadow-sm rounded-4 bg-white sticky-top" style="top: 2rem; z-index: 10;">
                    <div class="card-body p-4">
                        <span class="badge bg-brand-subtle text-brand mb-2 px-3 py-2 rounded-pill fw-semibold uppercase tracking-wider small">
                            {{ __('Your Stay Summary') }}
                        </span>
                        
                        <h4 class="fw-bold text-dark mb-1 mt-2">{{ $blockRoom['HotelName'] }}</h4>
                        <p class="text-muted small mb-4">
                            <i class="bi bi-geo-alt-fill me-1 text-danger"></i> Verified Property Reservation
                        </p>
                        
                        <hr class="text-muted opacity-25">

                        <div class="py-2">
                            <div class="text-secondary small fw-semibold mb-1">{{ __('Selected Room Option') }}</div>
                            <div class="text-dark fw-bold d-flex align-items-start">
                                <i class="bi bi-door-open me-2 text-brand fs-5 mt-0.5"></i>
                                <span>{{ $room['RoomTypeName'] }}</span>
                            </div>
                        </div>

                        <hr class="text-muted opacity-25">

                        <!-- Pricing Table -->
                        <div class="bg-light rounded-3 p-3 my-3">
                            <div class="d-flex justify-content-between text-secondary mb-2 small">
                                <span>Room Rate & Taxes</span>
                                <span>₹{{ number_format($room['Price']['OfferedPriceRoundedOff']) }}</span>
                            </div>
                            <div class="d-flex justify-content-between text-secondary mb-3 small">
                                <span>Booking Fees</span>
                                <span class="text-success fw-medium">FREE</span>
                            </div>
                            <div class="d-flex justify-content-between align-items-center border-top pt-3 border-secondary border-opacity-10">
                                <span class="fw-bold text-dark fs-5">{{ __('Total Amount') }}</span>
                                <span class="fw-extrabold text-brand fs-4">
                                    ₹{{ number_format($room['Price']['OfferedPriceRoundedOff']) }}
                                </span>
                            </div>
                        </div>

                        <!-- Trust Badge Info -->
                        <div class="d-flex align-items-center gap-2 text-success small bg-success-subtle p-2 rounded-3 justify-content-center">
                            <i class="bi bi-shield-check-fill fs-5"></i>
                            <span class="fw-medium">Secure checkout & instant confirmation</span>
                        </div>
                    </div>
                </div>
            </div>
        @endif

    </div>
</div>

<!-- Modern CSS Theme Overrides -->
<style>
    /* Brand Colors Utility classes */
    .text-brand { color: #aa0022 !important; }
    .text-brand-dark { color: #88001b !important; }
    .border-brand { border-color: #aa0022 !important; }
    .bg-brand-subtle { background-color: rgba(170, 0, 34, 0.08) !important; }
    
    /* Custom Button Class to replace btn-primary */
    .btn-brand {
        background-color: #aa0022 !important;
        border-color: #aa0022 !important;
        color: #ffffff !important;
    }
    .btn-brand:hover, .btn-brand:focus {
        background-color: #88001b !important;
        border-color: #88001b !important;
        color: #ffffff !important;
    }

    /* Layout & Component Architecture Rules */
    .rounded-4 { border-radius: 1rem !important; }
    .fw-extrabold { font-weight: 800; }
    .bg-success-subtle { background-color: rgba(25, 135, 84, 0.08) !important; }
    .hover-up { transition: transform 0.2s ease, box-shadow 0.2s ease; }
    .hover-up:hover { transform: translateY(-2px); box-shadow: 0 .5rem 1rem rgba(170, 0, 34, 0.15)!important; }
    
    /* Focus Ring Customization */
    .form-control:focus, .form-select:focus {
        box-shadow: 0 0 0 0.25rem rgba(170, 0, 34, 0.15);
        border-color: #aa0022 !important;
        background-color: #ffffff !important;
    }
</style>

<!-- Bootstrap Icons Loader Fallback -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

<!-- Dynamic Form Management JS -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const guestSelect = document.getElementById('guestCount');
    const container = document.getElementById('guestContainer');

    guestSelect.addEventListener('change', function () {
        const count = parseInt(this.value, 10);
        
        // Retain values entered by users instead of completely dropping HTML nodes on count updates
        const existingRows = container.querySelectorAll('.guest-input-card');
        const currentData = [];
        
        existingRows.forEach((row, idx) => {
            currentData[idx] = {
                first: row.querySelector('.fname-input')?.value || '',
                last: row.querySelector('.lname-input')?.value || ''
            };
        });

        container.innerHTML = '';

        for (let i = 0; i < count; i++) {
            const savedFirst = currentData[i]?.first || '';
            const savedLast = currentData[i]?.last || '';
            const badgeLabel = i === 0 ? '{{ __("Lead Guest") }}' : `{{ __("Guest") }} ${i + 1}`;
            const badgeStyle = i === 0 ? 'background-color: #aa0022; color: #ffffff;' : 'background-color: #e9ecef; color: #495057;';

            container.insertAdjacentHTML('beforeend', `
                <div class="guest-input-card border rounded-3 p-3 bg-white shadow-xs">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="badge px-2.5 py-1.5 rounded fw-semibold" style="${badgeStyle}">${badgeLabel}</span>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-6">
                            <input type="text" 
                                   name="guests[${i}][first_name]" 
                                   class="form-control fname-input" 
                                   placeholder="First Name" 
                                   value="${savedFirst}"
                                   required>
                        </div>
                        <div class="col-md-6">
                            <input type="text" 
                                   name="guests[${i}][last_name]" 
                                   class="form-control lname-input" 
                                   placeholder="Last Name" 
                                   value="${savedLast}"
                                   required>
                        </div>
                    </div>
                </div>
            `);
        }
    });

    // Execute setup on load
    guestSelect.dispatchEvent(new Event('change'));
});
</script>
@endsection