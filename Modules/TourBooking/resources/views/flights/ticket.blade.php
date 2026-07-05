@extends('layout_inner_page')

@section('front-content')

<div class="container py-4">

    @php
        $response = $ticket['Response'] ?? [];
        $data = $response['Response'] ?? [];
        $itinerary = $data['FlightItinerary'] ?? [];
    @endphp

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h4 class="mb-0">
                🎫 Ticket Confirmed
            </h4>
        </div>

        <div class="card-body">

            {{-- Status --}}
            <div class="alert alert-info">
                <strong>Status :</strong>

                @if(($data['TicketStatus'] ?? 0) == 1)
                    Confirmed
                @else
                    Pending
                @endif
            </div>

            {{-- PNR / Booking --}}
            <div class="row mb-4">

                <div class="col-md-6">
                    <h5>PNR</h5>
                    <h4 class="text-primary">
                        {{ $data['PNR'] ?? 'N/A' }}
                    </h4>
                </div>

                <div class="col-md-6">
                    <h5>Booking ID</h5>
                    <h4>
                        {{ $data['BookingId'] ?? 'N/A' }}
                    </h4>
                </div>

            </div>

            {{-- Flight --}}
            <div class="card mb-4">

                <div class="card-header">
                    ✈ Flight Details
                </div>

                <div class="card-body">

                    <div class="row">

                        <div class="col-md-4">
                            <strong>Route</strong><br>

                            {{ $itinerary['Origin'] ?? '' }}
                            →
                            {{ $itinerary['Destination'] ?? '' }}
                        </div>

                        <div class="col-md-4">
                            <strong>Airline</strong><br>

                            {{ $itinerary['AirlineCode'] ?? '' }}
                            -
                            {{ $itinerary['ValidatingAirlineCode'] ?? '' }}
                        </div>

                        <div class="col-md-4">
                            <strong>Fare Type</strong><br>

                            {{ $itinerary['FareType'] ?? '' }}
                        </div>

                    </div>

                </div>

            </div>

            {{-- Passenger --}}
            <div class="card mb-4">

                <div class="card-header">
                    👤 Passenger Details
                </div>

                <div class="card-body">

                    @foreach($itinerary['Passenger'] ?? [] as $pax)

                        <div class="border rounded p-3 mb-3">

                            <h5>
                                {{ $pax['Title'] ?? '' }}
                                {{ $pax['FirstName'] ?? '' }}
                                {{ $pax['LastName'] ?? '' }}
                            </h5>

                            <div class="row">

                                <div class="col-md-6">
                                    <strong>Email</strong><br>
                                    {{ $pax['Email'] ?? '' }}
                                </div>

                                <div class="col-md-6">
                                    <strong>Mobile</strong><br>
                                    {{ $pax['ContactNo'] ?? '' }}
                                </div>

                            </div>

                            <hr>

                            <div class="row">

                                <div class="col-md-4">
                                    <strong>Ticket Number</strong><br>

                                    {{ $pax['Ticket']['TicketNumber'] ?? '' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Status</strong><br>

                                    {{ $pax['Ticket']['Status'] ?? '' }}
                                </div>

                                <div class="col-md-4">
                                    <strong>Issue Date</strong><br>

                                    {{ $pax['Ticket']['IssueDate'] ?? '' }}
                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Segments --}}
            <div class="card mb-4">

                <div class="card-header">
                    🛫 Flight Segments
                </div>

                <div class="card-body">

                    @foreach($itinerary['Segments'] ?? [] as $segment)

                        <div class="border rounded p-3 mb-3">

                            <div class="row">

                                <div class="col-md-3">

                                    <strong>Flight</strong><br>

                                    {{ $segment['Airline']['AirlineName'] ?? '' }}

                                    <br>

                                    {{ $segment['Airline']['AirlineCode'] ?? '' }}
                                    {{ $segment['Airline']['FlightNumber'] ?? '' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>From</strong><br>

                                    {{ $segment['Origin']['Airport']['CityName'] ?? '' }}

                                    ({{ $segment['Origin']['Airport']['AirportCode'] ?? '' }})

                                    <br>

                                    {{ $segment['Origin']['DepTime'] ?? '' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>To</strong><br>

                                    {{ $segment['Destination']['Airport']['CityName'] ?? '' }}

                                    ({{ $segment['Destination']['Airport']['AirportCode'] ?? '' }})

                                    <br>

                                    {{ $segment['Destination']['ArrTime'] ?? '' }}

                                </div>

                                <div class="col-md-3">

                                    <strong>Status</strong><br>

                                    {{ $segment['Status'] ?? '' }}

                                    <br>

                                    <strong>Baggage</strong>

                                    {{ $segment['Baggage'] ?? '' }}

                                </div>

                            </div>

                        </div>

                    @endforeach

                </div>

            </div>

            {{-- Fare --}}
            @if(isset($itinerary['Fare']))
            <div class="card">

                <div class="card-header">
                    💰 Fare Details
                </div>

                <div class="card-body">

                    <table class="table table-bordered">

                        <tr>
                            <th>Base Fare</th>
                            <td>₹ {{ number_format($itinerary['Fare']['BaseFare'] ?? 0,2) }}</td>
                        </tr>

                        <tr>
                            <th>Tax</th>
                            <td>₹ {{ number_format($itinerary['Fare']['Tax'] ?? 0,2) }}</td>
                        </tr>

                        <tr>
                            <th>Total Fare</th>
                            <td>
                                <strong class="text-success">
                                    ₹ {{ number_format($itinerary['Fare']['PublishedFare'] ?? 0,2) }}
                                </strong>
                            </td>
                        </tr>

                    </table>

                </div>

            </div>
            @endif

        </div>

    </div>

</div>

@endsection