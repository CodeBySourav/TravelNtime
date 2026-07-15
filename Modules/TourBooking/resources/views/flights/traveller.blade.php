@extends('layout_inner_page')

@section('front-content')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap"
        rel="stylesheet">

    @php
        $adultCount = $search['adult'] ?? 1;
        $childCount = $search['child'] ?? 0;
        $infantCount = $search['infant'] ?? 0;

        $index = 0;
    @endphp


    <style>
        /* Custom Brand Styles overrides */
        :root {
            --brand-primary: #aa0022;
            --brand-primary-light: rgba(170, 0, 34, 0.05);
            --brand-primary-hover: #88001b;
        }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        .btn-brand-primary {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
            border: none !important;
            transition: all 0.2s ease-in-out;
        }

        .btn-brand-primary:hover {
            background-color: var(--brand-primary-hover) !important;
            color: #ffffff !important;
            box-shadow: 0 4px 12px rgba(170, 0, 34, 0.25);
        }

        .btn-outline-brand {
            color: var(--brand-primary) !important;
            border: 1.5px solid var(--brand-primary) !important;
            background: transparent;
            transition: all 0.2s ease-in-out;
        }

        .btn-outline-brand:hover {
            background-color: var(--brand-primary) !important;
            color: #ffffff !important;
        }

        .text-brand {
            color: var(--brand-primary) !important;
        }

        .bg-brand-light {
            background-color: var(--brand-primary-light) !important;
        }

        /* Modern Progress Track Line for Flights */
        .flight-timeline-line {
            position: relative;
            height: 2px;
            background: #e2e8f0;
            width: 100%;
            margin: 0 auto;
        }

        .flight-timeline-line::before,
        .flight-timeline-line::after {
            content: '';
            position: absolute;
            top: -3px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: #cbd5e1;
        }

        .flight-timeline-line::before {
            left: 0;
        }

        .flight-timeline-line::after {
            right: 0;
        }

        .airline-logo-container {
            width: 52px;
            height: 52px;
            background: #fff;
            border: 1px solid #edf2f7;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 10px;
            overflow: hidden;
        }

        .sticky-sidebar {
            position: -webkit-sticky;
            position: sticky;
            top: 24px;
        }

        /* Custom Checkbox Makeover */
        .brand-checkbox .custom-control-input:checked~.custom-control-label::before {
            background-color: var(--brand-primary) !important;
            border-color: var(--brand-primary) !important;
        }
    </style>

    <style>
        .ssr-card-label {
            cursor: pointer;
        }

        .ssr-selectable-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 10px;
            transition: all 0.15s ease-in-out;
            height: 70px;
        }

        .ssr-selectable-card:hover {
            border-color: #aa0022;
            background-color: #fff5f6;
            /* Very light subtle variant mix */
        }

        /* Active Highlight Border Option matching #aa0022 and #ffffff background */
        .ssr-radio:checked+.ssr-selectable-card {
            border-color: #aa0022 !important;
            background-color: #ffffff !important;
            border-width: 2px;
            box-shadow: 0 0 0 3px rgba(170, 0, 34, 0.15);
            /* Sleek theme outline shadow */
        }

        .ssr-icon-circle {
            width: 28px;
            height: 28px;
        }

        .text-truncate {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
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
        .tail-fin-left,
        .tail-fin-right {
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

        .seat-radio:checked+.seat-box {
            border-color: #aa0022;
            background-color: #aa0022;
        }

        .seat-radio:checked+.seat-box strong,
        .seat-radio:checked+.seat-box .seat-price {
            color: #ffffff !important;
        }
    </style>
    <div class="container py-4" style="background-color: #fcfcfc;">

        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.85rem;">
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Home</a></li>
                <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Flights</a></li>
                @if (!empty($fareQuote['Response']['Results']['Segments'][0][0]))
                    @php
                        $firstSeg = $fareQuote['Response']['Results']['Segments'][0][0];
                        $lastSeg = end($fareQuote['Response']['Results']['Segments'][0]);
                    @endphp
                    <li class="breadcrumb-item"><a href="#"
                            class="text-brand font-weight-bold text-decoration-none">{{ $firstSeg['Origin']['Airport']['CityName'] }}
                            → {{ $lastSeg['Destination']['Airport']['CityName'] }}</a></li>
                @endif
                <li class="breadcrumb-item active text-muted" aria-current="page">Review Itinerary</li>
            </ol>
        </nav>

        <form action="{{ route('flight.checkout') }}" method="POST">
            @csrf

            <div class="row">
                <div class="col-lg-8 col-md-12">
                    <h3 class="mb-4 font-weight-bold text-dark d-flex align-items-center">
                        <span
                            class="bg-brand-light text-brand rounded p-2 mr-3 d-inline-flex align-items-center justify-content-center"
                            style="width: 42px; height: 42px;">
                            <i class="fa fa-ticket"></i>
                        </span>
                        Review Your Itinerary
                    </h3>

                    {{-- ================= FLIGHT CARD SEGMENT SYSTEM ================= --}}
                    @if (!empty($fareQuote['Response']['Results']['Segments'][0]))
                        @foreach ($fareQuote['Response']['Results']['Segments'][0] as $segment)
                            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                                <div class="card-body p-4">
                                    <div class="row align-items-center">

                                        <div class="col-md-3 mb-3 mb-md-0">
                                            <div class="d-flex align-items-center">
                                                @php
                                                    $airlineCode = strtoupper($segment['Airline']['AirlineCode']);
                                                @endphp

                                                <div class="airline-logo-container mr-3">
                                                    <img src="https://images.kiwi.com/airlines/64/{{ $airlineCode }}.png"
                                                        alt="{{ $segment['Airline']['AirlineName'] }}"
                                                        style="width:55px;height:55px;object-fit:contain;"
                                                        onerror="this.onerror=null;this.src='{{ asset('images/airline.png') }}';">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.95rem;">
                                                        {{ $segment['Airline']['AirlineName'] }}</h6>
                                                    <small
                                                        class="text-muted font-weight-semibold">{{ $segment['Airline']['AirlineCode'] }}-{{ $segment['Airline']['FlightNumber'] }}</small>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-md-3 col-5 text-left">
                                            <h5 class="mb-0 font-weight-bold text-dark">
                                                {{ $segment['Origin']['Airport']['CityName'] }}</h5>
                                            <h3 class="my-1 font-weight-bold text-brand" style="letter-spacing: -0.5px;">
                                                {{ date('H:i', strtotime($segment['Origin']['DepTime'])) }}</h3>
                                            <p class="mb-0 text-dark small font-weight-bold">
                                                {{ date('D, d M', strtotime($segment['Origin']['DepTime'])) }}</p>
                                            <small class="text-muted d-block text-truncate"
                                                title="{{ $segment['Origin']['Airport']['AirportName'] }}">{{ $segment['Origin']['Airport']['AirportName'] }}</small>
                                        </div>

                                        <div class="col-md-3 col-2 text-center px-1">
                                            <small
                                                class="text-muted font-weight-bold d-block mb-1">{{ floor($segment['Duration'] / 60) }}h
                                                {{ $segment['Duration'] % 60 }}m</small>
                                            <div class="flight-timeline-line my-2">
                                                <span class="position-absolute text-brand bg-white px-2"
                                                    style="top: -10px; left: 35%; font-size: 0.9rem;"><i
                                                        class="fa fa-plane"></i></span>
                                            </div>
                                            <span class="badge badge-light border text-muted font-weight-bold py-1 px-2"
                                                style="font-size:0.7rem; border-radius: 4px;">NON-STOP</span>
                                        </div>

                                        <div class="col-md-3 col-5 text-right">
                                            <h5 class="mb-0 font-weight-bold text-dark">
                                                {{ $segment['Destination']['Airport']['CityName'] }}</h5>
                                            <h3 class="my-1 font-weight-bold text-brand" style="letter-spacing: -0.5px;">
                                                {{ date('H:i', strtotime($segment['Destination']['ArrTime'])) }}</h3>
                                            <p class="mb-0 text-dark small font-weight-bold">
                                                {{ date('D, d M', strtotime($segment['Destination']['ArrTime'])) }}</p>
                                            <small class="text-muted d-block text-truncate"
                                                title="{{ $segment['Destination']['Airport']['AirportName'] }}">{{ $segment['Destination']['Airport']['AirportName'] }}</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="card-footer border-0 d-flex flex-wrap justify-content-between align-items-center px-4 py-3"
                                    style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; background-color: #fdfdfd; border-top: 1px solid #f3f3f3 !important;">
                                    <span class="small text-muted font-weight-semibold">
                                        <i class="fa fa-briefcase mr-1 text-brand"></i> Baggage Allowances: Check-in <strong
                                            class="text-dark">{{ $segment['Baggage'] ?? '15 KG' }}</strong> • Cabin <strong
                                            class="text-dark">{{ $segment['CabinBaggage'] ?? '7 KG' }}</strong>
                                    </span>
                                    <a href="#" class="small font-weight-bold text-brand text-decoration-none"><i
                                            class="fa fa-info-circle mr-1"></i> Policy Rules</a>
                                </div>
                            </div>
                        @endforeach
                    @endif

                    {{-- ================= RESTRICTION FARE CONDITIONS NOTE ================= --}}
                    <div class="card border-0 mb-4 shadow-sm"
                        style="border-radius: 16px; border-left: 5px solid var(--brand-primary) !important; background: #ffffff;">
                        <div class="card-body p-4">
                            <h5 class="text-brand font-weight-bold mb-3 d-flex align-items-center">
                                <i class="fa fa-exclamation-triangle mr-2"></i> Special Category Group Fare Conditions
                            </h5>
                            <ul class="small text-muted pl-3 mb-4" style="line-height: 1.75; list-style-type: square;">
                                <li class="mb-2"><strong class="text-dark">Non-Refundable & Non-Changeable Fare:</strong>
                                    Cancellation, date modification, or routing re-issuance is strictly prohibited
                                    post-booking confirmations.</li>
                                <li class="mb-2">Passenger data integration populates on individual airline system nodes
                                    roughly <span class="text-brand font-weight-bold">12–24 hours before flight
                                        departure.</span></li>
                                <li class="mb-2">Mandatory check-in execution timelines open explicitly <span
                                        class="text-brand font-weight-bold">one day prior starting 8:00 PM onwards.</span>
                                </li>
                                <li>Dynamic meals, extra luggage weights, or seat charts can be purchased manually directly
                                    from the operating carrier during web check-in windows.</li>
                            </ul>
                            <hr style="border-top: 1px solid #f1f5f9;">
                            <div class="custom-control custom-checkbox brand-checkbox mt-2">
                                <input type="checkbox" class="custom-control-input" id="termsCheck" required>
                                <label class="custom-control-label small font-weight-bold text-dark"
                                    style="cursor: pointer;" for="termsCheck">I declare that I have carefully verified and
                                    understood the flight specific fare rules mentioned above.</label>
                            </div>
                        </div>
                    </div>

                    {{-- ================= PASSENGER MANAGEMENT HEADER ================= --}}
                    <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 mt-4 gap-2">
                        <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-users mr-2 text-muted"></i>Passenger
                            Details <span class="text-muted small font-weight-normal">(0/1 Configured)</span></h5>
                        <button type="button" class="btn btn-outline-brand btn-sm rounded-pill font-weight-bold px-3"><i
                                class="fa fa-plus-circle mr-1"></i> Add New Traveller</button>
                    </div>

                    {{-- ================= PASSENGER REGISTRATION FORM CARD ================= --}}
                    @for ($i = 1; $i <= $adultCount; $i++)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                <h5>Adult {{ $i }}</h5>

                                <div class="row">

                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select name="travellers[{{ $index }}][title]" class="form-control"
                                            required>
                                            <option value="">Select</option>
                                            <option>Mr</option>
                                            <option>Mrs</option>
                                            <option>Ms</option>
                                        </select>
                                    </div>

                                    <div class="col-md-5">
                                        <label>First Name</label>
                                        <input type="text" name="travellers[{{ $index }}][first_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-5">
                                        <label>Last Name</label>
                                        <input type="text" name="travellers[{{ $index }}][last_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>DOB</label>
                                        <input type="date" name="travellers[{{ $index }}][dob]" data-pax="1"
                                            class="form-control dob-field" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Gender</label>
                                        <select name="travellers[{{ $index }}][gender]" class="form-control"
                                            required>

                                            <option value="">Select</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Passenger Type</label>

                                        <input type="hidden" name="travellers[{{ $index }}][pax_type]"
                                            value="1">

                                        <input type="text" class="form-control" value="Adult" readonly>
                                    </div>

                                </div>

                            </div>
                        </div>

                        @php $index++; @endphp
                    @endfor


                    @for ($i = 1; $i <= $childCount; $i++)
                        <div class="card mb-3">
                            <div class="card-body">

                                <h5>Child {{ $i }}</h5>

                                <div class="row">

                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select name="travellers[{{ $index }}][title]" class="form-control"
                                            required>

                                            <option value="">Select</option>
                                            <option value="Master">Master</option>
                                            <option value="Miss">Miss</option>

                                        </select>
                                    </div>

                                    <div class="col-md-5">
                                        <label>First Name</label>
                                        <input type="text" name="travellers[{{ $index }}][first_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-5">
                                        <label>Last Name</label>
                                        <input type="text" name="travellers[{{ $index }}][last_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>DOB</label>
                                        <input type="date" name="travellers[{{ $index }}][dob]" data-pax="2"
                                            class="form-control dob-field" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Gender</label>
                                        <select name="travellers[{{ $index }}][gender]" class="form-control"
                                            required>

                                            <option value="">Select</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Passenger Type</label>

                                        <input type="hidden" name="travellers[{{ $index }}][pax_type]"
                                            value="2">

                                        <input type="text" class="form-control" value="Child" readonly>
                                    </div>

                                </div>

                            </div>
                        </div>

                        @php $index++; @endphp
                    @endfor

                    @for ($i = 1; $i <= $infantCount; $i++)
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">

                                <h5>Infant {{ $i }}</h5>

                                <div class="row">

                                    <div class="col-md-2">
                                        <label>Title</label>
                                        <select name="travellers[{{ $index }}][title]" class="form-control"
                                            required>

                                            <option value="">Select</option>
                                            <option value="Master">Master</option>
                                            <option value="Miss">Miss</option>

                                        </select>
                                    </div>

                                    <div class="col-md-5">
                                        <label>First Name</label>
                                        <input type="text" name="travellers[{{ $index }}][first_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-5">
                                        <label>Last Name</label>
                                        <input type="text" name="travellers[{{ $index }}][last_name]"
                                            class="form-control" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>DOB</label>
                                        <input type="date" name="travellers[{{ $index }}][dob]" data-pax="3"
                                            class="form-control dob-field" required>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Gender</label>
                                        <select name="travellers[{{ $index }}][gender]" class="form-control"
                                            required>

                                            <option value="">Select</option>
                                            <option value="1">Male</option>
                                            <option value="2">Female</option>

                                        </select>
                                    </div>

                                    <div class="col-md-4">
                                        <label>Passenger Type</label>

                                        <input type="hidden" name="travellers[{{ $index }}][pax_type]"
                                            value="3">

                                        <input type="text" class="form-control" value="Infant" readonly>
                                    </div>

                                </div>

                            </div>
                        </div>

                        @php $index++; @endphp
                    @endfor

                    {{-- ================= CONTACT INFORMATION PROFILE ================= --}}
                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                        <div class="card-body p-4">
                            <h5 class="font-weight-bold text-dark mb-2 d-flex align-items-center">
                                <i class="fa fa-envelope-open-o mr-2 text-muted"></i> Contact Information
                            </h5>
                            <p class="small text-muted mb-4">Your electronic tickets, confirmation receipts, and terminal
                                delay notices will be communicated here.</p>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Mobile Number *</label>
                                    <div class="input-group shadow-xs">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text font-weight-bold bg-light text-dark"
                                                style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-color: #e2e8f0;">+91</span>
                                        </div>
                                        <input type="text" name="mobile" class="form-control border-light"
                                            style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; height: 42px;"
                                            placeholder="Mobile Number" required>
                                    </div>
                                </div>

                                <div class="col-md-6 mb-3">
                                    <label class="small font-weight-bold text-muted">Email ID Address *</label>
                                    <input type="email" name="email" class="form-control border-light shadow-xs"
                                        style="border-radius: 8px; height: 42px;" placeholder="Email Address" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- ================= OPTIONAL ACCORDION SERVICES SELECTION ================= --}}
                    <div class="accordion mb-4" id="ssrAccordion">

                        <div class="card border-0 shadow-sm mb-3"
                            style="border-radius: 12px; overflow: hidden; background: #ffffff;">
                            <div class="card-header bg-white p-3 border-0" id="headingMeal">
                                <button
                                    class="btn btn-block text-left font-weight-bold text-dark p-0 d-flex justify-content-between align-items-center"
                                    type="button" data-toggle="collapse" data-bs-toggle="collapse"
                                    data-target="#collapseMeal" data-bs-target="#collapseMeal">
                                    <span class="d-flex align-items-center" style="color: #aa0022;">
                                        <i class="fa fa-cutlery mr-2"></i> <span class="text-dark">In-Flight Meal
                                            Selections</span>
                                        <span class="badge border ml-2 font-weight-normal px-2 py-1"
                                            style="font-size: 11px; border-radius: 6px; color: #aa0022; border-color: #aa0022; background-color: #ffffff;">Optional</span>
                                    </span>
                                    <i class="fa fa-chevron-down small text-muted"></i>
                                </button>
                            </div>
                            <div id="collapseMeal" class="collapse show" data-parent="#ssrAccordion"
                                data-bs-parent="#ssrAccordion">
                                <div class="card-body pt-1 pb-3 px-3" style="background-color: #ffffff;">
                                    <div class="row mx-n2">

                                        <div class="col-md-4 col-sm-6 px-2 mb-2">
                                            <label class="ssr-card-label w-100 m-0">
                                                <input type="radio" name="travellers[0][meal]" value="" checked
                                                    class="d-none ssr-radio">
                                                <div class="ssr-selectable-card p-2 d-flex align-items-center">
                                                    <div
                                                        class="ssr-icon-circle mr-2 bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="fa fa-times text-secondary"
                                                            style="font-size: 14px;"></i>
                                                    </div>
                                                    <div class="text-truncate">
                                                        <h6 class="font-weight-bold text-dark mb-0 small text-truncate">No
                                                            Meals Required</h6>
                                                        <small class="text-muted text-truncate d-block"
                                                            style="font-size: 10px;">Proceed without options</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        @if (!empty($meals))
                                            @foreach ($meals as $meal)
                                                @if (($meal['Code'] ?? '') !== 'NoMeal')
                                                    <div class="col-md-4 col-sm-6 px-2 mb-2">
                                                        <label class="ssr-card-label w-100 m-0">
                                                            <input type="radio" name="travellers[0][meal]"
                                                                value="{{ $meal['Code'] }}" class="d-none ssr-radio">
                                                            <div class="ssr-selectable-card p-2 position-relative">

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span
                                                                        class="badge px-1 py-0 font-weight-bold text-white"
                                                                        style="font-size: 11px; background-color: #aa0022; border-radius: 4px;">
                                                                        ₹{{ $meal['Price'] }}
                                                                    </span>
                                                                    <small class="text-muted font-weight-bold"
                                                                        style="font-size: 9px;">
                                                                        {{ $meal['Origin'] }}-{{ $meal['Destination'] }}
                                                                    </small>
                                                                </div>

                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="ssr-icon-circle mr-2 d-flex align-items-center justify-content-center flex-shrink-0">
                                                                        <i class="fa fa-cutlery"
                                                                            style="font-size: 14px; color: #aa0022;"></i>
                                                                    </div>
                                                                    <div class="text-truncate" style="flex: 1;">
                                                                        <h6 class="font-weight-bold text-dark mb-0 small text-truncate"
                                                                            title="{{ $meal['AirlineDescription'] }}">
                                                                            {{ $meal['AirlineDescription'] ?? 'Flight Meal' }}
                                                                        </h6>
                                                                        <small
                                                                            class="text-muted font-weight-bold text-uppercase"
                                                                            style="font-size: 9px; letter-spacing: 0.3px;">
                                                                            CODE: {{ $meal['Code'] }}
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm mb-3"
                            style="border-radius: 12px; overflow: hidden; background: #ffffff;">
                            <div class="card-header bg-white p-3 border-0" id="headingBag">
                                <button
                                    class="btn btn-block text-left font-weight-bold text-dark p-0 d-flex justify-content-between align-items-center"
                                    type="button" data-toggle="collapse" data-bs-toggle="collapse"
                                    data-target="#collapseBag" data-bs-target="#collapseBag">
                                    <span class="d-flex align-items-center" style="color: #aa0022;">
                                        <i class="fa fa-shopping-bag mr-2"></i> <span class="text-dark">Additional Baggage
                                            Allowance</span>
                                        <span class="badge border ml-2 font-weight-normal px-2 py-1"
                                            style="font-size: 11px; border-radius: 6px; color: #aa0022; border-color: #aa0022; background-color: #ffffff;">Optional</span>
                                    </span>
                                    <i class="fa fa-chevron-down small text-muted"></i>
                                </button>
                            </div>
                            <div id="collapseBag" class="collapse" data-parent="#ssrAccordion"
                                data-bs-parent="#ssrAccordion">
                                <div class="card-body pt-1 pb-3 px-3" style="background-color: #ffffff;">
                                    <div class="row mx-n2">

                                        <div class="col-md-4 col-sm-6 px-2 mb-2">
                                            <label class="ssr-card-label w-100 m-0">
                                                <input type="radio" name="travellers[0][baggage]" value=""
                                                    checked class="d-none ssr-radio">
                                                <div class="ssr-selectable-card p-2 d-flex align-items-center">
                                                    <div
                                                        class="ssr-icon-circle mr-2 bg-light rounded-circle d-flex align-items-center justify-content-center flex-shrink-0">
                                                        <i class="fa fa-times text-secondary"
                                                            style="font-size: 14px;"></i>
                                                    </div>
                                                    <div class="text-truncate">
                                                        <h6 class="font-weight-bold text-dark mb-0 small text-truncate">
                                                            Standard Baggage</h6>
                                                        <small class="text-muted text-truncate d-block"
                                                            style="font-size: 10px;">No extra allowance</small>
                                                    </div>
                                                </div>
                                            </label>
                                        </div>

                                        @if (!empty($baggage))
                                            @foreach ($baggage as $bag)
                                                @if (($bag['Code'] ?? '') !== 'NoBaggage')
                                                    <div class="col-md-4 col-sm-6 px-2 mb-2">
                                                        <label class="ssr-card-label w-100 m-0">
                                                            <input type="radio" name="travellers[0][baggage]"
                                                                value="{{ $bag['Code'] }}" class="d-none ssr-radio">
                                                            <div class="ssr-selectable-card p-2 position-relative">

                                                                <div
                                                                    class="d-flex justify-content-between align-items-center mb-1">
                                                                    <span
                                                                        class="badge px-1 py-0 font-weight-bold text-white"
                                                                        style="font-size: 11px; background-color: #aa0022; border-radius: 4px;">
                                                                        ₹{{ $bag['Price'] }}
                                                                    </span>
                                                                </div>

                                                                <div class="d-flex align-items-center">
                                                                    <div
                                                                        class="ssr-icon-circle mr-2 d-flex align-items-center justify-content-center flex-shrink-0">
                                                                        <i class="fa fa-shopping-bag"
                                                                            style="font-size: 14px; color: #aa0022;"></i>
                                                                    </div>
                                                                    <div class="text-truncate" style="flex: 1;">
                                                                        <h6
                                                                            class="font-weight-bold text-dark mb-0 small text-truncate">
                                                                            {{ !empty($bag['Text']) ? $bag['Text'] : $bag['Weight'] . ' KG Extra' }}
                                                                        </h6>
                                                                        <small
                                                                            class="text-muted font-weight-bold text-uppercase"
                                                                            style="font-size: 9px; letter-spacing: 0.3px;">
                                                                            CODE: {{ $bag['Code'] }}
                                                                        </small>
                                                                    </div>
                                                                </div>

                                                            </div>
                                                        </label>
                                                    </div>
                                                @endif
                                            @endforeach
                                        @endif

                                    </div>
                                </div>
                            </div>
                        </div>

                         
                    </div>


                    <div class="mb-5 mt-4">
                        <button type="submit" class="btn btn-brand-primary btn-lg px-5 font-weight-bold py-2.5"
                            style="border-radius: 10px; font-size: 1.05rem;">
                            Proceed to Verification <i class="fa fa-arrow-right ml-2" style="font-size: 0.9rem;"></i>
                        </button>
                    </div>
                </div>

                {{-- ================= RIGHT COLUMN SIDEBAR (FARE SUMMARY) ================= --}}
                <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                    <div class="sticky-sidebar">
                        @if (!empty($fareQuote['Response']['Results']['Fare']))
                            @php $fare = $fareQuote['Response']['Results']['Fare']; @endphp
                            <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                                <div class="card-body p-4">
                                    <h5
                                        class="card-title font-weight-bold text-dark border-bottom pb-3 mb-3 d-flex align-items-center">
                                        <i class="fa fa-calculator mr-2 text-muted" style="font-size: 1.1rem;"></i> Fare
                                        Summary
                                    </h5>

                                    <div class="d-flex justify-content-between mb-2.5">
                                        <span class="text-muted small font-weight-semibold">Base Fare</span>
                                        <span
                                            class="font-weight-bold text-dark">₹{{ number_format($fare['BaseFare']) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-2.5">
                                        <span class="text-muted small font-weight-semibold">Taxes & Fees</span>
                                        <span class="font-weight-bold text-dark">₹{{ number_format($fare['Tax']) }}</span>
                                    </div>

                                    <div class="d-flex justify-content-between mb-3">
                                        <span class="text-muted small font-weight-semibold">Convenience Surcharge</span>
                                        <span class="font-weight-bold text-dark">₹450</span>
                                    </div>

                                    <hr style="border-top: 1px dashed #e2e8f0;" class="my-3">

                                    <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-lg"
                                        style="border-radius: 10px; background-color: #f8fafc !important;">
                                        <span class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">Grand
                                            Total</span>
                                        <span class="h4 font-weight-bold text-brand mb-0" style="letter-spacing: -0.5px;">
                                            ₹{{ number_format($fare['PublishedFare'] + 450) }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                            <div class="card-body p-4">
                                <h6 class="font-weight-bold text-dark mb-3"><i class="fa fa-tags mr-1.5 text-muted"></i>
                                    Offers & Promocodes</h6>
                                <div class="input-group mb-3 border rounded-lg overflow-hidden"
                                    style="border-radius: 8px; border-color: #e2e8f0 !important;">
                                    <input type="text" class="form-control border-0 px-3"
                                        placeholder="Enter Promocode" style="box-shadow:none; height: 42px;">
                                    <div class="input-group-append">
                                        <button
                                            class="btn btn-link font-weight-bold text-brand text-decoration-none px-3 bg-light border-left"
                                            style="border-color: #e2e8f0 !important;" type="button">Apply</button>
                                    </div>
                                </div>

                                <div class="p-3 text-center rounded-lg bg-brand-light"
                                    style="border: 1.5px dashed rgba(170, 0, 34, 0.2) !important; border-radius: 10px;">
                                    <p class="small mb-2 text-dark font-weight-bold">Unlock Corporate Deals on Domestic
                                        Routes</p>
                                    <a href="#" class="btn btn-outline-brand btn-sm block font-weight-bold w-100"
                                        style="border-radius: 6px;">Login to verify exclusive deals</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script>
        function validateDOB(input) {

            const today = new Date();
            const dob = new Date(input.value);

            if (!input.value) return true;

            const pax = input.dataset.pax;

            let age = today.getFullYear() - dob.getFullYear();

            const m = today.getMonth() - dob.getMonth();

            if (m < 0 || (m === 0 && today.getDate() < dob.getDate())) {
                age--;
            }

            let message = '';

            if (pax == "1" && age < 12) {
                message = "Adult age should be greater than or equal to 12 years.";
            }

            if (pax == "2" && (age < 2 || age >= 12)) {
                message = "Child age should be between 2 and 11 years.";
            }

            if (pax == "3" && age >= 2) {
                message = "Infant age should be less than 2 years.";
            }

            if (message !== '') {
                alert(message);
                input.value = '';
                input.focus();
                return false;
            }

            return true;
        }

        // Validate immediately after selecting DOB
        document.querySelectorAll('.dob-field').forEach(function(input) {

            input.addEventListener('change', function() {
                validateDOB(this);
            });

        });

        // Validate again before submit
        document.querySelector('form').addEventListener('submit', function(e) {

            let valid = true;

            document.querySelectorAll('.dob-field').forEach(function(input) {

                if (!validateDOB(input)) {
                    valid = false;
                }

            });

            if (!valid) {
                e.preventDefault();
            }

        });
    </script>
@endsection
