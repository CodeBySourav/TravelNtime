@extends('layout_inner_page')

@section('front-content')

<div class="container py-4">

    <h2 class="mb-4">Traveller Details</h2>

    <form action="{{ route('flight.checkout') }}" method="POST">

        @csrf

        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                Lead Passenger
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-2 mb-3">
                        <label>Title</label>
                        <select name="travellers[0][title]" class="form-control" required>
                            <option value="">Select</option>
                            <option>Mr</option>
                            <option>Mrs</option>
                            <option>Ms</option>
                            <option>Miss</option>
                            <option>Master</option>
                        </select>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label>First Name (As per Passport / Aadhaar)</label>
                        <input type="text"
                               name="travellers[0][first_name]"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-5 mb-3">
                        <label>Last Name</label>
                        <input type="text"
                               name="travellers[0][last_name]"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Date of Birth</label>
                        <input type="date"
                               name="travellers[0][dob]"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Gender</label>
                        <select name="travellers[0][gender]"
                                class="form-control"
                                required>
                            <option value="">Select</option>
                            <option value="1">Male</option>
                            <option value="2">Female</option>
                        </select>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Passenger Type</label>
                        <select name="travellers[0][pax_type]"
                                class="form-control">
                            <option value="1">Adult</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Passport Number</label>
                        <input type="text"
                               name="travellers[0][passport_no]"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Passport Issue Date</label>
                        <input type="date"
                               name="travellers[0][passport_issue]"
                               class="form-control">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>Passport Expiry Date</label>
                        <input type="date"
                               name="travellers[0][passport_expiry]"
                               class="form-control">
                    </div>

                    <div class="col-md-12 mb-3">
                        <label>Address</label>
                        <input type="text"
                               name="travellers[0][address]"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>City</label>
                        <input type="text"
                               name="travellers[0][city]"
                               class="form-control">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Nationality</label>
                        <input type="text"
                               class="form-control"
                               name="travellers[0][nationality]"
                               value="IN">
                    </div>

                </div>

            </div>
        </div>

        <div class="card mb-4">

            <div class="card-header bg-success text-white">
                Contact Information
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>Email Address</label>
                        <input type="email"
                               name="email"
                               class="form-control"
                               required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Mobile Number</label>
                        <input type="text"
                               name="mobile"
                               class="form-control"
                               required>
                    </div>

                </div>

            </div>

        </div>

        <div class="card mb-4">

            <div class="card-header bg-info text-white">
                Meal Selection
            </div>

            <div class="card-body">

                <select class="form-control"
                        name="travellers[0][meal]">

                    <option value="">No Meal</option>

                    @if(!empty($ssr['MealDynamic']))
                        @foreach($ssr['MealDynamic'] as $meal)

                            <option value="{{ $meal['Code'] }}">
                                {{ $meal['AirlineDescription'] }}
                                (₹{{ $meal['Price'] }})
                            </option>

                        @endforeach
                    @endif

                </select>

            </div>

        </div>

        <div class="card mb-4">

            <div class="card-header bg-warning">
                Baggage Selection
            </div>

            <div class="card-body">

                <select class="form-control"
                        name="travellers[0][baggage]">

                    <option value="">Default</option>

                    @if(!empty($ssr['Baggage']))
                        @foreach($ssr['Baggage'] as $bag)

                            <option value="{{ $bag['Code'] }}">
                                {{ $bag['Weight'] }}
                                (₹{{ $bag['Price'] }})
                            </option>

                        @endforeach
                    @endif

                </select>

            </div>

        </div>

        <div class="card mb-4">

            <div class="card-header bg-secondary text-white">
                Seat Selection
            </div>

            <div class="card-body">

                <select class="form-control"
                        name="travellers[0][seat]">

                    <option value="">Auto Assign</option>

                    @if(!empty($ssr['SeatDynamic']))
                        @foreach($ssr['SeatDynamic'] as $segment)
                            @foreach($segment['SegmentSeat'] as $seatGroup)
                                @foreach($seatGroup['RowSeats'] as $row)
                                    @foreach($row['Seats'] as $seat)

                                        @if($seat['AvailablityType']==1)

                                            <option value="{{ $seat['Code'] }}">
                                                Seat {{ $seat['SeatNo'] }}
                                                (₹{{ $seat['Price'] }})
                                            </option>

                                        @endif

                                    @endforeach
                                @endforeach
                            @endforeach
                        @endforeach
                    @endif

                </select>

            </div>

        </div>

        <div class="card mb-4">

            <div class="card-header bg-dark text-white">
                GST Details (Optional)
            </div>

            <div class="card-body">

                <div class="row">

                    <div class="col-md-6 mb-3">
                        <label>GST Number</label>
                        <input type="text"
                               name="gst_number"
                               class="form-control">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>Company Name</label>
                        <input type="text"
                               name="gst_company_name"
                               class="form-control">
                    </div>

                </div>

            </div>

        </div>

        <button type="submit" class="btn btn-success btn-lg">
            Continue to Checkout
        </button>

    </form>

</div>

@endsection