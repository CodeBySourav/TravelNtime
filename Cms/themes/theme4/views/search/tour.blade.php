<form action="#" method="POST" class="tg-tour-widget">
    @csrf

    <div class="tg-tour-grid">
        {{-- Destination --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-map-marker-alt"></i> Destination</span>
            <input type="text" name="destination" placeholder="Where to?" required>
        </div>

        {{-- Travel Date --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-calendar-alt"></i> Date</span>
            <input type="date" name="travel_date" required>
        </div>

        {{-- Duration --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-clock"></i> Duration</span>
            <select name="duration">
                <option value="">Any</option>
                <option value="1-3">1 - 3 Days</option>
                <option value="4-6">4 - 6 Days</option>
                <option value="7-10">7 - 10 Days</option>
            </select>
        </div>

        {{-- Adults --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-user"></i> Adults</span>
            <select name="adults">
                @for($i=1;$i<=10;$i++)
                    <option value="{{ $i }}">{{ $i }} Adult</option>
                @endfor
            </select>
        </div>

        {{-- Children --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-child"></i> Children</span>
            <select name="children">
                @for($i=0;$i<=6;$i++)
                    <option value="{{ $i }}">{{ $i }} Child</option>
                @endfor
            </select>
        </div>

        {{-- Tour Type --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-hiking"></i> Type</span>
            <select name="tour_type">
                <option value="">Any Type</option>
                <option value="family">Family</option>
                <option value="honeymoon">Honeymoon</option>
                <option value="adventure">Adventure</option>
            </select>
        </div>

        {{-- Budget --}}
        <div class="tg-tour-input">
            <span class="tg-tour-label"><i class="fa fa-wallet"></i> Budget</span>
            <select name="budget">
                <option value="">Any Budget</option>
                <option value="10000">Below ₹10k</option>
                <option value="25000">₹10k - ₹25k</option>
            </select>
        </div>

        {{-- Search Button --}}
        <div class="tg-tour-submit-wrapper">
            <button class="tg-tour-submit-btn" type="submit">
                <i class="fa fa-search"></i> Search Tours
            </button>
        </div>
    </div>
</form>

<style>
/* Scoped Styles for Tour Widget Only */
.tg-tour-widget {
    background: #ffffff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.tg-tour-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    align-items: flex-end;
}

@media(max-width: 992px) { .tg-tour-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 576px) { .tg-tour-grid { grid-template-columns: 1fr; } }

.tg-tour-input { display: flex; flex-direction: column; }
.tg-tour-label {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    color: #718096;
    margin-bottom: 6px;
    display: block;
}
.tg-tour-label i { color: #aa0022; margin-right: 4px; }

.tg-tour-input input, 
.tg-tour-input select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    color: #1a1a1a;
    background: #fcfcfc;
    transition: border 0.2s;
    width: 100%;
}

.tg-tour-input input:focus, 
.tg-tour-input select:focus {
    outline: none;
    border-color: #aa0022;
}

.tg-tour-submit-wrapper { display: flex; align-items: center; }

.tg-tour-submit-btn {
    background: #aa0022;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    width: 100%;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    height: 44px;
}
.tg-tour-submit-btn:hover { background: #88001b; }
</style>