@extends('layout_inner_page')

@section('front-content')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/all.min.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
    .flight-timeline-line::before { left: 0; }
    .flight-timeline-line::after { right: 0; }

    .airline-logo-container {
        width: 52px;
        height: 52px;
        background: #fff;
        border: 1px solid #edf2f7;
        box-shadow: 0 2px 6px rgba(0,0,0,0.04);
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
    .brand-checkbox .custom-control-input:checked ~ .custom-control-label::before {
        background-color: var(--brand-primary) !important;
        border-color: var(--brand-primary) !important;
    }
</style>

<div class="container py-4" style="background-color: #fcfcfc;">
    
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb bg-transparent p-0 mb-0" style="font-size: 0.85rem;">
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Home</a></li>
            <li class="breadcrumb-item"><a href="#" class="text-muted text-decoration-none">Flights</a></li>
            @if(!empty($fareQuote['Response']['Results']['Segments'][0][0]))
                @php 
                    $firstSeg = $fareQuote['Response']['Results']['Segments'][0][0];
                    $lastSeg = end($fareQuote['Response']['Results']['Segments'][0]);
                @endphp
                <li class="breadcrumb-item"><a href="#" class="text-brand font-weight-bold text-decoration-none">{{ $firstSeg['Origin']['Airport']['CityName'] }} → {{ $lastSeg['Destination']['Airport']['CityName'] }}</a></li>
            @endif
            <li class="breadcrumb-item active text-muted" aria-current="page">Review Itinerary</li>
        </ol>
    </nav>

    <form action="{{ route('flight.checkout') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-lg-8 col-md-12">
                <h3 class="mb-4 font-weight-bold text-dark d-flex align-items-center">
                    <span class="bg-brand-light text-brand rounded p-2 mr-3 d-inline-flex align-items-center justify-content-center" style="width: 42px; height: 42px;">
                        <i class="fa fa-ticket"></i>
                    </span>
                    Review Your Itinerary
                </h3>

                {{-- ================= FLIGHT CARD SEGMENT SYSTEM ================= --}}
                @if(!empty($fareQuote['Response']['Results']['Segments'][0]))
                    @foreach($fareQuote['Response']['Results']['Segments'][0] as $segment)
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                            <div class="card-body p-4">
                                <div class="row align-items-center">
                                    
                                    <div class="col-md-3 mb-3 mb-md-0">
                                        <div class="d-flex align-items-center">
                                            @php
                                            $airlineCode = strtoupper($segment['Airline']['AirlineCode']);
                                            @endphp

                                            <div class="airline-logo-container mr-3">
                                                <img
                                                    src="https://images.kiwi.com/airlines/64/{{ $airlineCode }}.png"
                                                    alt="{{ $segment['Airline']['AirlineName'] }}"
                                                    style="width:55px;height:55px;object-fit:contain;"
                                                    onerror="this.onerror=null;this.src='{{ asset('images/airline.png') }}';">
                                            </div>
                                            <div>
                                                <h6 class="mb-0 font-weight-bold text-dark" style="font-size: 0.95rem;">{{ $segment['Airline']['AirlineName'] }}</h6>
                                                <small class="text-muted font-weight-semibold">{{ $segment['Airline']['AirlineCode'] }}-{{ $segment['Airline']['FlightNumber'] }}</small>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="col-md-3 col-5 text-left">
                                        <h5 class="mb-0 font-weight-bold text-dark">{{ $segment['Origin']['Airport']['CityName'] }}</h5>
                                        <h3 class="my-1 font-weight-bold text-brand" style="letter-spacing: -0.5px;">{{ date('H:i', strtotime($segment['Origin']['DepTime'])) }}</h3>
                                        <p class="mb-0 text-dark small font-weight-bold">{{ date('D, d M', strtotime($segment['Origin']['DepTime'])) }}</p>
                                        <small class="text-muted d-block text-truncate" title="{{ $segment['Origin']['Airport']['AirportName'] }}">{{ $segment['Origin']['Airport']['AirportName'] }}</small>
                                    </div>
                                    
                                    <div class="col-md-3 col-2 text-center px-1">
                                        <small class="text-muted font-weight-bold d-block mb-1">{{ floor($segment['Duration']/60) }}h {{ $segment['Duration'] % 60 }}m</small>
                                        <div class="flight-timeline-line my-2">
                                            <span class="position-absolute text-brand bg-white px-2" style="top: -10px; left: 35%; font-size: 0.9rem;"><i class="fa fa-plane"></i></span>
                                        </div>
                                        <span class="badge badge-light border text-muted font-weight-bold py-1 px-2" style="font-size:0.7rem; border-radius: 4px;">NON-STOP</span>
                                    </div>
                                    
                                    <div class="col-md-3 col-5 text-right">
                                        <h5 class="mb-0 font-weight-bold text-dark">{{ $segment['Destination']['Airport']['CityName'] }}</h5>
                                        <h3 class="my-1 font-weight-bold text-brand" style="letter-spacing: -0.5px;">{{ date('H:i', strtotime($segment['Destination']['ArrTime'])) }}</h3>
                                        <p class="mb-0 text-dark small font-weight-bold">{{ date('D, d M', strtotime($segment['Destination']['ArrTime'])) }}</p>
                                        <small class="text-muted d-block text-truncate" title="{{ $segment['Destination']['Airport']['AirportName'] }}">{{ $segment['Destination']['Airport']['AirportName'] }}</small>
                                    </div>
                                </div>
                            </div>
                            <div class="card-footer border-0 d-flex flex-wrap justify-content-between align-items-center px-4 py-3" style="border-bottom-left-radius: 16px; border-bottom-right-radius: 16px; background-color: #fdfdfd; border-top: 1px solid #f3f3f3 !important;">
                                <span class="small text-muted font-weight-semibold">
                                    <i class="fa fa-briefcase mr-1 text-brand"></i> Baggage Allowances: Check-in <strong class="text-dark">{{ $segment['Baggage'] ?? '15 KG' }}</strong> • Cabin <strong class="text-dark">{{ $segment['CabinBaggage'] ?? '7 KG' }}</strong>
                                </span>
                                <a href="#" class="small font-weight-bold text-brand text-decoration-none"><i class="fa fa-info-circle mr-1"></i> Policy Rules</a>
                            </div>
                        </div>
                    @endforeach
                @endif

                {{-- ================= RESTRICTION FARE CONDITIONS NOTE ================= --}}
                <div class="card border-0 mb-4 shadow-sm" style="border-radius: 16px; border-left: 5px solid var(--brand-primary) !important; background: #ffffff;">
                    <div class="card-body p-4">
                        <h5 class="text-brand font-weight-bold mb-3 d-flex align-items-center">
                            <i class="fa fa-exclamation-triangle mr-2"></i> Special Category Group Fare Conditions
                        </h5>
                        <ul class="small text-muted pl-3 mb-4" style="line-height: 1.75; list-style-type: square;">
                            <li class="mb-2"><strong class="text-dark">Non-Refundable & Non-Changeable Fare:</strong> Cancellation, date modification, or routing re-issuance is strictly prohibited post-booking confirmations.</li>
                            <li class="mb-2">Passenger data integration populates on individual airline system nodes roughly <span class="text-brand font-weight-bold">12–24 hours before flight departure.</span></li>
                            <li class="mb-2">Mandatory check-in execution timelines open explicitly <span class="text-brand font-weight-bold">one day prior starting 8:00 PM onwards.</span></li>
                            <li>Dynamic meals, extra luggage weights, or seat charts can be purchased manually directly from the operating carrier during web check-in windows.</li>
                        </ul>
                        <hr style="border-top: 1px solid #f1f5f9;">
                        <div class="custom-control custom-checkbox brand-checkbox mt-2">
                            <input type="checkbox" class="custom-control-input" id="termsCheck" required>
                            <label class="custom-control-label small font-weight-bold text-dark" style="cursor: pointer;" for="termsCheck">I declare that I have carefully verified and understood the flight specific fare rules mentioned above.</label>
                        </div>
                    </div>
                </div>

                {{-- ================= PASSENGER MANAGEMENT HEADER ================= --}}
                <div class="d-flex flex-wrap justify-content-between align-items-center mb-3 mt-4 gap-2">
                    <h5 class="mb-0 font-weight-bold text-dark"><i class="fa fa-users mr-2 text-muted"></i>Passenger Details <span class="text-muted small font-weight-normal">(0/1 Configured)</span></h5>
                    <button type="button" class="btn btn-outline-brand btn-sm rounded-pill font-weight-bold px-3"><i class="fa fa-plus-circle mr-1"></i> Add New Traveller</button>
                </div>

                {{-- ================= PASSENGER REGISTRATION FORM CARD ================= --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; overflow: hidden; background: #ffffff;">
                    <div class="p-3 border-bottom d-flex flex-wrap justify-content-between align-items-center bg-brand-light" style="font-size: 0.85rem; border-color: rgba(170, 0, 34, 0.08) !important;">
                        <span class="font-weight-semibold text-dark mb-2 mb-md-0"><i class="fa fa-user-circle mr-1 text-brand"></i> Sign-in to your profile to fetch saved traveler parameters automatically.</span>
                        <a href="#" class="btn btn-brand-primary btn-sm px-3 font-weight-bold" style="border-radius: 6px;">Login Now</a>
                    </div>
                    
                    <div class="card-body p-4">
                        <h6 class="font-weight-bold text-dark border-bottom pb-2 mb-4 d-flex align-items-center">
                            <span class="badge btn-brand-primary mr-2 px-2.5 py-1" style="font-size: 0.75rem; border-radius: 4px;">Primary</span> Adult 1
                        </h6>
                        <div class="row">
                            <div class="col-md-2 mb-3">
                                <label class="small font-weight-bold text-muted">Title</label>
                                <select name="travellers[0][title]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" required>
                                    <option value="">Select</option>
                                    <option>Mr</option>
                                    <option>Mrs</option>
                                    <option>Ms</option>
                                    <option>Miss</option>
                                </select>
                            </div>

                            <div class="col-md-5 mb-3">
                                <label class="small font-weight-bold text-muted">First Name (Identical to Govt ID)</label>
                                <input type="text" name="travellers[0][first_name]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" placeholder="First & Middle Name" required>
                            </div>

                            <div class="col-md-5 mb-3">
                                <label class="small font-weight-bold text-muted">Last Name</label>
                                <input type="text" name="travellers[0][last_name]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" placeholder="Last Name" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted">Date of Birth</label>
                                <input type="date" name="travellers[0][dob]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" required>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted">Gender</label>
                                <select name="travellers[0][gender]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" required>
                                    <option value="">Select</option>
                                    <option value="1">Male</option>
                                    <option value="2">Female</option>
                                </select>
                            </div>

                            <div class="col-md-4 mb-3">
                                <label class="small font-weight-bold text-muted">Passenger Type</label>
                                <select name="travellers[0][pax_type]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" readonly>
                                    <option value="1">Adult</option>
                                </select>
                            </div>

                            <div class="col-md-8 mb-3">
                                <label class="small font-weight-bold text-muted">Address</label>
                                <input type="text" name="travellers[0][address]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" placeholder="Street Address">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="small font-weight-bold text-muted">City</label>
                                <input type="text" name="travellers[0][city]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" placeholder="City">
                            </div>

                            <div class="col-md-2 mb-3">
                                <label class="small font-weight-bold text-muted">Nationality</label>
                                <input type="text" name="travellers[0][nationality]" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" value="IN">
                            </div>
                        </div>

                        <div class="mt-3">
                            <a class="text-decoration-none small font-weight-bold text-brand" data-toggle="collapse" href="#optionalFields" role="button" aria-expanded="false">
                                <i class="fa fa-plus-square mr-1"></i> Add Passport & Document Details (Optional International Segment Tracking)
                            </a>
                            <div class="collapse mt-3" id="optionalFields">
                                <div class="row p-3 bg-light rounded-lg" style="border-radius: 8px;">
                                    <div class="col-md-6 mb-3">
                                        <label class="small font-weight-bold text-muted">Passport Number</label>
                                        <input type="text" name="travellers[0][passport_no]" class="form-control bg-white border-light shadow-xs" style="height: 42px;">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Issue Date</label>
                                        <input type="date" name="travellers[0][passport_issue]" class="form-control bg-white border-light shadow-xs" style="height: 42px;">
                                    </div>
                                    <div class="col-md-3 mb-3">
                                        <label class="small font-weight-bold text-muted">Expiry Date</label>
                                        <input type="date" name="travellers[0][passport_expiry]" class="form-control bg-white border-light shadow-xs" style="height: 42px;">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= CONTACT INFORMATION PROFILE ================= --}}
                <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                    <div class="card-body p-4">
                        <h5 class="font-weight-bold text-dark mb-2 d-flex align-items-center">
                            <i class="fa fa-envelope-open-o mr-2 text-muted"></i> Contact Information
                        </h5>
                        <p class="small text-muted mb-4">Your electronic tickets, confirmation receipts, and terminal delay notices will be communicated here.</p>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted">Mobile Number *</label>
                                <div class="input-group shadow-xs">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text font-weight-bold bg-light text-dark" style="border-top-left-radius: 8px; border-bottom-left-radius: 8px; border-color: #e2e8f0;">+91</span>
                                    </div>
                                    <input type="text" name="mobile" class="form-control border-light" style="border-top-right-radius: 8px; border-bottom-right-radius: 8px; height: 42px;" placeholder="Mobile Number" required>
                                </div>
                            </div>

                            <div class="col-md-6 mb-3">
                                <label class="small font-weight-bold text-muted">Email ID Address *</label>
                                <input type="email" name="email" class="form-control border-light shadow-xs" style="border-radius: 8px; height: 42px;" placeholder="Email Address" required>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ================= OPTIONAL ACCORDION SERVICES SELECTION ================= --}}
                <div class="accordion mb-4" id="ssrAccordion">
                    <div class="card border-0 shadow-sm mb-2" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
                        <div class="card-header bg-white p-3 border-0" id="headingMeal">
                            <button class="btn btn-block text-left font-weight-bold text-dark p-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseMeal">
                                <span><i class="fa fa-cutlery text-brand mr-2"></i> In-Flight Meal Selections (Optional)</span>
                                <i class="fa fa-chevron-down small text-muted"></i>
                            </button>
                        </div>
                        <div id="collapseMeal" class="collapse" data-parent="#ssrAccordion">
                            <div class="card-body pt-1 pb-4 px-4">
                                <select class="form-control border-light" name="travellers[0][meal]" style="border-radius: 8px; height: 44px;">
                                    <option value="">No Meal Selected</option>
                                    @if(!empty($ssr['MealDynamic']))
                                        @foreach($ssr['MealDynamic'] as $meal)
                                            <option value="{{ $meal['Code'] }}">{{ $meal['AirlineDescription'] }} (+₹{{ $meal['Price'] }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="card border-0 shadow-sm mb-4" style="border-radius: 12px; overflow: hidden; background: #ffffff;">
                        <div class="card-header bg-white p-3 border-0" id="headingBag">
                            <button class="btn btn-block text-left font-weight-bold text-dark p-0 d-flex justify-content-between align-items-center" type="button" data-toggle="collapse" data-target="#collapseBag">
                                <span><i class="fa fa-plus-square text-brand mr-2"></i> Additional Baggage Allowance (Optional)</span>
                                <i class="fa fa-chevron-down small text-muted"></i>
                            </button>
                        </div>
                        <div id="collapseBag" class="collapse" data-parent="#ssrAccordion">
                            <div class="card-body pt-1 pb-4 px-4">
                                <select class="form-control border-light" name="travellers[0][baggage]" style="border-radius: 8px; height: 44px;">
                                    <option value="">Default Included Baggage Only</option>
                                    @if(!empty($ssr['Baggage']))
                                        @foreach($ssr['Baggage'] as $bag)
                                            <option value="{{ $bag['Code'] }}">{{ $bag['Weight'] }} KG (+₹{{ $bag['Price'] }})</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mb-5 mt-4">
                    <button type="submit" class="btn btn-brand-primary btn-lg px-5 font-weight-bold py-2.5" style="border-radius: 10px; font-size: 1.05rem;">
                        Proceed to Verification <i class="fa fa-arrow-right ml-2" style="font-size: 0.9rem;"></i>
                    </button>
                </div>
            </div>

            {{-- ================= RIGHT COLUMN SIDEBAR (FARE SUMMARY) ================= --}}
            <div class="col-lg-4 col-md-12 mt-4 mt-lg-0">
                <div class="sticky-sidebar">
                    @if(!empty($fareQuote['Response']['Results']['Fare']))
                        @php $fare = $fareQuote['Response']['Results']['Fare']; @endphp
                        <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                            <div class="card-body p-4">
                                <h5 class="card-title font-weight-bold text-dark border-bottom pb-3 mb-3 d-flex align-items-center">
                                    <i class="fa fa-calculator mr-2 text-muted" style="font-size: 1.1rem;"></i> Fare Summary
                                </h5>
                                
                                <div class="d-flex justify-content-between mb-2.5">
                                    <span class="text-muted small font-weight-semibold">Base Fare</span>
                                    <span class="font-weight-bold text-dark">₹{{ number_format($fare['BaseFare']) }}</span>
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
                                
                                <div class="d-flex justify-content-between align-items-center bg-light p-3 rounded-lg" style="border-radius: 10px; background-color: #f8fafc !important;">
                                    <span class="font-weight-bold text-dark mb-0" style="font-size: 0.95rem;">Grand Total</span>
                                    <span class="h4 font-weight-bold text-brand mb-0" style="letter-spacing: -0.5px;">
                                        ₹{{ number_format($fare['PublishedFare'] + 450) }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    @endif

                    <div class="card shadow-sm border-0 mb-4" style="border-radius: 16px; background: #ffffff;">
                        <div class="card-body p-4">
                            <h6 class="font-weight-bold text-dark mb-3"><i class="fa fa-tags mr-1.5 text-muted"></i> Offers & Promocodes</h6>
                            <div class="input-group mb-3 border rounded-lg overflow-hidden" style="border-radius: 8px; border-color: #e2e8f0 !important;">
                                <input type="text" class="form-control border-0 px-3" placeholder="Enter Promocode" style="box-shadow:none; height: 42px;">
                                <div class="input-group-append">
                                    <button class="btn btn-link font-weight-bold text-brand text-decoration-none px-3 bg-light border-left" style="border-color: #e2e8f0 !important;" type="button">Apply</button>
                                </div>
                            </div>
                            
                            <div class="p-3 text-center rounded-lg bg-brand-light" style="border: 1.5px dashed rgba(170, 0, 34, 0.2) !important; border-radius: 10px;">
                                <p class="small mb-2 text-dark font-weight-bold">Unlock Corporate Deals on Domestic Routes</p>
                                <a href="#" class="btn btn-outline-brand btn-sm block font-weight-bold w-100" style="border-radius: 6px;">Login to verify exclusive deals</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
        </div>
    </form>
</div>
@endsection