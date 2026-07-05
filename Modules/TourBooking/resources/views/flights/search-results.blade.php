@extends('layout_inner_page')

@section('front-content')
    <div class="container py-4" style="background-color: #fcfcfc; min-height: 100vh;">

        @if (session('error'))
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #aa0022 !important;">
                <i class="fas fa-exclamation-circle me-2" style="color: #aa0022;"></i> {{ session('error') }}
            </div>
        @endif

        <div class="card border-0 shadow-sm p-4 mb-4" style="border-radius: 16px; background-color: #ffffff;">
            <form action="{{ route('flight.search') }}" method="POST">
                @csrf
                <div class="row g-3 align-items-end">

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">
                            <i class="fas fa-plane-departure me-1" style="color: #aa0022;"></i> From
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                            <input type="text" name="origin" class="form-control border-start-0 ps-0 fw-semibold"
                                placeholder="City or Airport" value="{{ old('origin', request('origin')) }}">
                        </div>
                    </div>

                    <div class="col-auto d-none d-lg-block px-0 text-center" style="margin-bottom: 6px;">
                        <button type="button"
                            class="btn btn-light rounded-circle shadow-sm border border-2 border-white position-relative"
                            style="z-index: 2; width: 36px; height: 36px; padding: 0; margin-left: -18px; margin-right: -18px;">
                            <i class="fas fa-exchange-alt small text-secondary"></i>
                        </button>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">
                            <i class="fas fa-plane-arrival me-1" style="color: #aa0022;"></i> To
                        </label>
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                            <input type="text" name="destination" class="form-control border-start-0 ps-0 fw-semibold"
                                placeholder="City or Airport" value="{{ old('destination', request('destination')) }}">
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">
                            <i class="far fa-calendar-alt me-1" style="color: #aa0022;"></i> Departure
                        </label>
                        <input type="date" name="departure_date" class="form-control fw-semibold"
                            value="{{ old('departure_date', request('departure_date')) }}">
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Journey</label>
                        <select name="journey_type" class="form-select fw-semibold">
                            <option value="1" {{ old('journey_type', request('journey_type')) == '1' ? 'selected' : '' }}>One Way</option>
                            <option value="2" {{ old('journey_type', request('journey_type', '2')) == '2' ? 'selected' : '' }}>Round Trip</option>
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-4">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Return</label>
                        <input type="date" name="return_date" class="form-control fw-semibold"
                            value="{{ old('return_date', request('return_date')) }}">
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Adults</label>
                        <select name="adults" class="form-select fw-semibold">
                            @for ($i = 1; $i <= 9; $i++)
                                <option value="{{ $i }}" {{ old('adults', request('adults', 1)) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Children</label>
                        <select name="children" class="form-select fw-semibold">
                            @for ($i = 0; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('children', request('children', 0)) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Infants</label>
                        <select name="infants" class="form-select fw-semibold">
                            @for ($i = 0; $i <= 8; $i++)
                                <option value="{{ $i }}" {{ old('infants', request('infants', 0)) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div class="col-lg-2 col-md-3">
                        <label class="form-label text-muted small fw-bold text-uppercase mb-1">Cabin</label>
                        <select name="cabin" class="form-select fw-semibold">
                            <option value="2" {{ old('cabin', request('cabin', '2')) == '2' ? 'selected' : '' }}>Economy</option>
                            <option value="3" {{ old('cabin', request('cabin')) == '3' ? 'selected' : '' }}>Premium Eco</option>
                            <option value="4" {{ old('cabin', request('cabin')) == '4' ? 'selected' : '' }}>Business</option>
                            <option value="6" {{ old('cabin', request('cabin')) == '6' ? 'selected' : '' }}>First Class</option>
                        </select>
                    </div>

                    <input type="hidden" name="direct_flight" value="0">
                    <input type="hidden" name="one_stop_flight" value="0">

                    <div class="col-lg-2 col-md-12">
                        <button type="submit" class="btn w-100 fw-bold text-white custom-btn-primary"
                            style="height: 41px; border-radius: 6px;">
                            <i class="fas fa-search me-2"></i>Search
                        </button>
                    </div>

                </div>
            </form>
        </div>

        @if(!empty($result['Results'][0]))
            <div class="d-flex justify-content-between align-items-center mb-4 p-3 bg-white rounded shadow-sm border-bottom"
                style="border-color: #f1f1f1 !important; border-radius: 12px !important;">
                <div>
                    <h5 class="fw-bold mb-1 text-dark">Available Flights</h5>
                    <span class="text-muted small">Trace ID: <strong style="color: #aa0022;">{{ $result['TraceId'] ?? 'N/A' }}</strong></span>
                </div>
                <div>
                    <span class="badge bg-white text-dark border p-2 shadow-sm">
                        <i class="fas fa-plane me-1" style="color: #aa0022;"></i> {{ count($result['Results'][0] ?? []) }} Results Found
                    </span>
                </div>
            </div>

            <div id="flightCardsContainer"></div>

            <div class="d-flex justify-content-center my-4">
                <nav>
                    <ul class="pagination" id="uiPaginationLinks"></ul>
                </nav>
            </div>
        @else
            <div class="card border-0 shadow-sm text-center p-5" style="border-radius: 16px; background-color: #ffffff;">
                <div class="my-3">
                    <div class="rounded-circle d-inline-flex align-items-center justify-content-center bg-light mb-3" style="width: 80px; height: 80px;">
                        <i class="fas fa-search-location fs-1 text-muted"></i>
                    </div>
                    <h4 class="fw-bold text-dark mb-2">No Flights Found</h4>
                    <p class="text-muted mx-auto mb-4" style="max-width: 400px;">
                        Please complete or modify your route specifications above to query active flight inventories.
                    </p>
                </div>
            </div>
        @endif

    </div>

    <style>
        .flight-card {
            transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out;
        }
        .flight-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1.5rem rgba(170, 0, 34, 0.08) !important;
        }

        .custom-btn-primary {
            background-color: #aa0022;
            border: 2px solid #aa0022;
            transition: all 0.2s ease-in-out;
        }

        .custom-btn-primary:hover {
            background-color: #ffffff;
            color: #aa0022 !important;
            border-color: #aa0022;
        }

        .form-control:focus,
        .form-select:focus {
            border-color: #aa0022 !important;
            box-shadow: 0 0 0 0.25rem rgba(170, 0, 34, 0.1) !important;
        }

        .form-select, .form-control {
            font-size: 14px;
        }

        .pagination .page-link {
            color: #aa0022;
            border-color: #e9ecef;
            cursor: pointer;
        }
        
        .pagination .page-item.active .page-link {
            background-color: #aa0022;
            border-color: #aa0022;
            color: white;
        }

        @media (min-width: 768px) {
            .border-start-md {
                border-left: 1px dashed #e9ecef !important;
            }
        }
    </style>

    @if(!empty($result['Results'][0]))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // 1. Inject JSON from Controller securely into JavaScript execution memory
            const allFlights = @json($result['Results'][0]);
            const perPage = 10;
            let currentPage = 1;

            function renderFlights(page) {
                const container = document.getElementById('flightCardsContainer');
                container.innerHTML = ""; // Reset current visible block

                const start = (page - 1) * perPage;
                const end = start + perPage;
                const paginatedItems = allFlights.slice(start, end);

                paginatedItems.forEach(flight => {
                    const firstSegment = flight.Segments && flight.Segments[0] ? flight.Segments[0][0] : null;
                    const segments = (flight.Segments && flight.Segments[0]) ? flight.Segments[0] : [];
                    const lastSegment = segments.length ? segments[segments.length - 1] : null;
                    const stops = segments.length - 1;

                    if (!firstSegment || !lastSegment) return;

                    // Duration Calculations
                    const duration = lastSegment.AccumulatedDuration || lastSegment.Duration || 0;
                    const hours = Math.floor(duration / 60);
                    const minutes = duration % 60;

                    // Distance accumulator
                    let distance = 0;
                    segments.forEach(seg => { distance += (seg.Mile || 0); });

                    // Format Times via JS Helper
                    const depTime = new Date(firstSegment.Origin.DepTime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});
                    const arrTime = new Date(lastSegment.Destination.ArrTime).toLocaleTimeString([], {hour: '2-digit', minute:'2-digit', hour12: false});
                    
                    const formattedFare = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(flight.Fare.PublishedFare);
                    const formattedDistance = new Intl.NumberFormat('en-IN', { maximumFractionDigits: 0 }).format(distance);

                    const stopBadge = stops === 0 
                        ? `<span class="text-success">Non-stop</span>` 
                        : `<span class="text-warning">${stops} ${stops === 1 ? 'Stop' : 'Stops'}</span>`;

                    const distanceSpan = distance > 0 ? `<span class="text-black-50 ms-1">(${formattedDistance} KM)</span>` : '';
                    const refundBadge = flight.IsRefundable 
                        ? `<span class="badge bg-success-subtle text-success px-2 py-1" style="font-size: 10px; border: 1px solid rgba(25, 135, 84, 0.2);">Refundable</span>`
                        : `<span class="badge bg-light text-secondary px-2 py-1" style="font-size: 10px; border: 1px solid #e0e0e0;">Non-Refundable</span>`;

                    // Generate UI markup template block dynamically
                    const flightHtml = `
                        <div class="card border-0 shadow-sm mb-3 overflow-hidden flight-card" style="border-radius: 12px; background-color: #ffffff;">
                            <div class="card-body p-4">
                                <div class="row align-items-center gy-3">
                                    <div class="col-md-3 d-flex align-items-center">
                                        <div class="rounded p-2 me-3 d-flex align-items-center justify-content-center" style="width: 48px; height: 48px; background-color: rgba(170, 0, 34, 0.05); border: 1px solid rgba(170, 0, 34, 0.1);">
                                            <i class="fas fa-plane-departure fs-5" style="color: #aa0022;"></i>
                                        </div>
                                        <div>
                                            <h6 class="fw-bold mb-0 text-dark">${firstSegment.Airline.AirlineName}</h6>
                                            <span class="text-muted small fw-bold" style="font-size: 11px;">
                                                ${firstSegment.Airline.AirlineCode}-${firstSegment.Airline.FlightNumber || ''}
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="d-flex align-items-center justify-content-between text-center position-relative px-2">
                                            <div class="text-start" style="min-width: 85px;">
                                                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">${depTime}</h4>
                                                <span class="fw-extrabold text-secondary small">${firstSegment.Origin.Airport.AirportCode}</span>
                                                <div class="small text-muted text-truncate" style="max-width: 100px;">${firstSegment.Origin.Airport.CityName}</div>
                                            </div>
                                            <div class="flex-grow-1 mx-3 position-relative">
                                                <div class="fw-bold mb-1" style="color: #aa0022; font-size: 13px;">${hours}h ${minutes}m</div>
                                                <div class="d-flex align-items-center justify-content-center position-relative my-2" style="height: 2px; background-color: #e9ecef; width: 100%;">
                                                    <i class="fas fa-plane position-absolute" style="color: #aa0022; font-size: 11px; background: #fff; padding: 0 6px; transform: rotate(90deg);"></i>
                                                </div>
                                                <div class="small text-muted fw-semibold" style="font-size: 11px; letter-spacing: 0.3px;">
                                                    ${stopBadge} ${distanceSpan}
                                                </div>
                                            </div>
                                            <div class="text-end" style="min-width: 85px;">
                                                <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">${arrTime}</h4>
                                                <span class="fw-extrabold text-secondary small">${lastSegment.Destination.Airport.AirportCode}</span>
                                                <div class="small text-muted text-truncate" style="max-width: 100px;">${lastSegment.Destination.Airport.CityName}</div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-3 text-md-end border-start-md">
                                        <div class="ps-md-4">
                                            <span class="text-muted small d-block mb-0">Total Fare</span>
                                            <h3 class="fw-extrabold mb-1" style="color: #aa0022; font-size: 1.65rem;">₹${formattedFare}</h3>
                                            <div class="mb-3">${refundBadge}</div>
                                            
                                            <form method="POST" action="{{ route('flight.fareQuote') }}">
                                                @csrf

                                                <input type="hidden" name="trace_id" value="{{ $result['TraceId'] }}">
                                                <input type="hidden" name="result_index" value="${flight.ResultIndex}">
                                                <input type="hidden" name="is_lcc" value="${flight.IsLCC}">

                                                <button class="btn btn-danger w-100">
                                                    Book Now <i class="fas fa-arrow-right ms-1 small"></i>
                                                </button>
                                            </form>
                                            
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    `;
                    container.insertAdjacentHTML('beforeend', flightHtml);
                });
                
                window.scrollTo({ top: 0, behavior: 'smooth' });
                renderPaginationLinks();
            }

            function renderPaginationLinks() {
                const totalPages = Math.ceil(allFlights.length / perPage);
                const paginationUl = document.getElementById('uiPaginationLinks');
                paginationUl.innerHTML = "";

                if (totalPages <= 1) return;

                // Previous Button
                const prevClass = currentPage === 1 ? 'disabled' : '';
                paginationUl.insertAdjacentHTML('beforeend', `
                    <li class="page-item ${prevClass}">
                        <a class="page-link" data-page="${currentPage - 1}">&laquo;</a>
                    </li>
                `);

                // Dynamic Page Number Blocks
                for (let i = 1; i <= totalPages; i++) {
                    if (i === 1 || i === totalPages || (i >= currentPage - 2 && i <= currentPage + 2)) {
                        const activeClass = currentPage === i ? 'active' : '';
                        paginationUl.insertAdjacentHTML('beforeend', `
                            <li class="page-item ${activeClass}">
                                <a class="page-link" data-page="${i}">${i}</a>
                            </li>
                        `);
                    } else if (i === currentPage - 3 || i === currentPage + 3) {
                        paginationUl.insertAdjacentHTML('beforeend', `<li class="page-item disabled"><span class="page-link">...</span></li>`);
                    }
                }

                // Next Button
                const nextClass = currentPage === totalPages ? 'disabled' : '';
                paginationUl.insertAdjacentHTML('beforeend', `
                    <li class="page-item ${nextClass}">
                        <a class="page-link" data-page="${currentPage + 1}">&raquo;</a>
                    </li>
                `);

                // Bind click configurations dynamically
                paginationUl.querySelectorAll('.page-link[data-page]').forEach(link => {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const targetPage = parseInt(this.getAttribute('data-page'));
                        if (targetPage >= 1 && targetPage <= totalPages && targetPage !== currentPage) {
                            currentPage = targetPage;
                            renderFlights(currentPage);
                        }
                    });
                });
            }

            // Initialization trigger
            renderFlights(currentPage);
        });
    </script>
    @endif
@endsection