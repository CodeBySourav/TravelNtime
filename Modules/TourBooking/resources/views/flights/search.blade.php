@extends('layout_inner_page')
@section('front-content')

<!-- Custom Flight Booking Theme Styling -->
<style>
    body {
        background-color: #f8fafc;
    }
    .paytm-card {
        border-radius: 12px;
        border: none;
        box-shadow: 0 4px 20px rgba(170, 0, 34, 0.06);
        background: #ffffff;
    }
    /* Brand Header using template color #aa0022 and white text */
    .paytm-blue-bg {
        background: linear-gradient(135deg, #aa0022 0%, #80001a 100%);
        color: #ffffff;
        padding: 20px;
        border-radius: 12px 12px 0 0;
    }
    .trip-type-btn {
        background: #f1f5f9;
        border-radius: 20px;
        padding: 5px 15px;
        font-weight: 600;
        color: #334155;
        border: 1px solid transparent;
    }
    .input-block {
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        padding: 10px 15px;
        transition: all 0.2s ease-in-out;
        background: #ffffff;
        cursor: pointer;
    }
    /* Interactive block hover state matching template profile */
    .input-block:hover {
        border-color: #aa0022;
        background-color: #fffbfb;
    }
    .input-block label {
        display: block;
        font-size: 11px;
        text-transform: uppercase;
        color: #64748b;
        margin-bottom: 2px;
        font-weight: 600;
        letter-spacing: 0.3px;
    }
    .input-block input, .input-block select {
        border: none;
        padding: 0;
        font-weight: bold;
        font-size: 18px;
        color: #0f172a;
        width: 100%;
        background: transparent;
    }
    .input-block input:focus, .input-block select:focus {
        outline: none;
        box-shadow: none;
    }
    /* Primary action button in template color #aa0022 */
    .paytm-btn {
        background-color: #aa0022;
        color: #ffffff;
        font-weight: bold;
        border-radius: 30px;
        padding: 12px 40px;
        border: none;
        font-size: 16px;
        transition: background 0.2s, transform 0.1s;
    }
    .paytm-btn:hover {
        background-color: #8a001b;
        color: #ffffff;
    }
    .paytm-btn:active {
        transform: scale(0.98);
    }
    .custom-chk .form-check-input:checked {
        background-color: #aa0022;
        border-color: #aa0022;
    }
    .custom-chk label {
        font-size: 13px;
        color: #475569;
        font-weight: 500;
        cursor: pointer;
    }

    .list-group{
    max-height:300px;
    overflow-y:auto;
    border:1px solid #ddd;
    background:#fff;
    }

    .list-group-item{
        cursor:pointer;
    }

    .list-group-item:hover{
        background:#f5f5f5;
    }
</style>

@if(session('error'))
    <div class="alert alert-danger mx-3 mt-3">
        {{ session('error') }}
    </div>
@endif

<div class="container py-4">
    <div class="card paytm-card">
        <!-- Top Branding Header -->
        <div class="paytm-blue-bg d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-weight-bold" style="letter-spacing: -0.5px;">Book Flight Tickets</h4>
            <span class="badge bg-white text-dark py-2 px-3 shadow-sm" style="border-radius:20px; font-weight: 600;">No Hidden Charges</span>
        </div>

        <div class="card-body p-4">
            <form method="POST" action="{{ route('flight.search') }}">
                @csrf

                <!-- Journey Details Row -->
                <div class="row mb-3 align-items-center">
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label for="journey_type">Trip Type</label>
                            <select name="journey_type" id="journey_type">
                                <option value="1">One Way</option>
                                <option value="2">Round Trip</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label>Class</label>
                            <select name="cabin_class">
                                <option value="1">All Class</option>
                                <option value="2" selected>Economy</option>
                                <option value="3">Premium Eco</option>
                                <option value="4">Business</option>
                                <option value="5">Prem. Business</option>
                                <option value="6">First Class</option>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Quick Count Selectors -->
                    <div class="col-md-6 mb-2">
                        <div class="input-block">
                            <label>Passengers (Adult | Child | Infant)</label>
                            <div class="d-flex align-items-center justify-content-between">
                                <select name="adult" class="me-2" style="font-size:15px;">
                                    @for($i=1;$i<=9;$i++)
                                        <option value="{{$i}}">
                                            {{$i}} Adult{{$i>1?'s':''}}
                                        </option>
                                    @endfor
                                </select>
                                <select name="child" class="me-2" style="font-size:15px;">
                                    @for($i=0;$i<=8;$i++)
                                        <option value="{{$i}}">{{$i}} Child{{$i>1?'ren':''}}</option>
                                    @endfor
                                </select>
                                <select name="infant" style="font-size:15px;">
                                    @for($i=0;$i<=8;$i++)
                                        <option value="{{$i}}">{{$i}} Infant{{$i>1?'s':''}}</option>
                                    @endfor
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- From / To / Dates Row -->
                <div class="row mb-4">
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label>From</label>
                            <div class="position-relative">
                                <input type="text"
                                    id="origin_search"
                                    class="form-control"
                                    placeholder="City or Airport">

                                <input type="hidden"
                                    name="origin"
                                    id="origin">

                                <div id="origin_list"
                                    class="list-group position-absolute w-100"
                                    style="z-index:9999;"></div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label>To</label>
                            <div class="position-relative">
                                <input type="text"
                                    id="destination_search"
                                    class="form-control"
                                    placeholder="City or Airport">

                                <input type="hidden"
                                    name="destination"
                                    id="destination">

                                <div id="destination_list"
                                    class="list-group position-absolute w-100"
                                    style="z-index:9999;"></div>
                            </div>  
                        </div>
                    </div>
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label>Departure Date</label>
                            <input type="date" name="departure_date" required style="font-size:15px;">
                        </div>
                    </div>
                    <div class="col-md-3 mb-2" id="returnBox" style="display:none;">
                        <div class="input-block">
                            <label>Return Date</label>
                            <input type="date" name="return_date" style="font-size:15px;">
                        </div>
                    </div>
                </div>

                <!-- Preferences Filters Row -->
                <div class="row align-items-center">
                    <div class="col-md-3 mb-2">
                        <div class="input-block">
                            <label>Preferred Airline</label>
                            <input type="text" name="preferred_airline" placeholder="e.g. AI" style="font-size: 15px; text-transform: uppercase;">
                        </div>
                    </div>
                    <div class="col-md-6 d-flex align-items-center mt-3 mt-md-0 custom-chk">
                        <div class="form-check me-4">
                            <input class="form-check-input" type="checkbox" name="direct_flight" value="1" id="direct_f">
                            <label class="form-check-label" for="direct_f">Non-Stop Flights</label>
                        </div>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="one_stop_flight" value="1" id="one_stop">
                            <label class="form-check-label" for="one_stop">1 Stop Flights</label>
                        </div>
                    </div>
                    <div class="col-md-3 text-md-end text-center mt-3 mt-md-0">
                        <button type="submit" class="btn paytm-btn w-100 shadow-sm">
                            Search Flights
                        </button>
                    </div>
                </div>

            </form>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Handle toggle UI behavior cleanly
    document.getElementById('journey_type').addEventListener('change', function() {
        var returnBox = document.getElementById('returnBox');
        if (this.value == '2') {
            returnBox.style.display = 'block';
        } else {
            returnBox.style.display = 'none';
        }
    });
</script>

<script>
function airportSearch(inputId, hiddenId, listId) {

    $('#' + inputId).on('keyup', function () {

        let keyword = $(this).val();

        if (keyword.length < 2) {
            $('#' + listId).html('');
            return;
        }

        $.get("{{ route('airports.search') }}", {
            q: keyword
        }, function (data) {

            let html = '';

            $.each(data, function (i, airport) {

                html += `
                    <a href="#"
                       class="list-group-item list-group-item-action airport-item"
                       data-code="${airport.airport_code}"
                       data-name="${airport.city_name} (${airport.airport_code}) - ${airport.airport_name}">
                        <strong>${airport.city_name}</strong> (${airport.airport_code})<br>
                        <small>${airport.airport_name}</small>
                    </a>
                `;

            });

            $('#' + listId).html(html);

        });

    });

    $(document).on('click', '#' + listId + ' .airport-item', function (e) {

        e.preventDefault();

        $('#' + inputId).val($(this).data('name'));

        $('#' + hiddenId).val($(this).data('code'));

        $('#' + listId).html('');

    });

}

airportSearch('origin_search', 'origin', 'origin_list');
airportSearch('destination_search', 'destination', 'destination_list');
</script>
@endsection