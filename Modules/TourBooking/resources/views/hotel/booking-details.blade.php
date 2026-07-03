@extends('layout_inner_page')

@section('front-content')

<div class="container">

    <h2 class="mb-4">
        Booking Details
    </h2>

    <div class="card">

        <div class="card-body">

            <h4>{{ $details['HotelName'] }}</h4>

            <hr>

            <p>
                <strong>Status:</strong>
                {{ $details['HotelBookingStatus'] }}
            </p>

            <p>
                <strong>Booking ID:</strong>
                {{ $details['BookingId'] }}
            </p>

            <p>
                <strong>Confirmation No:</strong>
                {{ $details['ConfirmationNo'] }}
            </p>

            <p>
                <strong>Booking Ref:</strong>
                {{ $details['BookingReferenceNo'] ?? '-' }}
            </p>

            <p>
                <strong>Check In:</strong>
                {{ $details['CheckInDate'] }}
            </p>

            <p>
                <strong>Check Out:</strong>
                {{ $details['CheckOutDate'] }}
            </p>

            <p>
                <strong>Booking Date:</strong>
                {{ $details['BookingDate'] }}
            </p>

            <p>
                <strong>Address:</strong>
                {{ $details['AddressLine1'] }}
            </p>

            <p>
                <strong>City:</strong>
                {{ $details['City'] }}
            </p>

            <p>
                <strong>Rooms:</strong>
                {{ $details['NoOfRooms'] }}
            </p>

        </div>

    </div>

    @foreach($details['HotelRoomsDetails'] as $room)

        <div class="card mt-4">

            <div class="card-header">

                {{ $room['RoomTypeName'] }}

            </div>

            <div class="card-body">

                <p>
                    <strong>Rate Plan:</strong>
                    {{ $room['RatePlanName'] ?? '-' }}
                </p>

                <p>
                    <strong>Cancellation Policy:</strong>
                    {!! nl2br($room['CancellationPolicy']) !!}
                </p>

                <p>
                    <strong>Last Cancellation Date:</strong>
                    {{ $room['LastCancellationDate'] }}
                </p>

                <p>
                    <strong>Invoice No:</strong>
                    {{ $room['InvoiceNo'] }}
                </p>

                <p>
                    <strong>Hotel Confirmation:</strong>
                    {{ $room['HotelConfirmationNo'] ?? '-' }}
                </p>

                <h5 class="mt-4">
                    Guests
                </h5>

                <table class="table">

                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Type</th>
                        </tr>
                    </thead>

                    <tbody>

                    @foreach($room['HotelPassenger'] as $guest)

                        <tr>

                            <td>
                                {{ $guest['Title'] }}
                                {{ $guest['FirstName'] }}
                                {{ $guest['LastName'] }}
                            </td>

                            <td>
                                {{ $guest['PaxType'] == 1 ? 'Adult' : 'Child' }}
                            </td>

                        </tr>

                    @endforeach

                    </tbody>

                </table>

                <h5>
                    Price Details
                </h5>

                <table class="table table-bordered">

                    <tr>
                        <th>Room Price</th>
                        <td>₹{{ $room['Price']['RoomPrice'] }}</td>
                    </tr>

                    <tr>
                        <th>Tax</th>
                        <td>₹{{ $room['Price']['Tax'] }}</td>
                    </tr>

                    <tr>
                        <th>Offered Price</th>
                        <td>
                            ₹{{ $room['Price']['OfferedPrice'] }}
                        </td>
                    </tr>

                </table>

            </div>

        </div>

    @endforeach

</div>

@endsection