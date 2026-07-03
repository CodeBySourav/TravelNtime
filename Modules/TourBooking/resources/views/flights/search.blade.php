@extends('layout_inner_page')

@section('front-content')
<div class="container py-5" style="background-color: #fcfcfc; min-height: 100vh;">

    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-11">

            @if(session('error'))
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #aa0022 !important;">
                    <i class="fas fa-exclamation-circle me-2" style="color: #aa0022;"></i> {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-left: 4px solid #aa0022 !important;">
                    <div class="fw-bold mb-1"><i class="fas fa-exclamation-triangle me-2" style="color: #aa0022;"></i> Please fix the errors below:</div>
                    <ul class="mb-0 sm-text ps-3">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="card border-0 shadow-sm p-4 p-md-5" style="border-radius: 20px; background-color: #ffffff;">
                
                <div class="d-flex align-items-center mb-4">
                    <div class="rounded-circle d-flex align-items-center justify-content-center me-3" 
                         style="width: 45px; height: 45px; background-color: rgba(170, 0, 34, 0.05);">
                        <i class="fas fa-plane fs-5" style="color: #aa0022;"></i>
                    </div>
                    <div>
                        <h4 class="fw-bold mb-0 text-dark" style="letter-spacing: -0.5px;">Book Flight Tickets</h4>
                        <p class="text-muted small mb-0">Get the best rates on domestic and international sectors</p>
                    </div>
                </div>

                <form action="{{ route('flight.search') }}" method="POST">
                    @csrf

                    <div class="row g-3 align-items-end mb-4 position-relative">

                        <div class="col-lg-3 col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">
                                <i class="fas fa-plane-departure me-1" style="color: #aa0022;"></i> From
                            </label>
                            <div class="input-group">
                                <span class="input-group-text bg-white border-end-0"><i class="fas fa-map-marker-alt text-muted"></i></span>
                                <input type="text" name="origin" class="form-control border-start-0 ps-0 fw-semibold"
                                    placeholder="City or Airport" value="{{ old('origin','DEL') }}">
                            </div>
                        </div>

                        <div class="col-auto d-none d-lg-block px-0 text-center position-relative" style="margin-bottom: 6px; z-index: 5;">
                            <button type="button" id="btn-swap-locations"
                                class="btn btn-light rounded-circle shadow-sm border border-2 border-white"
                                style="width: 36px; height: 36px; padding: 0; margin-left: -18px; margin-right: -18px; transition: transform 0.2s;">
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
                                    placeholder="City or Airport" value="{{ old('destination','BOM') }}">
                            </div>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">
                                <i class="far fa-calendar-alt me-1" style="color: #aa0022;"></i> Departure Date
                            </label>
                            <input type="date" name="departure_date" class="form-control fw-semibold"
                                value="{{ old('departure_date','2026-07-20') }}">
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Journey Type</label>
                            <select name="journey_type" class="form-select fw-semibold">
                                <option value="1" {{ old('journey_type') == '1' ? 'selected' : '' }}>One Way</option>
                                <option value="2" {{ old('journey_type', '2') == '2' ? 'selected' : 'selected' }}>Round Trip / Return</option>
                                <option value="3" {{ old('journey_type') == '3' ? 'selected' : '' }}>Multi Stop</option>
                                <option value="4" {{ old('journey_type') == '4' ? 'selected' : '' }}>Advance Search</option>
                                <option value="5" {{ old('journey_type') == '5' ? 'selected' : '' }}>Special Return</option>
                            </select>
                        </div>

                        <div class="col-lg-2 col-md-4">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Return Date</label>
                            <input type="date" name="return_date" class="form-control fw-semibold"
                                value="{{ old('return_date','2026-07-25') }}">
                        </div>

                    </div>

                    <div class="row g-3 align-items-end mb-4 bg-light p-3 rounded-3 mx-0">
                        
                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Adults (12y+)</label>
                            <select name="adults" class="form-select fw-semibold">
                                @for ($i = 1; $i <= 9; $i++)
                                    <option value="{{ $i }}" {{ old('adults', 1) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Children (2y-12y)</label>
                            <select name="children" class="form-select fw-semibold">
                                @for ($i = 0; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('children', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Infants (under 2y)</label>
                            <select name="infants" class="form-select fw-semibold">
                                @for ($i = 0; $i <= 8; $i++)
                                    <option value="{{ $i }}" {{ old('infants', 0) == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Cabin Class</label>
                            <select name="cabin" class="form-select fw-semibold">
                                <option value="1" {{ old('cabin') == '1' ? 'selected' : '' }}>All Classes</option>
                                <option value="2" {{ old('cabin', '2') == '2' ? 'selected' : '' }}>Economy</option>
                                <option value="3" {{ old('cabin') == '3' ? 'selected' : '' }}>Premium Economy</option>
                                <option value="4" {{ old('cabin') == '4' ? 'selected' : '' }}>Business</option>
                                <option value="5" {{ old('cabin') == '5' ? 'selected' : '' }}>Premium Business</option>
                                <option value="6" {{ old('cabin') == '6' ? 'selected' : '' }}>First Class</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Direct Flight</label>
                            <select name="direct_flight" class="form-select fw-semibold">
                                <option value="0" {{ old('direct_flight', '0') == '0' ? 'selected' : '' }}>No Preference</option>
                                <option value="1" {{ old('direct_flight') == '1' ? 'selected' : '' }}>Yes Only</option>
                            </select>
                        </div>

                        <div class="col-xl-2 col-md-4 col-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Max 1 Stop</label>
                            <select name="one_stop_flight" class="form-select fw-semibold">
                                <option value="0" {{ old('one_stop_flight', '0') == '0' ? 'selected' : '' }}>No Preference</option>
                                <option value="1" {{ old('one_stop_flight') == '1' ? 'selected' : '' }}>Yes Only</option>
                            </select>
                        </div>

                    </div>

                    <div class="row g-3 mb-4">
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Preferred Airlines (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fas fa-bookmark"></i></span>
                                <input type="text" name="preferred_airlines[]" class="form-control border-start-0 ps-0"
                                    placeholder="e.g. AI, 6E, SG (Comma Separated Codes)">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label text-muted small fw-bold text-uppercase mb-1">Distribution Sources (Optional)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-white text-muted"><i class="fas fa-database"></i></span>
                                <input type="text" name="sources[]" class="form-control border-start-0 ps-0"
                                    placeholder="e.g. GDS, NDC Core Engine API">
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-md-end justify-content-stretch pt-2">
                        <button type="submit" class="btn fw-bold text-white custom-btn-primary px-5 py-2-5 w-100 w-md-auto"
                            style="height: 48px; border-radius: 24px; font-size: 15px;">
                            <i class="fas fa-search me-2"></i>Search Flights
                        </button>
                    </div>

                </form>

            </div>

        </div>
    </div>

</div>

<style>
    .custom-btn-primary {
        background-color: #aa0022;
        border: 2px solid #aa0022;
        transition: all 0.2s ease-in-out;
        box-shadow: 0 4px 12px rgba(170, 0, 34, 0.15);
    }

    .custom-btn-primary:hover {
        background-color: #ffffff;
        color: #aa0022 !important;
        border-color: #aa0022;
        box-shadow: 0 4px 16px rgba(170, 0, 34, 0.25);
    }

    .form-control:focus,
    .form-select:focus {
        border-color: #aa0022 !important;
        box-shadow: 0 0 0 0.25rem rgba(170, 0, 34, 0.1) !important;
    }

    .form-select, .form-control {
        font-size: 14px;
        height: 42px;
        border-radius: 8px;
    }
    
    .input-group-text {
        border-radius: 8px 0 0 8px;
    }

    #btn-swap-locations:hover {
        transform: rotate(180deg);
        background-color: #f8f9fa;
    }

    @media(max-width: 767.98px) {
        .w-md-auto {
            width: 100% !important;
        }
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const swapBtn = document.getElementById('btn-swap-locations');
        if(swapBtn) {
            swapBtn.addEventListener('click', function() {
                const originInput = document.querySelector('input[name="origin"]');
                const destInput = document.querySelector('input[name="destination"]');
                if(originInput && destInput) {
                    const temp = originInput.value;
                    originInput.value = destInput.value;
                    destInput.value = temp;
                }
            });
        }
    });
</script>
@endsection