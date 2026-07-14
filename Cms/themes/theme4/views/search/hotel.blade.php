<form action="#" method="POST" x-data="{
    hotelRooms: 1,
    hotelAdults: 2,
    hotelChildren: 0,
    openGuests: false
}" @click.away="openGuests = false" class="hotel-search-widget">
    @csrf

    <div class="search-grid">

        {{-- Destination --}}
        <div class="input-card position-relative">

            <span class="input-label">
                <i class="fa fa-map-marker-alt"></i> Destination
            </span>

            <input type="text"
                id="destination"
                placeholder="City / Hotel / Destination"
                autocomplete="off"
                required>

            {{-- This will store DestinationId --}}
            <input type="hidden"
                name="city"
                id="destination_id">

            <div id="destination-list" class="destination-list"></div>

        </div>
        {{-- Check In --}}
        <div class="input-card">
            <span class="input-label"><i class="fa fa-calendar-alt"></i> Check In</span>
            <input type="date" 
                   name="check_in" 
                   value="{{ old('check_in', date('Y-m-d')) }}" 
                   min="{{ date('Y-m-d') }}" 
                   required>
        </div>

        {{-- Check Out --}}
        <div class="input-card">
            <span class="input-label"><i class="fa fa-calendar-alt"></i> Check Out</span>
            <input type="date" 
                   name="check_out" 
                   value="{{ old('check_out', date('Y-m-d', strtotime('+1 day'))) }}" 
                   min="{{ date('Y-m-d') }}" 
                   required>
        </div>

        {{-- Guests & Rooms (Interactive Dropdown) --}}
        <div class="input-card relative cursor-pointer" @click="openGuests = !openGuests">
            <span class="input-label"><i class="fa fa-users"></i> Guests & Rooms</span>
            <div class="display-value">
                <span x-text="hotelRooms">1</span> Room(s)
            </div>
            <div class="sub-displaytext">
                <span x-text="hotelAdults">2</span> Adt, <span x-text="hotelChildren">0</span> Chd
            </div>

            <div class="passenger-dropdown" x-show="openGuests" x-transition @click.stop>
                
                {{-- Rooms --}}
                <div class="passenger-row">
                    <div>
                        <p class="p-title">Rooms</p>
                        <p class="p-subtitle">Maximum 10 rooms</p>
                    </div>
                    <div class="counter-control">
                        <button type="button" @click="if(hotelRooms > 1) hotelRooms--">-</button>
                        <span x-text="hotelRooms"></span>
                        <input type="hidden" name="rooms" :value="hotelRooms">
                        <button type="button" @click="if(hotelRooms < 10) hotelRooms++">+</button>
                    </div>
                </div>

                {{-- Adults --}}
                <div class="passenger-row">
                    <div>
                        <p class="p-title">Adults</p>
                        <p class="p-subtitle">Age 12+</p>
                    </div>
                    <div class="counter-control">
                        <button type="button" @click="if(hotelAdults > 1) hotelAdults--">-</button>
                        <span x-text="hotelAdults"></span>
                        <input type="hidden" name="adults" :value="hotelAdults">
                        <button type="button" @click="if(hotelAdults < 20) hotelAdults++">+</button>
                    </div>
                </div>

                {{-- Children --}}
                <div class="passenger-row">
                    <div>
                        <p class="p-title">Children</p>
                        <p class="p-subtitle">Age 0-11</p>
                    </div>
                    <div class="counter-control">
                        <button type="button" @click="if(hotelChildren > 0) hotelChildren--">-</button>
                        <span x-text="hotelChildren"></span>
                        <input type="hidden" name="children" :value="hotelChildren">
                        <button type="button" @click="if(hotelChildren < 10) hotelChildren++">+</button>
                    </div>
                </div>
                
                <button type="button" class="btn-done" @click="openGuests = false">Done</button>
            </div>
        </div>

        {{-- Nationality --}}
        <div class="input-card">
            <span class="input-label"><i class="fa fa-globe"></i> Nationality</span>
            <select name="nationality" required>
                <option value="IN">India</option>
                <option value="BD">Bangladesh</option>
                <option value="NP">Nepal</option>
                <option value="LK">Sri Lanka</option>
                <option value="AE">United Arab Emirates</option>
                <option value="US">United States</option>
                <option value="GB">United Kingdom</option>
            </select>
        </div>

    </div>

    <div class="search-action-row">
        <button type="submit" class="search-submit-btn">
            <i class="fa fa-search"></i> Search Hotels
        </button>
    </div>
</form>

<style>
:root {
    --primary-color: #aa0022;
    --primary-hover: #88001b;
    --bg-card: #ffffff;
    
    --text-main: #1a1a1a;
    --text-muted: #718096;
    --border-color: #e2e8f0;
    --radius: 12px;
}

.hotel-search-widget {
    background: var(--bg-card);
    padding: 24px;
    border-radius: var(--radius);
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.05);
    border: 1px solid var(--border-color);
    font-family: system-ui, -apple-system, sans-serif;
    max-width: 1200px;
    margin: 0 auto;
}

/* Responsive Grid Layout */
.search-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
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
    transition: border-color 0.2s;
}
.input-card:focus-within {
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
    border: none;
    outline: none;
    font-size: 16px;
    font-weight: 600;
    color: var(--text-main);
    padding: 0;
    width: 100%;
    background: transparent;
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

/* Centered Button Layout Styling */
.search-action-row {
    display: flex;
    justify-content: center; 
    margin-top: 10px;
}
.search-submit-btn {
    background: var(--primary-color);
    color: #ffffff;
    padding: 14px 40px; 
    border-radius: var(--radius);
    border: none;
    font-size: 16px;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
}
.search-submit-btn:hover {
    background: var(--primary-hover);
}
</style>


