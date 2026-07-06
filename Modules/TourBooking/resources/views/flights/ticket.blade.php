@extends('layout_inner_page')

@section('front-content')
<style>
    body {
        background-color: #f5f7fa;
    }
    .brand-red-bg {
        background: linear-gradient(135deg, #aa0022 0%, #d42a44 100%);
    }
    .text-brand-red {
        color: #aa0022;
    }
    .paytm-card {
        border: none;
        border-radius: 16px;
        background: #ffffff;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
    }
    .status-badge-success {
        background-color: #e6f9f0;
        color: #00b85c;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        display: inline-block;
    }
    .status-badge-warning {
        background-color: #fff7e6;
        color: #ff9f00;
        font-weight: 600;
        padding: 6px 16px;
        border-radius: 20px;
        display: inline-block;
    }
    .flight-timeline {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .timeline-line {
        flex-grow: 1;
        height: 2px;
        border-top: 2px dashed #e0e0e0;
        margin: 0 15px;
        position: relative;
    }
    .timeline-icon {
        position: absolute;
        top: -10px;
        left: 50%;
        transform: translateX(-50%);
        background: #ffffff;
        padding: 0 8px;
        color: #aa0022;
        font-size: 1.1rem;
    }
    .detail-label {
        font-size: 0.85rem;
        color: #757575;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 2px;
    }
    .detail-value {
        font-size: 1rem;
        font-weight: 600;
        color: #111111;
    }
    .fare-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px solid #f0f0f0;
    }
    .fare-row:last-child {
        border-bottom: none;
    }
    
    /* Print optimizations to cleanly hide sharing tools when printing */
    @media print {
        .no-print {
            display: none !important;
        }
        body {
            background-color: #ffffff;
        }
        .paytm-card {
            box-shadow: none;
            border: 1px solid #e0e0e0;
        }
    }
</style>

<div class="container py-5">

    @php
        $response = $ticket['Response'] ?? [];
        $data = $response['Response'] ?? [];
        $itinerary = $data['FlightItinerary'] ?? [];
    @endphp

    <div class="row justify-content-center">
        <div class="col-lg-9">
            
            {{-- Share & Action Bar (Hidden on Print) --}}
            <div class="paytm-card p-3 mb-4 no-print d-flex flex-wrap justify-content-between align-items-center gap-2">
                <div class="d-flex align-items-center">
                    <span class="fs-5 me-2">🔗</span>
                    <span class="text-muted small fw-medium">Need to send this itinerary?</span>
                </div>
                <div class="d-flex gap-2">
                    <button class="btn btn-sm btn-outline-dark px-3 rounded-pill fw-medium" onclick="copyTicketLink()">
                        📋 Copy Link
                    </button>
                    <button class="btn btn-sm text-white px-3 rounded-pill fw-medium" style="background-color: #aa0022;" onclick="window.print()">
                        🖨 Print / Download PDF
                    </button>
                </div>
            </div>
            
            {{-- Header Success Banner --}}
            <div class="paytm-card brand-red-bg text-white p-4 mb-4 text-center text-sm-start d-sm-flex align-items-center justify-content-between">
                <div>
                    <h3 class="fw-bold mb-1">🎉 Ticket Confirmed!</h3>
                    <p class="mb-0 opacity-75">Thank you for booking with us. Have a wonderful journey!</p>
                </div>
                <div class="mt-3 mt-sm-0">
                    <span class="bg-white text-dark px-3 py-2 rounded-3 fw-bold shadow-sm">
                        @if(($data['TicketStatus'] ?? 0) == 1)
                            <span class="text-success">●</span> Confirmed
                        @else
                            <span class="text-warning">●</span> Pending
                        @endif
                    </span>
                </div>
            </div>

            {{-- Booking Core Info (PNR & IDs) --}}
            <div class="paytm-card p-4 mb-4">
                <div class="row text-center text-md-start">
                    <div class="col-md-4 mb-3 mb-md-0 border-end-md">
                        <div class="detail-label">Airline PNR</div>
                        <div class="detail-value text-brand-red fs-4 fw-bold">{{ $data['PNR'] ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4 mb-3 mb-md-0 border-end-md ps-md-4">
                        <div class="detail-label">Booking ID</div>
                        <div class="detail-value fs-5">{{ $data['BookingId'] ?? 'N/A' }}</div>
                    </div>
                    <div class="col-md-4 ps-md-4">
                        <div class="detail-label">Fare Type</div>
                        <div class="detail-value text-success">
                            <span class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded">
                                {{ $itinerary['FareType'] ?? 'Regular' }}
                            </span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Flight Journey Details Segment --}}
            <h5 class="fw-bold text-brand-red mb-3">✈ Flight Itinerary</h5>
            @foreach($itinerary['Segments'] ?? [] as $segment)
                <div class="paytm-card p-4 mb-4">
                    <div class="d-flex align-items-center mb-4 pb-3 border-bottom">
                        <div class="bg-light p-2 rounded me-3 text-center fw-bold text-brand-red" style="min-width: 50px;">
                            {{ $segment['Airline']['AirlineCode'] ?? '' }}
                        </div>
                        <div>
                            <h6 class="mb-0 fw-bold text-dark">{{ $segment['Airline']['AirlineName'] ?? 'Airline' }}</h6>
                            <small class="text-muted">Flight {{ $segment['Airline']['AirlineCode'] ?? '' }}-{{ $segment['Airline']['FlightNumber'] ?? '' }}</small>
                        </div>
                        <div class="ms-auto text-end">
                            <div class="detail-label">Baggage</div>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary fw-semibold">{{ $segment['Baggage'] ?? 'N/A' }}</span>
                        </div>
                    </div>

                    <div class="row align-items-center">
                        <div class="col-md-3 text-center text-md-start">
                            <h3 class="fw-bold mb-0 text-brand-red">{{ $segment['Origin']['Airport']['AirportCode'] ?? '' }}</h3>
                            <div class="fw-semibold text-dark">{{ $segment['Origin']['Airport']['CityName'] ?? '' }}</div>
                            <div class="text-muted small mt-1">
                                {{ isset($segment['Origin']['DepTime']) ? date('d M Y, H:i', strtotime($segment['Origin']['DepTime'])) : '' }}
                            </div>
                        </div>

                        <div class="col-md-6 my-3 my-md-0 text-center">
                            <div class="flight-timeline">
                                <div class="timeline-line">
                                    <div class="timeline-icon">✈</div>
                                </div>
                            </div>
                            <small class="text-muted d-block mt-2 fw-medium">
                                Status: <span class="text-success">{{ $segment['Status'] ?? 'OK' }}</span>
                            </small>
                        </div>

                        <div class="col-md-3 text-center text-md-end">
                            <h3 class="fw-bold mb-0 text-brand-red">{{ $segment['Destination']['Airport']['AirportCode'] ?? '' }}</h3>
                            <div class="fw-semibold text-dark">{{ $segment['Destination']['Airport']['CityName'] ?? '' }}</div>
                            <div class="text-muted small mt-1">
                                {{ isset($segment['Destination']['ArrTime']) ? date('d M Y, H:i', strtotime($segment['Destination']['ArrTime'])) : '' }}
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

            {{-- Passengers Section --}}
            <h5 class="fw-bold text-brand-red mb-3">👤 Passenger Details</h5>
            <div class="paytm-card p-4 mb-4">
                <div class="table-responsive">
                    <table class="table table-borderless align-middle mb-0">
                        <thead>
                            <tr class="text-muted small text-uppercase" style="border-bottom: 2px solid #f4f4f4;">
                                <th class="pb-3">Name</th>
                                <th class="pb-3">Contact Details</th>
                                <th class="pb-3">Ticket Number</th>
                                <th class="pb-3 text-end">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($itinerary['Passenger'] ?? [] as $pax)
                                <tr style="border-bottom: 1px solid #f8f9fa;">
                                    <td class="py-3">
                                        <div class="fw-bold text-dark">
                                            {{ $pax['Title'] ?? '' }} {{ $pax['FirstName'] ?? '' }} {{ $pax['LastName'] ?? '' }}
                                        </div>
                                    </td>
                                    <td class="py-3">
                                        <div class="small text-dark"><i class="far fa-envelope text-muted me-1"></i>{{ $pax['Email'] ?? 'N/A' }}</div>
                                        <div class="small text-muted mt-1"><i class="fas fa-phone text-muted me-1"></i>{{ $pax['ContactNo'] ?? 'N/A' }}</div>
                                    </td>
                                    <td class="py-3">
                                        <span class="font-monospace text-secondary fw-semibold">{{ $pax['Ticket']['TicketNumber'] ?? 'N/A' }}</span>
                                        @if(isset($pax['Ticket']['IssueDate']))
                                            <div class="small text-muted mt-1">Issued: {{ date('d M Y', strtotime($pax['Ticket']['IssueDate'])) }}</div>
                                        @endif
                                    </td>
                                    <td class="py-3 text-end">
                                        @if(($pax['Ticket']['Status'] ?? '') == 'OK' || ($pax['Ticket']['Status'] ?? '') == 'Confirmed')
                                            <span class="status-badge-success">Confirmed</span>
                                        @else
                                            <span class="status-badge-warning">{{ $pax['Ticket']['Status'] ?? 'Pending' }}</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            {{-- Fare Details Section --}}
            @if(isset($itinerary['Fare']))
                <h5 class="fw-bold text-brand-red mb-3">💰 Payment Breakdown</h5>
                <div class="paytm-card p-4">
                    <div class="fare-row">
                        <span class="text-muted">Base Fare</span>
                        <span class="fw-semibold text-dark">₹ {{ number_format($itinerary['Fare']['BaseFare'] ?? 0, 2) }}</span>
                    </div>
                    <div class="fare-row">
                        <span class="text-muted">Taxes & Fees</span>
                        <span class="fw-semibold text-dark">₹ {{ number_format($itinerary['Fare']['Tax'] ?? 0, 2) }}</span>
                    </div>
                    <div class="fare-row pt-3 mt-2 border-top" style="border-top: 2px dashed #f0f0f0 !important;">
                        <span class="fs-5 fw-bold text-dark">Total Amount Paid</span>
                        <span class="fs-4 fw-bold text-brand-red">₹ {{ number_format($itinerary['Fare']['PublishedFare'] ?? 0, 2) }}</span>
                    </div>
                </div>
            @endif

        </div>
    </div>
</div>

<script>
    function copyTicketLink() {
        const dummyUrl = window.location.href;
        navigator.clipboard.writeText(dummyUrl).then(() => {
            alert('Ticket link copied to clipboard successfully!');
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }
</script>
@endsection