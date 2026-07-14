@extends('layout_inner_page')

@section('front-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    body {
        background-color: #f4f6f9;
    }
    .section-title {
        font-size: 1.15rem;
        font-weight: 700;
        color: #1a1a1a;
    }
    .custom-addons-card {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 16px rgba(0,0,0,0.03);
        background: #ffffff;
        height: 100%;
    }
    .custom-card-header {
        background-color: #ffffff;
        border-bottom: 1px solid #edf2f7;
        padding: 16px 20px;
    }
    .ssr-item-label {
        width: 100%;
        height: 100%;
        margin: 0;
        cursor: pointer;
    }
    .ssr-card-selectable {
        border: 1px solid #e2e8f0;
        border-radius: 10px;
        background: #fff;
        padding: 14px;
        height: 100%;
        transition: all 0.2s ease-in-out;
        position: relative;
    }
    .ssr-card-selectable:hover {
        border-color: #aa0022;
        background-color: #f7faff;
    }
    .ssr-radio-input:checked + .ssr-card-selectable,
    .ssr-check-input:checked + .ssr-card-selectable {
        border-color: #aa0022;
        background-color: #ecf5ff;
        box-shadow: 0 0 0 1px #aa0022;
    }
    .price-badge-top {
        position: absolute;
        top: 8px;
        end: 8px;
        background: #f1f5f9;
        color: #334155;
        font-weight: 700;
        font-size: 0.7rem;
        padding: 2px 7px;
        border-radius: 20px;
    }
    .ssr-radio-input:checked + .ssr-card-selectable .price-badge-top {
        background: #aa0022;
        color: #fff;
    }

    /* Airplane Canvas Wrapper matching image color */
    .airplane-canvas {
        background-color: #adcdeb;
        border-radius: 12px;
        padding: 40px 20px;
        width: 100%;
        display: flex;
        justify-content: center;
    }

    /* Structural Aircraft Body Configuration */
    .cabin-body-frame {
        width: 100%;
        max-width: 460px;
        background: #ffffff;
        position: relative;
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    /* Front Aircraft Cockpit Shape */
    .aircraft-nose {
        width: 100%;
        height: 180px;
        background: #ffffff;
        border-top-left-radius: 220px 180px;
        border-top-right-radius: 220px 180px;
        position: relative;
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
        align-items: center;
        padding-bottom: 20px;
    }
    /* Windshield Glass Elements */
    .cockpit-windows {
        display: flex;
        gap: 12px;
        position: absolute;
        top: 60px;
    }
    .window-pane {
        width: 55px;
        height: 24px;
        background: #4a5568;
        transform: skewY(-12deg);
        border-radius: 4px;
    }
    .window-pane.pane-right {
        transform: skewY(12deg);
    }

    /* Rear Aircraft Tail Section Shape */
    .aircraft-tail-zone {
        width: 100%;
        height: 240px;
        background: #ffffff;
        border-bottom-left-radius: 220px 300px;
        border-bottom-right-radius: 220px 300px;
        position: relative;
        margin-top: -5px;
    }
    /* Horizontal Tail Fin Accents */
    .tail-fin-left, .tail-fin-right {
        position: absolute;
        bottom: 40px;
        width: 50px;
        height: 110px;
        background: #d11a1a;
    }
    .tail-fin-left {
        left: -48px;
        border-top-left-radius: 100% 20%;
        border-bottom-left-radius: 10px;
        transform: skewY(15deg);
    }
    .tail-fin-right {
        right: -48px;
        border-top-right-radius: 100% 20%;
        border-bottom-right-radius: 10px;
        transform: skewY(-15deg);
    }
    /* Rear Fuselage Center Projection line */
    .tail-cone-shadow {
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 28px;
        height: 180px;
        background: #cbd5e1;
        border-radius: 14px;
    }

    /* Scroll Container inside the fuselage body */
    .cabin-scroll-frame {
        width: 100%;
        max-height: 520px;
        overflow-y: auto;
        padding: 0 20px;
        background: #ffffff;
    }
    .cabin-scroll-frame::-webkit-scrollbar {
        width: 5px;
    }
    .cabin-scroll-frame::-webkit-scrollbar-thumb {
        background: #cbd5e1;
        border-radius: 4px;
    }

    /* Cabin Interior Signage utilities */
    .exit-signage-row {
        display: flex;
        justify-content: space-between;
        width: 100%;
        padding: 12px 10px;
        color: #d11a1a;
        font-weight: 800;
        font-size: 0.65rem;
        letter-spacing: 0.5px;
        align-items: center;
    }
    .facility-icon-wrap {
        color: #94a3b8;
        font-size: 1.1rem;
        display: flex;
        gap: 20px;
        padding: 10px 0;
    }

    /* Row Layout & Seats */
    .aisle-space {
        width: 36px;
        font-weight: 700;
        font-size: 0.8rem;
        color: #64748b;
    }
    .seat-wrapper {
        width: 45px;
        height: 45px;
    }
    .seat-box {
        width: 100%;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
        border: 1px solid #cbd5e1;
        border-radius: 5px;
        background-color: #ffffff;
        cursor: pointer;
        transition: all 0.15s ease;
    }
    .seat-box strong {
        font-size: 0.7rem;
        color: #334155;
        line-height: 1.1;
    }
    .seat-box .seat-price {
        font-size: 0.55rem;
        color: #64748b;
        font-weight: 600;
        margin-top: 1px;
    }
    .seat-box:hover {
        border-color: #aa0022;
        background-color: #f0f7ff;
    }
    .seat-box.seat-free {
        background-color: #e2f5ea;
        border-color: #a3e635;
    }
    .seat-box.seat-free strong {
        color: #15803d;
    }
    .seat-box.seat-free .seat-price {
        color: #166534;
    }
    .seat-radio:checked + .seat-box {
        border-color: #aa0022;
        background-color: #aa0022;
    }
    .seat-radio:checked + .seat-box strong,
    .seat-radio:checked + .seat-box .seat-price {
        color: #ffffff !important;
    }
</style>

<div class="container py-4">
    <div class="d-flex align-items-center justify-content-between mb-4 pb-3 border-bottom">
        <div>
            <h4 class="fw-bold text-dark mb-1" style="letter-spacing: -0.5px;">Customize Your Journey</h4>
            <p class="text-muted small mb-0">Select optional add-ons to make your flight experience comfortable.</p>
        </div>
        <span class="badge bg-primary-subtle text-primary border border-primary-subtle px-3 py-2 rounded-pill fw-semibold small">Optional Add-ons</span>
    </div>

    <form action="{{ route('flight.after.ssr') }}" method="POST">
        @csrf

        <div class="row g-4">
            {{-- ================= LEFT COLUMN: BAGGAGE & MEALS ================= --}}
            <div class="col-lg-6 d-flex flex-column gap-4">
                
                {{-- BAGGAGE CONTAINER --}}
                <div class="card custom-addons-card">
                    <div class="custom-card-header d-flex align-items-center">
                        <div class="rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(0, 140, 255, 0.1); color: #aa0022;">
                            <i class="fa-solid fa-suitcase-rolling fs-5"></i>
                        </div>
                        <div>
                            <h5 class="section-title mb-0">Add Extra Baggage</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-6 col-sm-4">
                                <label class="ssr-item-label">
                                    <input type="radio" name="baggage" value="" class="ssr-radio-input d-none" checked>
                                    <div class="ssr-card-selectable text-center">
                                        <i class="fa-solid fa-briefcase text-muted mb-2 d-block fs-4"></i>
                                        <div class="fw-bold text-dark small">Standard</div>
                                        <div class="text-success fw-semibold mt-1" style="font-size: 0.7rem;">Included in Fare</div>
                                    </div>
                                </label>
                            </div>

                            @foreach(($ssr['Response']['Baggage'] ?? []) as $bags)
                                @foreach($bags as $bag)
                                    @if($bag['Code'] !== 'NoBaggage')
                                    <div class="col-6 col-sm-4">
                                        <label class="ssr-item-label">
                                            <input type="radio" name="baggage" value="{{ $bag['Code'] }}" class="ssr-radio-input d-none">
                                            <div class="ssr-card-selectable text-center">
                                                <span class="price-badge-top">₹{{ $bag['Price'] }}</span>
                                                <i class="fa-solid fa-weight-hanging text-primary mb-2 d-block fs-4"></i>
                                                <div class="fw-bold text-dark small">+{{ $bag['Weight'] }} KG Extra</div>
                                                <div class="text-muted text-truncate mt-1 px-1" style="font-size: 0.65rem;" title="{{ $bag['Text'] ?? 'Check-in Luggage' }}">
                                                    {{ $bag['Text'] ?? 'Check-in Luggage' }}
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

                {{-- MEALS CONTAINER --}}
                <div class="card custom-addons-card">
                    <div class="custom-card-header d-flex align-items-center">
                        <div class="rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(25, 135, 84, 0.1); color: #198754;">
                            <i class="fa-solid fa-utensils fs-5"></i>
                        </div>
                        <div>
                            <h5 class="section-title mb-0">In-Flight Meals</h5>
                        </div>
                    </div>

                    <div class="card-body p-4">
                        <div class="row g-3">
                            <div class="col-12 col-sm-6">
                                <label class="ssr-item-label">
                                    <input type="radio" name="meal" value="" class="ssr-radio-input d-none" checked>
                                    <div class="ssr-card-selectable d-flex align-items-center p-3">
                                        <div class="me-3 text-muted"><i class="fa-solid fa-circle-xmark fs-3"></i></div>
                                        <div>
                                            <div class="fw-bold text-dark small">No Meals Required</div>
                                            <small class="text-muted" style="font-size: 0.7rem;">Proceed without meal options</small>
                                        </div>
                                    </div>
                                </label>
                            </div>

                            @foreach(($ssr['Response']['MealDynamic'] ?? []) as $mealGroup)
                                @foreach($mealGroup as $meal)
                                    @if($meal['Code'] !== 'NoMeal' && !empty($meal['AirlineDescription']))
                                    <div class="col-12 col-sm-6">
                                        <label class="ssr-item-label">
                                            <input type="radio" name="meal" value="{{ $meal['Code'] }}" class="ssr-radio-input d-none">
                                            <div class="ssr-card-selectable d-flex align-items-center p-3">
                                                <span class="price-badge-top">₹{{ $meal['Price'] }}</span>
                                                <div class="me-3 text-success">
                                                    <i class="fa-solid fa-bowl-food fs-3"></i>
                                                </div>
                                                <div class="text-truncate pe-4">
                                                    <div class="fw-bold text-dark small text-truncate" title="{{ $meal['AirlineDescription'] }}">
                                                        {{ $meal['AirlineDescription'] }}
                                                    </div>
                                                    <small class="text-muted text-uppercase" style="font-size: 0.65rem;">Code: {{ $meal['Code'] }}</small>
                                                </div>
                                            </div>
                                        </label>
                                    </div>
                                    @endif
                                @endforeach
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>

            {{-- ================= RIGHT COLUMN: AIRCRAFT CORE SEATS MAP ================= --}}
            <div class="col-lg-6">
                <div class="card custom-addons-card">
                    <div class="custom-card-header d-flex align-items-center">
                        <div class="rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(255, 193, 7, 0.15); color: #b48608;">
                            <i class="fa-solid fa-chair fs-5"></i>
                        </div>
                        <div>
                            <h5 class="section-title mb-0">Select Preferred Seats</h5>
                        </div>
                    </div>

                    <div class="card-body p-3 bg-white">
                        <div class="airplane-canvas">
                            
                            <div class="cabin-body-frame shadow" style="background-color: #adcdeb;">
                                
                                <div class="aircraft-nose">
                                    <div class="cockpit-windows">
                                        <div class="window-pane"></div>
                                        <div class="window-pane pane-right"></div>
                                    </div>
                                    <div class="facility-icon-wrap">
                                        <i class="fa-solid fa-restroom"></i>
                                        <i class="fa-solid fa-restroom"></i>
                                    </div>
                                    <div class="exit-signage-row">
                                        <span><i class="fa-solid fa-caret-left"></i> EXIT</span>
                                        <span>EXIT <i class="fa-solid fa-caret-right"></i></span>
                                    </div>
                                </div>
                                
                                <div class="cabin-scroll-frame">
                                    <div class="d-flex flex-column gap-2 align-items-center">
                                        @php $rowCounter = 1; @endphp
                                        @foreach(($ssr['Response']['SeatDynamic'] ?? []) as $segment)
                                            @foreach(($segment['SegmentSeat'] ?? []) as $segmentSeat)
                                                @foreach(($segmentSeat['RowSeats'] ?? []) as $row)
                                                    @if(!empty($row['Seats']))
                                                        <div class="d-flex align-items-center justify-content-center">
                                                            
                                                            <div class="d-flex gap-1">
                                                                @foreach(array_slice($row['Seats'], 0, 3) as $seat)
                                                                    @if(!empty($seat['SeatNo']))
                                                                        <div class="seat-wrapper">
                                                                            <label class="m-0 w-100 h-100 position-relative">
                                                                                <input type="radio" name="seat" value="{{ $seat['Code'] }}" class="seat-radio d-none">
                                                                                <div class="seat-box {{ $seat['Price'] == 0 ? 'seat-free' : '' }}">
                                                                                    <strong>{{ $seat['RowNo'] }}{{ $seat['SeatNo'] }}</strong>
                                                                                    <span class="seat-price">₹{{ $seat['Price'] }}</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    @else
                                                                        <div class="seat-wrapper"></div>
                                                                    @endif
                                                                @endforeach
                                                            </div>

                                                            <div class="aisle-space text-center">
                                                                {{ $rowCounter++ }}
                                                            </div>

                                                            <div class="d-flex gap-1">
                                                                @foreach(array_slice($row['Seats'], 3, 3) as $seat)
                                                                    @if(!empty($seat['SeatNo']))
                                                                        <div class="seat-wrapper">
                                                                            <label class="m-0 w-100 h-100 position-relative">
                                                                                <input type="radio" name="seat" value="{{ $seat['Code'] }}" class="seat-radio d-none">
                                                                                <div class="seat-box {{ $seat['Price'] == 0 ? 'seat-free' : '' }}">
                                                                                    <strong>{{ $seat['RowNo'] }}{{ $seat['SeatNo'] }}</strong>
                                                                                    <span class="seat-price">₹{{ $seat['Price'] }}</span>
                                                                                </div>
                                                                            </label>
                                                                        </div>
                                                                    @else
                                                                        <div class="seat-wrapper"></div>
                                                                    @endif
                                                                @endforeach
                                                            </div>

                                                        </div>
                                                    @endif
                                                @endforeach
                                            @endforeach
                                        @endforeach
                                    </div>
                                </div>

                                <div class="w-100 bg-white text-center pt-2">
                                    <div class="exit-signage-row">
                                        <span><i class="fa-solid fa-caret-left"></i> EXIT</span>
                                        <span>EXIT <i class="fa-solid fa-caret-right"></i></span>
                                    </div>
                                    <div class="facility-icon-wrap justify-content-center w-100">
                                        <i class="fa-solid fa-coffee"></i>
                                        <i class="fa-solid fa-restroom"></i>
                                        <i class="fa-solid fa-coffee"></i>
                                    </div>
                                </div>
                                <div class="aircraft-tail-zone">
                                    <div class="tail-fin-left"></div>
                                    <div class="tail-fin-right"></div>
                                    <div class="tail-cone-shadow"></div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- ================= SPECIAL SERVICES MODULE ================= --}}
        @if(isset($ssr['Response']['SpecialServices']))
        <div class="card custom-addons-card my-4">
            <div class="custom-card-header d-flex align-items-center">
                <div class="rounded-3 me-3 d-inline-flex align-items-center justify-content-center" style="width: 36px; height: 36px; background-color: rgba(13, 202, 240, 0.1); color: #0dcaf0;">
                    <i class="fa-solid fa-handshake-angle fs-5 text-dark"></i>
                </div>
                <div>
                    <h5 class="section-title mb-0">Assistance Requests</h5>
                </div>
            </div>

            <div class="card-body p-4">
                <div class="row g-2">
                    @foreach($ssr['Response']['SpecialServices'] as $services)
                        @foreach($services['SegmentSpecialService'] ?? [] as $segment)
                            @foreach($segment['SSRService'] ?? [] as $service)
                                <div class="col-md-6">
                                    <label class="ssr-item-label">
                                        <input type="checkbox" name="services[]" value="{{ $service['SSRCode'] ?? '' }}" class="ssr-check-input d-none">
                                        <div class="ssr-card-selectable d-flex align-items-center justify-content-between p-2.5">
                                            <div class="d-flex align-items-center text-truncate pe-2">
                                                <div class="me-2 text-info"><i class="fa-solid fa-circle-check"></i></div>
                                                <div class="text-truncate">
                                                    <span class="fw-semibold text-dark text-truncate d-block small" style="line-height: 1.2;">{{ $service['Text'] ?? ($service['SSRCode'] ?? 'Special Assistance') }}</span>
                                                    <small class="text-muted" style="font-size: 0.65rem;">Code: {{ $service['SSRCode'] ?? 'N/A' }}</small>
                                                </div>
                                            </div>
                                            @if(isset($service['Price']))
                                                <span class="badge bg-light text-dark border rounded-pill px-2 py-1 fw-bold" style="font-size: 0.65rem;">₹{{ $service['Price'] }}</span>
                                            @endif
                                        </div>
                                    </label>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>
            </div>
        </div>
        @endif

        <div class="d-flex align-items-center justify-content-between p-3 mt-4 bg-white border border-light-subtle rounded-3 shadow-sm">
            <a href="{{ url()->previous() }}" class="btn btn-link text-decoration-none text-secondary fw-semibold small p-0">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to Review
            </a>
            
            <button type="button" id="payBtn"
    class="btn btn-primary px-5 fw-bold rounded-2 shadow-sm py-2"
    style="font-size:0.9rem;background:#aa0022;border:none;">
    Pay & Continue
    <i class="fa-solid fa-arrow-right ms-1"></i>
</button>
        </div>

    </form>
</div>


<script src="https://checkout.razorpay.com/v1/checkout.js"></script>

<script>
document.getElementById('payBtn').onclick = function(e){

    var options = {

        key: "{{ config('services.razorpay.key') }}",

        amount: "{{ session('flight.fare_quote')['Response']['Results']['Fare']['PublishedFare'] * 100 }}",

        currency: "INR",

        name: "Travel & Time",

        description: "Flight Booking",

        handler: function (response){

            let form = document.querySelector("form");

            let payment = document.createElement("input");
            payment.type = "hidden";
            payment.name = "razorpay_payment_id";
            payment.value = response.razorpay_payment_id;

            form.appendChild(payment);

            form.submit();
        }

    };

    var rzp = new Razorpay(options);

    rzp.open();

    e.preventDefault();
}
</script>
@endsection