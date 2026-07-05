@extends('layout_inner_page')

@section('front-content')

<div class="container py-4">

    <h3 class="mb-4">Special Service Request (SSR)</h3>

    <form action="{{ route('flight.after.ssr') }}" method="POST">
        @csrf

        {{-- ================= BAGGAGE ================= --}}
        <div class="card mb-4">
            <div class="card-header bg-primary text-white">
                <strong>Select Baggage</strong>
            </div>

            <div class="card-body">

                <select class="form-control" name="baggage">
                    <option value="">No Baggage</option>

                    @foreach(($ssr['Response']['Baggage'] ?? []) as $bags)

                        @foreach($bags as $bag)

                            <option value="{{ $bag['Code'] }}">

                                {{ $bag['Weight'] }} KG

                                @if(isset($bag['Text']))
                                    ({{ $bag['Text'] }})
                                @endif

                                - ₹{{ $bag['Price'] }}

                            </option>

                        @endforeach

                    @endforeach

                </select>

            </div>
        </div>

        {{-- ================= MEALS ================= --}}
        <div class="card mb-4">

            <div class="card-header bg-success text-white">
                <strong>Select Meal</strong>
            </div>

            <div class="card-body">

                <select class="form-control" name="meal">

                    <option value="">No Meal</option>

                    @foreach(($ssr['Response']['MealDynamic'] ?? []) as $mealGroup)

                        @foreach($mealGroup as $meal)

                            <option value="{{ $meal['Code'] }}">

                                {{ $meal['AirlineDescription'] ?: 'No Meal' }}

                                - ₹{{ $meal['Price'] }}

                            </option>

                        @endforeach

                    @endforeach

                </select>

            </div>

        </div>

        {{-- ================= SEATS ================= --}}
        <div class="card mb-4">

            <div class="card-header bg-warning">
                <strong>Select Seat</strong>
            </div>

            <div class="card-body">

                <div class="row">

                    @foreach(($ssr['Response']['SeatDynamic'] ?? []) as $segment)

                        @foreach(($segment['SegmentSeat'] ?? []) as $segmentSeat)

                            @foreach(($segmentSeat['RowSeats'] ?? []) as $row)

                                @foreach(($row['Seats'] ?? []) as $seat)

                                    @if(!empty($seat['SeatNo']))

                                        <div class="col-md-2 col-3 mb-3">

                                            <label class="border rounded p-2 text-center w-100">

                                                <input
                                                    type="radio"
                                                    name="seat"
                                                    value="{{ $seat['Code'] }}">

                                                <br>

                                                <strong>

                                                    {{ $seat['RowNo'] }}{{ $seat['SeatNo'] }}

                                                </strong>

                                                <br>

                                                ₹{{ $seat['Price'] }}

                                            </label>

                                        </div>

                                    @endif

                                @endforeach

                            @endforeach

                        @endforeach

                    @endforeach

                </div>

            </div>

        </div>

        {{-- ================= SPECIAL SERVICES ================= --}}

        @if(isset($ssr['Response']['SpecialServices']))

        <div class="card mb-4">

            <div class="card-header bg-info text-white">

                <strong>Special Services</strong>

            </div>

            <div class="card-body">

                @foreach($ssr['Response']['SpecialServices'] as $services)

                    @foreach($services['SegmentSpecialService'] ?? [] as $segment)

                        @foreach($segment['SSRService'] ?? [] as $service)

                            <div class="form-check mb-2">

                                <input
                                    class="form-check-input"
                                    type="checkbox"
                                    name="services[]"
                                    value="{{ $service['SSRCode'] ?? '' }}">

                                <label class="form-check-label">

                                    {{ $service['Text'] ?? ($service['SSRCode'] ?? 'Service') }}

                                    @if(isset($service['Price']))
                                        - ₹{{ $service['Price'] }}
                                    @endif

                                </label>

                            </div>

                        @endforeach

                    @endforeach

                @endforeach

            </div>

        </div>

        @endif

        <div class="text-center">

            <button class="btn btn-lg btn-primary">

                Continue Booking

            </button>

        </div>

    </form>

</div>

@endsection