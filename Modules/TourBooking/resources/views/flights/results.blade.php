@extends('layout_inner_page')
@section('front-content')

<!-- Custom image_3924c2.png Style Overrides -->
<style>
    body {
        background-color: #f7f9fb !important;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    
    /* Top Search Strip Card Container */
    .search-panel-card {
        background: #ffffff;
        border-radius: 14px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.04);
        border: 1px solid #eef1f5;
        padding: 24px;
        margin-bottom: 24px;
    }

    /* Input Field Label Formatting */
    .field-group {
        margin-bottom: 15px;
    }
    .field-group label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #555c65;
        text-transform: uppercase;
        display: flex;
        align-items: center;
        margin-bottom: 6px;
    }
    .field-group label i, .field-group label svg {
        margin-right: 6px;
        color: #a10024;
    }

    /* Custom Form Control Formatting */
    .form-box-input {
        background: #ffffff;
        border: 1px solid #ced4da;
        border-radius: 6px;
        padding: 10px 14px;
        font-size: 14px;
        font-weight: 600;
        color: #1a1a1a;
        width: 100%;
        height: 45px;
        transition: border-color 0.2s;
    }
    .form-box-input:focus {
        border-color: #a10024;
        outline: none;
        box-shadow: none;
    }

    /* Maroon Button Accent styles */
    .btn-maroon-search {
        background-color: #a10024;
        color: #ffffff;
        font-weight: 600;
        font-size: 15px;
        border-radius: 6px;
        height: 45px;
        border: none;
        width: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background 0.2s;
    }
    .btn-maroon-search:hover {
        background-color: #80001c;
        color: #ffffff;
    }

    /* Available Flights Header Summary Ribbon */
    .results-count-ribbon {
        background: #ffffff;
        border-radius: 10px;
        border: 1px solid #eef1f5;
        padding: 15px 20px;
        margin-bottom: 20px;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }
    .results-count-badge {
        border: 1px solid #ced4da;
        background: #fff;
        padding: 6px 14px;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 700;
        color: #333;
    }

    /* Flight Offer Presentation Cards */
    .flight-offer-card {
        background: #ffffff;
        border-radius: 12px;
        border: 1px solid #eef1f5;
        padding: 24px;
        margin-bottom: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,0.02);
    }
    .airline-brand-box {
        display: flex;
        align-items: center;
    }
    .airline-logo-square {
        width: 48px;
        height: 48px;
        background-color: #fff5f6;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 15px;
        border: 1px solid #ffe3e6;
    }
    .airline-title {
        font-size: 15px;
        font-weight: 700;
        color: #1e2631;
    }
    .airline-subtitle {
        font-size: 12px;
        color: #707a8a;
    }

    /* Mid-Timeline Chart Path Vector */
    .timeline-path-box {
        text-align: center;
        position: relative;
        padding-top: 5px;
    }
    .duration-lbl {
        font-size: 12px;
        font-weight: 700;
        color: #a10024;
        margin-bottom: 2px;
    }
    .vector-line-marker {
        height: 2px;
        background: #cbd3dc;
        width: 100%;
        display: block;
        position: relative;
        margin: 8px 0;
    }
    .vector-line-marker::after {
        content: '✈';
        position: absolute;
        top: -9px;
        left: 50%;
        transform: translateX(-50%);
        color: #a10024;
        background: #fff;
        padding: 0 4px;
        font-size: 12px;
    }
    .stops-lbl {
        font-size: 11px;
        font-weight: 700;
        color: #ffb800;
    }

    /* Timings & Station display styles */
    .time-stamp {
        font-size: 18px;
        font-weight: 700;
        color: #1e2631;
    }
    .airport-label {
        font-size: 13px;
        font-weight: 600;
        color: #555c65;
    }
    .city-label {
        font-size: 12px;
        color: #707a8a;
    }

    /* Side Price Checkout Block styles */
    .price-checkout-pane {
        border-left: 1px dashed #e5ebf1;
        padding-left: 20px;
        text-align: right;
    }
    .total-fare-lbl {
        font-size: 11px;
        color: #707a8a;
        margin-bottom: 2px;
    }
    .amount-tag {
        font-size: 24px;
        font-weight: 700;
        color: #a10024;
    }
    .refundable-badge {
        background-color: #e2f6ed;
        color: #24b273;
        font-size: 11px;
        font-weight: 600;
        padding: 2px 8px;
        border-radius: 4px;
        display: inline-block;
        margin-bottom: 12px;
    }
    .btn-maroon-book {
        background-color: #a10024;
        color: #ffffff;
        font-weight: 700;
        font-size: 13px;
        border-radius: 30px;
        padding: 10px 24px;
        border: none;
        transition: background 0.2s;
    }
    .btn-maroon-book:hover {
        background-color: #80001c;
        color: #ffffff;
    }
</style>

<div class="container py-4">

    <!-- 1. Top Integrated Live Active Search Form Panel Strip -->
    <form method="POST" action="{{ route('flight.search') }}" class="search-panel-card">
        @csrf
        <div class="row">
            <!-- From Location Code Input -->
            <div class="col-md-3 field-group">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    From
                </label>
                <input type="text" class="form-box-input" name="origin" value="{{ strtoupper($search['origin'] ?? 'DEL') }}" required style="text-transform: uppercase;">
            </div>

            <!-- Destination Location Code Input -->
            <div class="col-md-3 field-group">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0 1 18 0z"></path><circle cx="12" cy="10" r="3"></circle></svg>
                    To
                </label>
                <input type="text" class="form-box-input" name="destination" value="{{ strtoupper($search['destination'] ?? 'BOM') }}" required style="text-transform: uppercase;">
            </div>

            <!-- Outbound Departure Date Input -->
            <div class="col-md-2 field-group">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Departure
                </label>
                <input type="date" class="form-box-input" name="departure_date" value="{{ $search['departure_date'] ?? '' }}" required>
            </div>

            <!-- Route Configuration Selector Mapping Options -->
            <div class="col-md-2 field-group">
                <label>Journey</label>
                <select class="form-box-input" name="journey_type" id="journey_type">
                    <option value="1" {{ ($search['journey_type'] ?? '1') == '1' ? 'selected' : '' }}>One Way</option>
                    <option value="2" {{ ($search['journey_type'] ?? '1') == '2' ? 'selected' : '' }}>Round Trip</option>
                </select>
            </div>

            <!-- Return Date Input Element Component -->
            <div class="col-md-2 field-group">
                <label>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                    Return
                </label>
                <input type="date" class="form-box-input" name="return_date" value="{{ $search['return_date'] ?? '' }}">
            </div>
        </div>

        <div class="row mt-2">
            <!-- Adults Selector Dropdown Box -->
            <div class="col-md-2 field-group">
                <label>Adults</label>
                <select class="form-box-input" name="adult">
                    @for($i=1;$i<=9;$i++)
                        <option value="{{$i}}" {{ ($search['adult'] ?? 1) == $i ? 'selected':'' }}>{{$i}}</option>
                    @endfor
                </select>
            </div>

            <!-- Children Selector Dropdown Box -->
            <div class="col-md-2 field-group">
                <label>Children</label>
                <select class="form-box-input" name="child">
                    @for($i=0;$i<=8;$i++)
                        <option value="{{$i}}" {{ ($search['child'] ?? 0) == $i ? 'selected':'' }}>{{$i}}</option>
                    @endfor
                </select>
            </div>

            <!-- Infants Selector Dropdown Box -->
            <div class="col-md-2 field-group">
                <label>Infants</label>
                <select class="form-box-input" name="infant">
                    @for($i=0;$i<=8;$i++)
                        <option value="{{$i}}" {{ ($search['infant'] ?? 0) == $i ? 'selected':'' }}>{{$i}}</option>
                    @endfor
                </select>
            </div>

            <!-- Seating Travel Cabin Type Configuration Block -->
            <div class="col-md-3 field-group">
                <label>Cabin</label>
                <select class="form-box-input" name="cabin_class">
                    <option value="1" {{ ($search['cabin_class'] ?? '2') == '1' ? 'selected' : '' }}>All</option>
                    <option value="2" {{ ($search['cabin_class'] ?? '2') == '2' ? 'selected' : '' }}>Economy</option>
                    <option value="3" {{ ($search['cabin_class'] ?? '2') == '3' ? 'selected' : '' }}>Premium Economy</option>
                    <option value="4" {{ ($search['cabin_class'] ?? '2') == '4' ? 'selected' : '' }}>Business</option>
                    <option value="6" {{ ($search['cabin_class'] ?? '2') == '6' ? 'selected' : '' }}>First</option>
                </select>
            </div>

            <!-- Modify Search Confirmation Action Button Context -->
            <div class="col-md-3 field-group d-flex align-items-end">
                <button type="submit" class="btn btn-maroon-search">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" class="mr-2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    Search
                </button>
            </div>
        </div>
    </form>

    <!-- 2. Section Heading Summary Panel Metric Counter Header Strip -->
    <div class="results-count-ribbon shadow-sm">
        <div>
            <h4 class="mb-1 font-weight-bold text-dark" style="font-size: 18px;">Available Flights</h4>
            <span class="text-muted small">Trace ID: <b class="text-danger">05fb911a-ec38-4a44-83b9-fd341abf01c9</b></span>
        </div>
        <div>
            <div class="results-count-badge">
                ✈ &nbsp;{{ count($results) }} Results Found
            </div>
        </div>
    </div>

    <!-- 3. Dynamic Results Rendering Loop Blocks -->
    @foreach($results as $group)
    @foreach($group as $flight)
        @php
            $segment = $flight['Segments'][0][0];
        @endphp

        <div class="flight-offer-card shadow-sm">
            <div class="row align-items-center">
                
                <div class="col-md-3">
                    <div class="airline-brand-box">
                        <div class="airline-logo-square">
    <img
        src="https://images.kiwi.com/airlines/64/{{ strtoupper($segment['Airline']['AirlineCode']) }}.png"
        alt="{{ $segment['Airline']['AirlineName'] }}"
        style="width:50px;height:50px;object-fit:contain;"
        onerror="this.src='{{ asset('images/airline.png') }}'"
    >
</div>
                        <div>
                            <div class="airline-title">{{ $segment['Airline']['AirlineName'] }}</div>
                            <div class="airline-subtitle">{{ $segment['Airline']['AirlineCode'] }}-{{ $segment['Airline']['FlightNumber'] }}</div>
                        </div>
                    </div>
                </div>

                <div class="col-md-2 text-right text-md-left">
                    <div class="time-stamp">{{ date('H:i', strtotime($segment['Origin']['DepTime'])) }}</div>
                    <div class="airport-label">{{ $segment['Origin']['Airport']['AirportCode'] }}</div>
                    <div class="city-label">{{ $segment['Origin']['Airport']['CityName'] ?? $segment['Origin']['Airport']['AirportName'] ?? 'Origin' }}</div>
                </div>

                <div class="col-md-3">
                    <div class="timeline-path-box">
                        <div class="duration-lbl">
                            @if(isset($flight['Segments'][0]))
                                @php 
                                    // Basic duration calculator helper if API doesn't pass it clean
                                    $minutes = $segment['Duration'] ?? 0;
                                    $hours = floor($minutes / 60);
                                    $remainingMinutes = $minutes % 60;
                                @endphp
                                {{ $hours > 0 ? $hours.'h ' : '' }}{{ $remainingMinutes }}m
                            @endif
                        </div>
                        <span class="vector-line-marker"></span>
                        <div class="stops-lbl">
                            {{ count($flight['Segments'][0]) > 1 ? (count($flight['Segments'][0]) - 1) . ' Stop' : 'Non-Stop' }}
                        </div>
                    </div>
                </div>

                <div class="col-md-2">
                    <div class="time-stamp">{{ date('H:i', strtotime($segment['Destination']['ArrTime'])) }}</div>
                    <div class="airport-label">{{ $segment['Destination']['Airport']['AirportCode'] }}</div>
                    <div class="city-label">{{ $segment['Destination']['Airport']['CityName'] ?? $segment['Destination']['Airport']['AirportName'] ?? 'Destination' }}</div>
                </div>

                <div class="col-md-2 price-checkout-pane">
                    <div class="total-fare-lbl">Total Fare</div>
                    <div class="amount-tag mb-1">₹{{ number_format($flight['Fare']['PublishedFare']) }}</div>
                    <div><span class="refundable-badge">Refundable</span></div>
                    
                    <form method="POST" action="{{ route('flight.fareQuote') }}">
                        @csrf
                        <input type="hidden" name="result_index" value="{{ $flight['ResultIndex'] }}">
                        <button type="submit" class="btn btn-maroon-book w-100">
                            Book Now &nbsp;➔
                        </button>
                    </form>
                </div>

            </div>
        </div>
    @endforeach
@endforeach

</div>
@endsection