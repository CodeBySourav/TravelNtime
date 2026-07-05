@extends('layout_inner_page')

@section('front-content') 

<div class="container py-5">

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    <div class="card shadow-lg border-0">

        <div class="card-header bg-primary text-white">

            <h3 class="mb-0">
                Flight Search
            </h3>

        </div>

        <div class="card-body">

            <form method="POST" action="{{ route('flight.search') }}">

                @csrf

                <div class="row">

                    <div class="col-md-3">

                        <label>Journey</label>

                        <select
                            class="form-control"
                            name="journey_type"
                            id="journey_type">

                            <option value="1">One Way</option>
                            <option value="2">Round Trip</option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>Cabin</label>

                        <select
                            class="form-control"
                            name="cabin_class">

                            <option value="1">All</option>
                            <option value="2" selected>Economy</option>
                            <option value="3">Premium Economy</option>
                            <option value="4">Business</option>
                            <option value="5">Premium Business</option>
                            <option value="6">First</option>

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>Adult</label>

                        <select
                            class="form-control"
                            name="adult">

                            @for($i=1;$i<=9;$i++)
                                <option value="{{$i}}">
                                    {{$i}}
                                </option>
                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>Child</label>

                        <select
                            class="form-control"
                            name="child">

                            @for($i=0;$i<=8;$i++)
                                <option value="{{$i}}">
                                    {{$i}}
                                </option>
                            @endfor

                        </select>

                    </div>

                </div>

                <div class="row mt-4">

                    <div class="col-md-3">

                        <label>Infant</label>

                        <select
                            class="form-control"
                            name="infant">

                            @for($i=0;$i<=8;$i++)
                                <option value="{{$i}}">
                                    {{$i}}
                                </option>
                            @endfor

                        </select>

                    </div>

                    <div class="col-md-3">

                        <label>From</label>

                        <input
                            type="text"
                            class="form-control"
                            name="origin"
                            placeholder="DEL"
                            required>

                    </div>

                    <div class="col-md-3">

                        <label>To</label>

                        <input
                            type="text"
                            class="form-control"
                            name="destination"
                            placeholder="BOM"
                            required>

                    </div>

                    <div class="col-md-3">

                        <label>Departure</label>

                        <input
                            type="date"
                            class="form-control"
                            name="departure_date"
                            required>

                    </div>

                </div>

                <div class="row mt-4">

                    <div
                        class="col-md-3"
                        id="returnBox"
                        style="display:none;">

                        <label>Return Date</label>

                        <input
                            type="date"
                            class="form-control"
                            name="return_date">

                    </div>

                    <div class="col-md-3">

                        <label>

                            <input
                                type="checkbox"
                                name="direct_flight"
                                value="1">

                            Direct Flight

                        </label>

                    </div>

                    <div class="col-md-3">

                        <label>

                            <input
                                type="checkbox"
                                name="one_stop_flight"
                                value="1">

                            One Stop

                        </label>

                    </div>

                    <div class="col-md-3">

                        <label>Preferred Airline</label>

                        <input
                            type="text"
                            name="preferred_airline"
                            class="form-control"
                            placeholder="AI">

                    </div>

                </div>

                <div class="mt-5 text-center">

                    <button
                        class="btn btn-primary btn-lg px-5">

                        Search Flights

                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

<script>

document.getElementById('journey_type')
.addEventListener('change',function(){

    if(this.value==2){

        document
        .getElementById('returnBox')
        .style.display='block';

    }else{

        document
        .getElementById('returnBox')
        .style.display='none';

    }

});

</script>

@endsection