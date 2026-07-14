<form action="#" method="POST" class="tg-visa-widget">
    @csrf

    <div class="tg-visa-grid">
        {{-- From Country --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-plane-departure"></i> From Country</span>
            <select name="from_country" required>
                <option value="">Select Country</option>
                <option value="IN">India</option>
                <option value="BD">Bangladesh</option>
                <option value="NP">Nepal</option>
                <option value="LK">Sri Lanka</option>
                <option value="AE">United Arab Emirates</option>
                <option value="US">United States</option>
                <option value="GB">United Kingdom</option>
            </select>
        </div>

        {{-- Destination Country --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-plane-arrival"></i> Destination</span>
            <select name="to_country" required>
                <option value="">Select Country</option>
                <option value="AE">United Arab Emirates</option>
                <option value="US">United States</option>
                <option value="GB">United Kingdom</option>
                <option value="CA">Canada</option>
                <option value="AU">Australia</option>
                <option value="SG">Singapore</option>
                <option value="TH">Thailand</option>
                <option value="MY">Malaysia</option>
                <option value="JP">Japan</option>
                <option value="KR">South Korea</option>
                <option value="FR">France</option>
                <option value="DE">Germany</option>
            </select>
        </div>

        {{-- Nationality --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-id-card"></i> Nationality</span>
            <select name="nationality" required>
                <option value="">Select Nationality</option>
                <option value="Indian">Indian</option>
                <option value="Bangladeshi">Bangladeshi</option>
                <option value="Nepalese">Nepalese</option>
                <option value="Sri Lankan">Sri Lankan</option>
                <option value="Pakistani">Pakistani</option>
                <option value="Other">Other</option>
            </select>
        </div>

        {{-- Visa Type --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-passport"></i> Visa Type</span>
            <select name="visa_type" required>
                <option value="">Select Visa Type</option>
                <option value="tourist">Tourist Visa</option>
                <option value="business">Business Visa</option>
                <option value="student">Student Visa</option>
                <option value="work">Work Visa</option>
                <option value="medical">Medical Visa</option>
                <option value="transit">Transit Visa</option>
                <option value="family">Family Visit Visa</option>
            </select>
        </div>

        {{-- Travel Date --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-calendar-alt"></i> Travel Date</span>
            <input type="date" name="travel_date" required>
        </div>

        {{-- Processing Type --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-bolt"></i> Processing</span>
            <select name="processing_type">
                <option value="normal">Normal</option>
                <option value="urgent">Urgent</option>
                <option value="express">Express</option>
            </select>
        </div>

        {{-- Number of Travellers --}}
        <div class="tg-visa-input">
            <span class="tg-visa-label"><i class="fa fa-users"></i> Travellers</span>
            <select name="travellers">
                @for($i = 1; $i <= 10; $i++)
                    <option value="{{ $i }}">{{ $i }} Traveller{{ $i > 1 ? 's' : '' }}</option>
                @endfor
            </select>
        </div>

        {{-- Search Button --}}
        <div class="tg-visa-submit-wrapper">
            <button class="tg-visa-submit-btn" type="submit">
                <i class="fa fa-search"></i> Search Visa
            </button>
        </div>
    </div>
</form>

<style>
/* Scoped Styles for Visa Widget Only */
.tg-visa-widget {
    background: #ffffff;
    padding: 24px;
    border-radius: 12px;
    box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05);
    border: 1px solid #e2e8f0;
}

.tg-visa-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 15px;
    align-items: flex-end;
}

@media(max-width: 992px) { .tg-visa-grid { grid-template-columns: repeat(2, 1fr); } }
@media(max-width: 576px) { .tg-visa-grid { grid-template-columns: 1fr; } }

.tg-visa-input { display: flex; flex-direction: column; }
.tg-visa-label {
    font-size: 11px;
    text-transform: uppercase;
    font-weight: 700;
    color: #718096;
    margin-bottom: 6px;
    display: block;
}
.tg-visa-label i { color: #aa0022; margin-right: 4px; }

.tg-visa-input input, 
.tg-visa-input select {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    color: #1a1a1a;
    background: #fcfcfc;
    transition: border 0.2s;
    width: 100%;
}

.tg-visa-input input:focus, 
.tg-visa-input select:focus {
    outline: none;
    border-color: #aa0022;
}

.tg-visa-submit-wrapper { display: flex; align-items: center; }

.tg-visa-submit-btn {
    background: #aa0022;
    color: #ffffff;
    border: none;
    border-radius: 8px;
    padding: 10px 20px;
    width: 100%;
    font-weight: 700;
    cursor: pointer;
    transition: background 0.2s;
    height: 44px; /* Matches perfectly with input fields */
}
.tg-visa-submit-btn:hover { background: #88001b; }
</style>