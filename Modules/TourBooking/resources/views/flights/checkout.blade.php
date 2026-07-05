@extends('layout_inner_page')

@section('front-content')

<div class="container py-4">

    <h2>Review Your Booking</h2>

    <hr>

    @php

        $flight = $fareQuote['Results'];

    @endphp

    <div class="card mb-4">

        <div class="card-header">
            Flight Details
        </div>

        <div class="card-body">

            @foreach($flight['Segments'] as $journey)

                @foreach($journey as $segment)

                    <p>

                        <strong>

                            {{ $segment['Airline']['AirlineName'] }}

                            {{ $segment['Airline']['FlightNumber'] }}

                        </strong>

                    </p>

                    <p>

                        {{ $segment['Origin']['Airport']['CityName'] }}

                        →

                        {{ $segment['Destination']['Airport']['CityName'] }}

                    </p>

                    <p>

                        {{ date('d M Y H:i',strtotime($segment['Origin']['DepTime'])) }}

                        -

                        {{ date('d M Y H:i',strtotime($segment['Destination']['ArrTime'])) }}

                    </p>

                    <hr>

                @endforeach

            @endforeach

        </div>

    </div>


    <div class="card mb-4">

        <div class="card-header">

            Contact Details

        </div>

        <div class="card-body">

            <p>

                <strong>Email :</strong>

                {{ $contact['email'] }}

            </p>

            <p>

                <strong>Mobile :</strong>

                {{ $contact['mobile'] }}

            </p>

        </div>

    </div>


    <div class="card mb-4">

        <div class="card-header">

            Passenger Details

        </div>

        <div class="card-body">

            @foreach($travellers as $i=>$traveller)

                <h5>

                    Passenger {{ $i+1 }}

                </h5>

                <table class="table table-bordered">

                    <tr>

                        <th>Name</th>

                        <td>

                            {{ $traveller['title'] }}

                            {{ $traveller['first_name'] }}

                            {{ $traveller['last_name'] }}

                        </td>

                    </tr>

                    <tr>

                        <th>Gender</th>

                        <td>{{ $traveller['gender'] }}</td>

                    </tr>

                    <tr>

                        <th>DOB</th>

                        <td>{{ $traveller['dob'] }}</td>

                    </tr>

                    <tr>

                        <th>Meal</th>

                        <td>{{ $traveller['meal'] ?? 'None' }}</td>

                    </tr>

                    <tr>

                        <th>Baggage</th>

                        <td>{{ $traveller['baggage'] ?? 'Default' }}</td>

                    </tr>

                    <tr>

                        <th>Seat</th>

                        <td>{{ $traveller['seat'] ?? 'Auto' }}</td>

                    </tr>

                </table>

            @endforeach

        </div>

    </div>


    <div class="card">

        <div class="card-header">

            Fare Summary

        </div>

        <div class="card-body">

            <table class="table">

                <tr>

                    <th>Base Fare</th>

                    <td>₹{{ number_format($flight['Fare']['BaseFare'],2) }}</td>

                </tr>

                <tr>

                    <th>Tax</th>

                    <td>₹{{ number_format($flight['Fare']['Tax'],2) }}</td>

                </tr>

                <tr>

                    <th>Meal Charges</th>

                    <td>₹{{ number_format($flight['Fare']['TotalMealCharges'],2) }}</td>

                </tr>

                <tr>

                    <th>Baggage Charges</th>

                    <td>₹{{ number_format($flight['Fare']['TotalBaggageCharges'],2) }}</td>

                </tr>

                <tr>

                    <th>Seat Charges</th>

                    <td>₹{{ number_format($flight['Fare']['TotalSeatCharges'],2) }}</td>

                </tr>

                <tr class="table-primary">

                    <th>Total</th>

                    <th>

                        ₹{{ number_format($flight['Fare']['PublishedFare'],2) }}

                    </th>

                </tr>

            </table>

        </div>

    </div>

    <form method="POST" action="{{ route('flight.book') }}">

        @csrf
        <input type="hidden" name="flight_type" value="{{ $type }}">
        <button class="btn btn-success btn-lg mt-4">

            Confirm Booking

        </button>

    </form>

</div>

@endsection