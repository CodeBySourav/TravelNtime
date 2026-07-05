@extends('layout_inner_page')

@section('front-content')

<div class="container">

<div class="card mb-4">

<div class="card-body">

<h4>

{{ $search['origin'] }}

→

{{ $search['destination'] }}

</h4>

<p>

Departure :

{{ $search['departure_date'] }}

</p>

<p>

Adults :

{{ $search['adult'] }}

Child :

{{ $search['child'] }}

Infant :

{{ $search['infant'] }}

</p>

</div>

</div>

@foreach($results as $group)

    @foreach($group as $flight)

        @php

        $segment=$flight['Segments'][0][0];

        @endphp

        <div class="card mb-3 shadow-sm">

            <div class="card-body">

                <div class="row align-items-center">

                    <div class="col-md-3">

                        <h5>

                            {{ $segment['Airline']['AirlineName'] }}

                        </h5>

                        <small>

                            {{ $segment['Airline']['AirlineCode'] }}

                            {{ $segment['Airline']['FlightNumber'] }}

                        </small>

                    </div>

                    <div class="col-md-2">

                        <h4>

                            {{ date('H:i',strtotime($segment['Origin']['DepTime'])) }}

                        </h4>

                        <small>

                            {{ $segment['Origin']['Airport']['AirportCode'] }}

                        </small>

                    </div>

                    <div class="col-md-2 text-center">

                        →

                    </div>

                    <div class="col-md-2">

                        <h4>

                            {{ date('H:i',strtotime($segment['Destination']['ArrTime'])) }}

                        </h4>

                        <small>

                            {{ $segment['Destination']['Airport']['AirportCode'] }}

                        </small>

                    </div>

                    <div class="col-md-2">

                        ₹{{ number_format($flight['Fare']['PublishedFare']) }}

                    </div>

                    <div class="col-md-1">

                        <form method="POST"

                              action="{{ route('flight.fareQuote') }}">

                            @csrf

                            <input type="hidden"

                                   name="result_index"

                                   value="{{ $flight['ResultIndex'] }}">

                            <button type="submit" 

                                class="btn btn-primary">

                                Select Flight

                            </button>

                        </form>

                    </div>

                </div>

            </div>

        </div>

    @endforeach

@endforeach

</div>

@endsection