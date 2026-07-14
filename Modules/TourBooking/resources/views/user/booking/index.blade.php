@extends('user.master_layout')
@section('title')
    <title>{{ __('translate.Bookings list') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Bookings list') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Bookings list') }} >> {{ __('translate.Bookings list') }}</p>
@endsection

@section('body-content')
    <style>
        .custom-booking-tabs .nav-link {
            color: #555555;
            background-color: #f9f9f9;
            border: 1px solid #e3e3e3 !important;
            border-bottom: none !important;
            font-weight: 600;
            padding: 12px 24px;
            transition: all 0.3s ease;
            border-radius: 6px 6px 0 0;
        }
        .custom-booking-tabs .nav-link:hover {
            color: #aa0022;
            background-color: #fff1f3;
        }
        .custom-booking-tabs .nav-link.active {
            color: #ffffff !important;
            background-color: #aa0022 !important;
            border-color: #aa0022 !important;
            box-shadow: 0 -2px 10px rgba(170, 0, 34, 0.15);
        }
        .brand-badge-success {
            background-color: #e6f7ed;
            color: #28a745;
            padding: 6px 12px;
            border-radius: 30px;
            font-weight: 600;
            font-size: 12px;
            display: inline-block;
        }
        .brand-btn-view {
            background-color: #aa0022 !important;
            color: #ffffff !important;
            border: 1px solid #aa0022 !important;
            padding: 8px 16px;
            border-radius: 4px;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }
        .brand-btn-view:hover {
            background-color: #ffffff !important;
            color: #aa0022 !important;
            box-shadow: 0 4px 12px rgba(170, 0, 34, 0.2);
        }
        .pnr-highlight {
            color: #aa0022;
            font-weight: 700;
            letter-spacing: 0.5px;
            background-color: #fff1f3;
            padding: 4px 8px;
            border-radius: 4px;
            border: 1px dashed rgba(170, 0, 34, 0.3);
        }
        .crancy-table__head th {
            background-color: #fcfcfc;
            border-bottom: 2px solid #eaeaea;
            color: #333333;
            font-weight: 700;
            text-transform: uppercase;
            font-size: 12px;
            letter-spacing: 0.5px;
        }
    </style>

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            
                            <ul class="nav nav-tabs custom-booking-tabs" id="bookingTabs" role="tablist" style="border-bottom: 2px solid #aa0022; margin-bottom: 25px; display: flex; gap: 6px;">
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link active" id="flight-tab" data-bs-toggle="tab" data-bs-target="#flight" type="button" role="tab" aria-controls="flight" aria-selected="true">
                                        <i class="fas fa-plane mg-right-5"></i> {{ __('Flight Bookings') }}
                                    </button>
                                </li>
                                <li class="nav-item" role="presentation">
                                    <button class="nav-link" id="hotel-tab" data-bs-toggle="tab" data-bs-target="#hotel" type="button" role="tab" aria-controls="hotel" aria-selected="false">
                                        <i class="fas fa-hotel mg-right-5"></i> {{ __('Hotel Bookings') }}
                                    </button>
                                </li>
                            </ul>

                            <div class="tab-content" id="bookingTabsContent">
                                
                                <div class="tab-pane fade show active" id="flight" role="tabpanel" aria-labelledby="flight-tab">
                                    <div class="crancy-table crancy-table--v3">
                                        <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">
                                            <table class="crancy-table__main crancy-table__main-v3 no-footer" id="flightDataTable">
                                                <thead class="crancy-table__head">
                                                    <tr>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('PNR') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Airline / Flight') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Route') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Fare') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Status') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="crancy-table__body">
                                                    @forelse ($flightBookings as $flight)
                                                        <tr class="odd">
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <span class="pnr-highlight">{{ $flight->pnr }}</span>
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="font-weight: 500;">
                                                                {{ $flight->airline }} <span class="text-muted">({{ $flight->flight_number }})</span>
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <span style="font-weight: 600; color: #333;">{{ $flight->origin }}</span> 
                                                                <i class="fas fa-long-arrow-alt-right mx-2" style="color: #aa0022;"></i> 
                                                                <span style="font-weight: 600; color: #333;">{{ $flight->destination }}</span>
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="font-weight: 700; color: #aa0022;">
                                                                {{ currency($flight->offered_fare) }}
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <span class="brand-badge-success">{{ $flight->status }}</span>
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <a href="javascript:void(0)"
                                                                    class="brand-btn-view booking-details-btn"
                                                                    data-booking-id="{{ $flight->booking_id }}"
                                                                    data-pnr="{{ $flight->pnr }}"
                                                                    data-trace-id="{{ $flight->trace_id }}">
                                                                        <i class="fas fa-eye"></i> Details
                                                                    </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center p-5 text-muted">
                                                                <i class="fas fa-plane-departure fa-2x mb-3 d-block" style="color: #ccc;"></i>
                                                                {{ __('No flight bookings found') }}
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <div class="tab-pane fade" id="hotel" role="tabpanel" aria-labelledby="hotel-tab">
                                    <div class="crancy-table crancy-table--v3">
                                        <div id="crancy-table__main_wrapper" class="dt-bootstrap5 no-footer">
                                            <table class="crancy-table__main crancy-table__main-v3 no-footer" id="hotelDataTable">
                                                <thead class="crancy-table__head">
                                                    <tr>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Booking Ref') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Hotel Name') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Total Amount') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Lead Name') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Status') }}</th>
                                                        <th class="crancy-table__column-2 crancy-table__h2">{{ __('Action') }}</th>
                                                    </tr>
                                                </thead>
                                                <tbody class="crancy-table__body">
                                                    @forelse ($hotelBookings as $hotel)
                                                        <tr class="odd">
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="font-weight: 600; color: #555;">
                                                                #{{ $hotel->booking_ref ?? $hotel->booking_id }}
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="font-weight: 600; color: #222;">
                                                                {{ Str::limit($hotel->hotel_name, 50) }}
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="font-weight: 700; color: #aa0022;">
                                                                {{ currency($hotel->amount) }}
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2" style="color: #555;">
                                                                {{ $hotel->lead_name }}
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <span class="brand-badge-success">{{ $hotel->booking_status }}</span>
                                                            </td>
                                                            <td class="crancy-table__column-2 crancy-table__data-2">
                                                                <a href="#" class="brand-btn-view">
                                                                    <i class="fas fa-eye"></i> {{ __('Details') }}
                                                                </a>
                                                            </td>
                                                        </tr>
                                                    @empty
                                                        <tr>
                                                            <td colspan="6" class="text-center p-5 text-muted">
                                                                <i class="fas fa-hotel fa-2x mb-3 d-block" style="color: #ccc;"></i>
                                                                {{ __('No hotel bookings found') }}
                                                            </td>
                                                        </tr>
                                                    @endforelse
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>


    <div class="modal fade" id="bookingDetailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">
                    Flight Booking Details
                </h5>

                <button class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body">

                <div id="bookingLoader" class="text-center p-5" style="display:none;">
                    <i class="fas fa-spinner fa-spin fa-3x"></i>
                    <br><br>
                    Loading Booking...
                </div>

                <div id="bookingResult"></div>

            </div>

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
 
<script>

$(document).on('click','.booking-details-btn',function(){

    let bookingId=$(this).data('booking-id');
    let pnr=$(this).data('pnr');
    let traceId=$(this).data('trace-id');

    $("#bookingDetailsModal").modal('show');

    $("#bookingLoader").show();
    $("#bookingResult").html('');

    $.ajax({

        url:"{{ route('flight.booking.details') }}",

        type:"POST",

        data:{
            _token:"{{ csrf_token() }}",
            booking_id:bookingId,
            pnr:pnr,
            trace_id:traceId
        },

        success:function(res){

            $("#bookingLoader").hide();

            if(res.success){

                let d=res.data.Response.FlightItinerary;

                let html='';

                html+=`
                <div class="row">

                    <div class="col-md-4 mb-3">
                        <strong>PNR</strong><br>
                        ${d.PNR}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Booking ID</strong><br>
                        ${d.BookingId}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Status</strong><br>
                        ${d.TicketStatus ?? d.Status}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Origin</strong><br>
                        ${d.Origin}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Destination</strong><br>
                        ${d.Destination}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Airline</strong><br>
                        ${d.AirlineCode}
                    </div>

                    <div class="col-md-4 mb-3">
                        <strong>Fare</strong><br>
                        ₹ ${d.Fare.OfferedFare}
                    </div>

                </div>

                <hr>

                <h5>Passengers</h5>

                <table class="table table-bordered">

                    <thead>

                        <tr>

                            <th>Name</th>

                            <th>Type</th>

                            <th>Ticket No.</th>

                            <th>Status</th>

                        </tr>

                    </thead>

                    <tbody>
                `;

                d.Passenger.forEach(function(p){

                    html+=`

                    <tr>

                        <td>${p.Title} ${p.FirstName} ${p.LastName}</td>

                        <td>${p.PaxType}</td>

                        <td>${p.Ticket.TicketNumber}</td>

                        <td>${p.Ticket.Status}</td>

                    </tr>

                    `;

                });

                html+=`</tbody></table>`;

                html+=`<hr><h5>Segments</h5>`;

                d.Segments.forEach(function(s){

                    html+=`

                    <div class="card mb-3">

                        <div class="card-body">

                            <b>${s.Airline.AirlineName}
                            (${s.Airline.FlightNumber})</b>

                            <br>

                            ${s.Origin.Airport.CityName}
                            →

                            ${s.Destination.Airport.CityName}

                            <br>

                            Departure :
                            ${s.Origin.DepTime}

                            <br>

                            Arrival :
                            ${s.Destination.ArrTime}

                            <br>

                            Flight Status :
                            ${s.FlightStatus}

                        </div>

                    </div>

                    `;

                });

                $("#bookingResult").html(html);

            }else{

                $("#bookingResult").html('<div class="alert alert-danger">'+res.message+'</div>');

            }

        }

    });

});

</script>
@endsection