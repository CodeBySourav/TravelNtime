@extends('layout_inner_page')

 

@section('front-content')

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="card border-0 shadow rounded-4">

                <div class="card-body p-5">

                    <div class="text-center mb-5">

                        <div class="mb-4">
                            <i class="fa-solid fa-circle-check text-success"
                               style="font-size:80px;"></i>
                        </div>

                        <h2 class="fw-bold text-success">
                            {{ __('Booking Confirmed!') }}
                        </h2>

                        <p class="text-muted mb-0">
                            Thank you for booking with us.
                        </p>

                    </div>

                    <hr>

                    <h4 class="mb-4">
                        {{ __('Booking Details') }}
                    </h4>

                    <table class="table table-bordered">

                        <tbody>

                            <tr>
                                <th width="35%">Booking ID</th>
                                <td>{{ $booking->booking_id ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Booking Reference</th>
                                <td>{{ $booking->booking_ref ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Confirmation No.</th>
                                <td>{{ $booking->confirmation_no ?? '-' }}</td>
                            </tr>

                            <tr>
                                <th>Hotel Name</th>
                                <td>{{ $booking->hotel_name }}</td>
                            </tr>

                            <tr>
                                <th>Lead Guest</th>
                                <td>{{ $booking->lead_name }}</td>
                            </tr>

                            <tr>
                                <th>Email</th>
                                <td>{{ $booking->email }}</td>
                            </tr>

                            <tr>
                                <th>Mobile</th>
                                <td>{{ $booking->mobile }}</td>
                            </tr>

                            <tr>
                                <th>Amount Paid</th>
                                <td>
                                    ₹{{ number_format($booking->amount,2) }}
                                </td>
                            </tr>

                            <tr>
                                <th>Status</th>
                                <td>
                                    <span class="  badge-success px-3 py-2">
                                        {{ $booking->booking_status }}
                                    </span>
                                </td>
                            </tr>

                        </tbody>

                    </table>

                    <div class="text-center mt-5">

                        <a href="{{ route('hotel.my.bookings') }}"
                            class="btn btn-primary btn-lg px-5">
                                <i class="fa-solid fa-house me-2"></i>
                                My Bookings
                            </a>

                    </div>

                </div>

            </div>

        </div>

    </div>

</div>

@endsection