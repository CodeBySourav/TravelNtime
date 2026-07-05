@extends('layout_inner_page')

@section('front-content')

<div class="container py-5">

    @php
        $response = $booking['Response'] ?? [];
        $itinerary = $response['FlightItinerary'] ?? [];

        $segments = $itinerary['Segments'][0] ?? [];

        $fare = $itinerary['Fare'] ?? [];

        $passengers = $itinerary['Passenger'] ?? [];
    @endphp

    <div class="card shadow">

        <div class="card-header bg-success text-white">
            <h3 class="mb-0">🎉 Flight Booking Successful</h3>
        </div>

        <div class="card-body">

            <div class="alert alert-success">
                Your flight has been booked successfully.
            </div>

            <hr>

            <h4>Booking Information</h4>

            <table class="table table-bordered">

                <tr>
                    <th width="30%">Booking ID</th>
                    <td>{{ $itinerary['BookingId'] ?? '-' }}</td>
                </tr>

                <tr>
                    <th>PNR</th>
                    <td>{{ $itinerary['PNR'] ?? '-' }}</td>
                </tr>

                <tr>
                    <th>Booking Status</th>
                    <td>{{ $itinerary['BookingStatus'] ?? 'Confirmed' }}</td>
                </tr>

                <tr>
                    <th>Invoice Number</th>
                    <td>{{ $itinerary['InvoiceNo'] ?? '-' }}</td>
                </tr>

            </table>

            <hr>

            <h4>Flight Details</h4>

            @foreach($segments as $segment)

                <div class="card mb-3">

                    <div class="card-body">

                        <div class="row">

                            <div class="col-md-3">
                                <strong>Airline</strong><br>
                                {{ $segment['Airline']['AirlineName'] }}
                            </div>

                            <div class="col-md-2">
                                <strong>Flight</strong><br>
                                {{ $segment['Airline']['AirlineCode'] }}
                                {{ $segment['Airline']['FlightNumber'] }}
                            </div>

                            <div class="col-md-3">
                                <strong>Departure</strong><br>
                                {{ $segment['Origin']['Airport']['CityName'] }}<br>
                                {{ date('d M Y H:i', strtotime($segment['Origin']['DepTime'])) }}
                            </div>

                            <div class="col-md-3">
                                <strong>Arrival</strong><br>
                                {{ $segment['Destination']['Airport']['CityName'] }}<br>
                                {{ date('d M Y H:i', strtotime($segment['Destination']['ArrTime'])) }}
                            </div>

                            <div class="col-md-1">
                                <strong>Duration</strong><br>
                                {{ $segment['Duration'] }} Min
                            </div>

                        </div>

                    </div>

                </div>

            @endforeach

            <hr>

            <h4>Passenger Details</h4>

            <table class="table table-bordered">

                <thead class="table-light">

                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Ticket No</th>
                    <th>Status</th>
                </tr>

                </thead>

                <tbody>

                @foreach($passengers as $passenger)

                    <tr>

                        <td>
                            {{ $passenger['Title'] ?? '' }}
                            {{ $passenger['FirstName'] ?? '' }}
                            {{ $passenger['LastName'] ?? '' }}
                        </td>

                        <td>

                            @if(($passenger['PaxType'] ?? 1)==1)
                                Adult
                            @elseif(($passenger['PaxType'] ?? 1)==2)
                                Child
                            @else
                                Infant
                            @endif

                        </td>

                        <td>
                            {{ $passenger['Ticket']['TicketNumber'] ?? '-' }}
                        </td>

                        <td>
                            {{ $passenger['Ticket']['TicketStatus'] ?? 'Confirmed' }}
                        </td>

                    </tr>

                @endforeach

                </tbody>

            </table>

            <hr>

            <h4>Fare Summary</h4>

            <table class="table table-bordered">

                <tr>
                    <th>Base Fare</th>
                    <td>₹ {{ number_format($fare['BaseFare'] ?? 0,2) }}</td>
                </tr>

                <tr>
                    <th>Tax</th>
                    <td>₹ {{ number_format($fare['Tax'] ?? 0,2) }}</td>
                </tr>

                <tr>
                    <th>Published Fare</th>
                    <td>₹ {{ number_format($fare['PublishedFare'] ?? 0,2) }}</td>
                </tr>

                <tr class="table-success">
                    <th>Total Paid</th>
                    <td>
                        <strong>
                            ₹ {{ number_format($fare['PublishedFare'] ?? 0,2) }}
                        </strong>
                    </td>
                </tr>

            </table>

            <div class="text-center mt-4">

                <a href="{{ route('flight.search.form') }}"
                   class="btn btn-primary">

                    Book Another Flight

                </a>

            </div>

        </div>

    </div>

</div>

@endsection