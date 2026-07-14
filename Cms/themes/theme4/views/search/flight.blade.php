 
<!-- Brand Theme Widget Styling -->
<style>
:root {
    --primary-color: #aa0022;
    --primary-hover: #88001b;
    --bg-card: #ffffff;
    --text-main: #0f172a;
    --text-muted: #64748b;
    --border-color: #e2e8f0;
    --radius: 12px;
}

body {
    background-color: #f8fafc;
}

.flight-search-widget {
    background: var(--bg-card);
    padding: 24px;
    border-radius: var(--radius);
    box-shadow: 0 4px 20px rgba(170, 0, 34, 0.06);
    border: 1px solid var(--border-color);
    font-family: system-ui, -apple-system, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
}

/* Tabs Styling */
.journey-type-tabs {
    display: flex;
    gap: 8px;
    margin-bottom: 20px;
}
.journey-type-tabs label {
    padding: 8px 16px;
    border-radius: 20px;
    cursor: pointer;
    font-size: 14px;
    font-weight: 500;
    color: var(--text-muted);
    transition: all 0.2s ease;
    border: 1px solid transparent;
}
.journey-type-tabs label.active {
    background: #fdf0f2;
    color: var(--primary-color);
    font-weight: 600;
}
.sr-only {
    position: absolute; width: 1px; height: 1px; padding: 0; margin: -1px; overflow: hidden; clip: rect(0,0,0,0); border: 0;
}

/* Responsive Grid Layout */
.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}

@media(max-width: 768px) {
    .search-grid {
        grid-template-columns: 1fr;
    }
}

/* Unified Container Box */
.input-card {
    border: 1px solid var(--border-color);
    border-radius: var(--radius);
    padding: 12px 16px;
    display: flex;
    flex-direction: column;
    position: relative;
    background: #ffffff;
    transition: border-color 0.2s, background-color 0.2s;
}
.input-card:hover {
    border-color: var(--primary-color);
    background-color: #fffbfb;
}
.input-card:focus-within {
    border-color: var(--primary-color);
    background-color: #fffbfb;
}
.input-card.has-error {
    border-color: var(--primary-color);
}

.input-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    color: var(--text-muted);
    margin-bottom: 6px;
    font-weight: 600;
}
.input-label i {
    margin-right: 4px;
    color: var(--primary-color);
}

.input-card input, 
.input-card select,
.display-value {
    border: none !important;
    outline: none !important;
    font-size: 18px;
    font-weight: bold;
    color: var(--text-main);
    padding: 0 !important;
    width: 100%;
    background: transparent !important;
    box-shadow: none !important;
}
.sub-displaytext {
    font-size: 12px;
    color: var(--text-muted);
    margin-top: 2px;
}

/* Custom Interactive Dropdown */
.passenger-dropdown {
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    right: 0;
    min-width: 280px;
    background: #ffffff;
    border-radius: var(--radius);
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.15);
    border: 1px solid var(--border-color);
    padding: 16px;
    z-index: 50;
}
.passenger-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 12px 0;
    border-bottom: 1px solid #edf2f7;
}
.passenger-row:last-of-type { border-bottom: none; }
.p-title { font-size: 14px; font-weight: 600; margin: 0; color: var(--text-main); }
.p-subtitle { font-size: 11px; color: var(--text-muted); margin: 0; }

.counter-control {
    display: flex;
    align-items: center;
    gap: 12px;
}
.counter-control button {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    border: 1px solid var(--primary-color);
    background: transparent;
    color: var(--primary-color);
    font-size: 18px;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s;
}
.counter-control button:hover {
    background: var(--primary-color);
    color: #ffffff;
}
.counter-control span {
    font-weight: 600;
    width: 16px;
    text-align: center;
}
.btn-done {
    width: 100%;
    margin-top: 12px;
    background: #edf2f7;
    border: none;
    padding: 8px;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: background 0.2s;
}
.btn-done:hover {
    background: #e2e8f0;
}

/* Autocomplete Suggestion List Theming */
.airport-autocomplete-list {
    max-height: 300px;
    overflow-y: auto;
    border: 1px solid var(--border-color);
    border-radius: 8px;
    background: #ffffff;
    position: absolute;
    top: calc(100% + 8px);
    left: 0;
    width: 100%;
    z-index: 9999;
    box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1);
}
.airport-item {
    cursor: pointer;
    display: block;
    padding: 10px 14px;
    color: var(--text-main);
    text-decoration: none;
    border-bottom: 1px solid #f1f5f9;
    transition: background 0.15s ease;
}
.airport-item:last-child {
    border-bottom: none;
}
.airport-item:hover {
    background: #fff5f6;
    color: var(--text-main);
}
.airport-item strong {
    color: var(--primary-color);
}

/* Action Rows & Extra filters */
.search-action-row {
    display: flex;
    justify-content: space-between;
    align-items: center;
    flex-wrap: wrap;
    gap: 16px;
    margin-top: 10px;
}
.custom-chk .form-check-input:checked {
    background-color: var(--primary-color);
    border-color: var(--primary-color);
}
.custom-chk label {
    font-size: 14px;
    color: #475569;
    font-weight: 500;
    cursor: pointer;
}
.search-submit-btn {
    background: var(--primary-color);
    color: #ffffff;
    padding: 14px 40px; 
    border-radius: 30px;
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s, transform 0.1s;
    box-shadow: 0 4px 12px rgba(170, 0, 34, 0.2);
}
.search-submit-btn:hover {
    background: var(--primary-hover);
}
.search-submit-btn:active {
    transform: scale(0.98);
}
.error-msg {
    font-size: 11px;
    color: var(--primary-color);
    margin-top: 4px;
    font-weight: 600;
}
</style>

@if(session('error'))
    <div class="alert alert-danger mx-3 mt-3">
        {{ session('error') }}
    </div>
@endif

<div class="container py-5">
    <form action="{{ route('flight.search') }}" method="POST" x-data="{
        journeyType: '1',
        adult: 1,
        child: 0,
        infant: 0,
        openPassenger: false
    }" @click.away="openPassenger = false" class="flight-search-widget">
        @csrf

        <!-- Trip Switchers -->
        <div class="journey-type-tabs">
            <label :class="journeyType === '1' ? 'active' : ''">
                <input type="radio" name="journey_type" value="1" x-model="journeyType" class="sr-only">
                <i class="fa fa-arrow-right"></i> One Way
            </label>
            <label :class="journeyType === '2' ? 'active' : ''">
                <input type="radio" name="journey_type" value="2" x-model="journeyType" class="sr-only">
                <i class="fa fa-exchange-alt"></i> Round Trip
            </label>
        </div>

        <!-- Inputs Search Form Row Grid -->
        <div class="search-grid">
            
            {{-- Origin Input Box --}}
            <div class="input-card {{ $errors->has('origin') ? 'has-error' : '' }}">
                <span class="input-label"><i class="fa fa-plane-departure"></i> From</span>
                <input type="text" id="origin_search" placeholder="City or Airport" autocomplete="off" required>
                <input type="hidden" name="origin" id="origin" value="{{ old('origin') }}">
                <div id="origin_list" class="airport-autocomplete-list"></div>
                @error('origin')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Destination Input Box --}}
            <div class="input-card {{ $errors->has('destination') ? 'has-error' : '' }}">
                <span class="input-label"><i class="fa fa-plane-arrival"></i> To</span>
                <input type="text" id="destination_search" placeholder="City or Airport" autocomplete="off" required>
                <input type="hidden" name="destination" id="destination" value="{{ old('destination') }}">
                <div id="destination_list" class="airport-autocomplete-list"></div>
                @error('destination')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Departure Date Picker --}}
            <div class="input-card {{ $errors->has('departure_date') ? 'has-error' : '' }}">
                <span class="input-label"><i class="fa fa-calendar-alt"></i> Departure</span>
                <input type="date" 
                       name="departure_date" 
                       value="{{ old('departure_date', date('Y-m-d')) }}" 
                       min="{{ date('Y-m-d') }}" 
                       required>
                @error('departure_date')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Return Date Picker --}}
            <div class="input-card {{ $errors->has('return_date') ? 'has-error' : '' }}" 
                 x-show="journeyType === '2'"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 transform scale-95"
                 x-transition:enter-end="opacity-100 transform scale-100"
                 x-transition:leave="transition ease-in duration-150"
                 x-transition:leave-start="opacity-100 transform scale-100"
                 x-transition:leave-end="opacity-0 transform scale-95">
                <span class="input-label"><i class="fa fa-calendar-alt"></i> Return</span>
                <input type="date" 
                       name="return_date" 
                       :required="journeyType === '2'" 
                       min="{{ date('Y-m-d') }}" 
                       value="{{ old('return_date') }}">
                @error('return_date')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

            {{-- Travellers Dropdown Selector Container --}}
            <div class="input-card relative cursor-pointer" @click="openPassenger = !openPassenger">
                <span class="input-label"><i class="fa fa-users"></i> Travellers</span>
                <div class="display-value">
                    <span x-text="adult + child + infant">1</span> Traveler(s)
                </div>
                <div class="sub-displaytext">
                    <span x-text="adult">1</span> Adt, <span x-text="child">0</span> Chd, <span x-text="infant">0</span> Inf
                </div>

                <div class="passenger-dropdown" x-show="openPassenger" x-transition @click.stop>
                    <div class="passenger-row">
                        <div>
                            <p class="p-title">Adults</p>
                            <p class="p-subtitle">Age 12+</p>
                        </div>
                        <div class="counter-control">
                            <button type="button" @click="if(adult > 1) adult--">-</button>
                            <span x-text="adult"></span>
                            <input type="hidden" name="adult" :value="adult">
                            <button type="button" @click="if(adult < 9) adult++">+</button>
                        </div>
                    </div>

                    <div class="passenger-row">
                        <div>
                            <p class="p-title">Children</p>
                            <p class="p-subtitle">Age 2-11</p>
                        </div>
                        <div class="counter-control">
                            <button type="button" @click="if(child > 0) child--">-</button>
                            <span x-text="child"></span>
                            <input type="hidden" name="child" :value="child">
                            <button type="button" @click="if(child < 8) child++">+</button>
                        </div>
                    </div>

                    <div class="passenger-row">
                        <div>
                            <p class="p-title">Infants</p>
                            <p class="p-subtitle">Under 2</p>
                        </div>
                        <div class="counter-control">
                            <button type="button" @click="if(infant > 0) infant--">-</button>
                            <span x-text="infant"></span>
                            <input type="hidden" name="infant" :value="infant">
                            <button type="button" @click="if(infant < adult) infant++">+</button>
                        </div>
                    </div>
                    
                    <button type="button" class="btn-done" @click="openPassenger = false">Done</button>
                </div>
            </div>

            {{-- Cabin Class Menu Options --}}
            <div class="input-card {{ $errors->has('cabin_class') ? 'has-error' : '' }}">
                <span class="input-label"><i class="fa fa-couch"></i> Cabin Class</span>
                <select name="cabin_class" required>
                    <option value="1" {{ old('cabin_class')=='1' ? 'selected' : '' }}>All Classes</option>
                    <option value="2" {{ old('cabin_class')=='2' || !old('cabin_class') ? 'selected' : '' }}>Economy</option>
                    <option value="3" {{ old('cabin_class')=='3' ? 'selected' : '' }}>Premium Economy</option>
                    <option value="4" {{ old('cabin_class')=='4' ? 'selected' : '' }}>Business</option>
                    <option value="5" {{ old('cabin_class')=='5' ? 'selected' : '' }}>First Class</option>
                </select>
                @error('cabin_class')
                    <span class="error-msg">{{ $message }}</span>
                @enderror
            </div>

        
        </div>

        <!-- Preference Filters Row & Main CTA Button Element -->
        <div class="search-action-row custom-chk">
             
            <div>
                <button type="submit" class="search-submit-btn">
                    <i class="fa fa-search"></i> Search Flights
                </button>
            </div>
        </div>
    </form>
</div>

<!-- jQuery Script Autocomplete Integration Module -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
function airportSearch(inputId, hiddenId, listId) {
    $('#' + inputId).on('keyup', function () {
        let keyword = $(this).val();

        if (keyword.length < 2) {
            $('#' + listId).html('');
            return;
        }

        $.get("{{ route('airports.search') }}", { q: keyword }, function (data) {
            let html = '';
            $.each(data, function (i, airport) {
                html += `
                    <a href="#"
                       class="airport-item"
                       data-code="${airport.airport_code}"
                       data-name="${airport.city_name} (${airport.airport_code}) - ${airport.airport_name}">
                        <strong>${airport.city_name}</strong> (${airport.airport_code})<br>
                        <small style="color: #64748b;">${airport.airport_name}</small>
                    </a>
                `;
            });
            $('#' + listId).html(html);
        });
    });

    $(document).on('click', '#' + listId + ' .airport-item', function (e) {
        e.preventDefault();
        $('#' + inputId).val($(this).data('name'));
        $('#' + hiddenId).val($(this).data('code'));
        $('#' + listId).html('');
    });
}

// Instantiate searches targeting correct components
airportSearch('origin_search', 'origin', 'origin_list');
airportSearch('destination_search', 'destination', 'destination_list');
</script>
 