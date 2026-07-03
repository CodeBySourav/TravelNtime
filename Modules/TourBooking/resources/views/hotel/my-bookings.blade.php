@extends('layout_inner_page')

@section('front-content')

<div class="container py-4">

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
    @endif

    @forelse($bookings as $booking)

        @php

            $live = $booking->live ?? [];

            $bookingStatus = (int)($live['BookingStatus'] ?? 0);
            $hotelStatus   = $live['HotelBookingStatus'] ?? 'Unknown';

            switch ($bookingStatus) {

                case 2:
                    $badge = 'success';
                    $statusText = 'Confirmed';
                    break;

                case 4:

                    if (($booking->change_request_status ?? 0) == 1) {
                        $badge = 'warning';
                        $statusText = 'Cancellation Pending';
                    } else {
                        $badge = 'danger';
                        $statusText = 'Cancelled';
                    }

                    break;

                default:
                    $badge = 'secondary';
                    $statusText = 'Pending';
                    break;
            }


            $room = $live['HotelRoomsDetails'][0] ?? [];

            $leadPassenger = '';

            if (!empty($room['HotelPassenger'])) {

                foreach ($room['HotelPassenger'] as $passenger) {

                    if (($passenger['LeadPassenger'] ?? false) == true) {

                        $leadPassenger = trim(
                            ($passenger['Title'] ?? '') . ' ' .
                            ($passenger['FirstName'] ?? '') . ' ' .
                            ($passenger['LastName'] ?? '')
                        );

                        break;
                    }
                }
            }

        @endphp

        <div class="card shadow-sm mb-4">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-start">

                    <div>

                        <h4 class="fw-bold mb-2">
                            {{ $live['HotelName'] ?? 'Hotel Name' }}
                        </h4>

                        <span class="badge bg-{{ $badge }}">
                            {{ $statusText }}
                        </span>

                    </div>

                    <div class="text-end">

                        <h5 class="text-primary mb-0">

                            ₹{{ number_format($room['Price']['OfferedPriceRoundedOff'] ?? $booking->amount ?? 0) }}

                        </h5>

                    </div>

                </div>

                <hr>

                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>Booking ID</strong><br>
                        {{ $live['BookingId'] ?? $booking->booking_id }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Booking Ref</strong><br>
                        {{ $live['BookingRefNo'] ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Confirmation No</strong><br>
                        {{ $live['ConfirmationNo'] ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Lead Guest</strong><br>
                        {{ $leadPassenger ?: '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Room</strong><br>
                        {{ $room['RoomTypeName'] ?? '-' }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Guests</strong><br>
                        {{ count($room['HotelPassenger'] ?? []) }}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Check In</strong><br>

                        @if(!empty($live['CheckInDate']))
                            {{ \Carbon\Carbon::parse($live['CheckInDate'])->format('d M Y') }}
                        @else
                            -
                        @endif

                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Check Out</strong><br>

                        @if(!empty($live['CheckOutDate']))
                            {{ \Carbon\Carbon::parse($live['CheckOutDate'])->format('d M Y') }}
                        @else
                            -
                        @endif

                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Star Rating</strong><br>
                        ⭐ {{ $live['StarRating'] ?? '-' }}
                    </div>

                    <div class="col-md-12 mb-3">
                        <strong>Address</strong><br>
                        {{ $live['AddressLine1'] ?? '-' }}
                    </div>

                </div>

                <hr>

                <div class="d-flex flex-wrap gap-2">

    {{-- View Details --}}
    <a href="{{ route('hotel.booking.show', $booking->id) }}"
       class="btn btn-primary">
        <i class="fa fa-eye"></i> View Details
    </a>

    {{-- Cancelled --}}
    @if($booking->booking_status == 'Cancelled')

        <button class="btn btn-danger" disabled>
            <i class="fa fa-times-circle"></i> Cancelled
        </button>

    {{-- Cancellation Pending --}}
    @elseif($booking->change_request_status == 1)

        <button class="btn btn-warning text-dark" disabled>
            <i class="fa fa-clock"></i> Cancellation Pending
        </button>

    {{-- Confirmed --}}
    @elseif($booking->booking_status == 'Confirmed')

        <form action="{{ route('hotel.cancel', $booking->id) }}"
              method="POST"
              onsubmit="return confirm('Are you sure you want to cancel this booking?');">

            @csrf

            <button type="submit"
                    class="btn btn-danger"
                     
                <i class="fa fa-times"></i> Cancel Booking

            </button>

        </form>

    {{-- Other Status --}}
    @else

        <button class="btn btn-secondary" disabled>
            {{ $booking->booking_status }}
        </button>

    @endif

</div>

            </div>

        </div>

    @empty

        <div class="alert alert-info text-center">

            <h5>No hotel bookings found.</h5>

        </div>

    @endforelse

</div>

@endsection