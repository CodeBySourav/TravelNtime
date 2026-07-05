@extends('layout_inner_page')

@section('front-content')
<!-- Tailwind CSS v4 & Alpine.js -->
<script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

<style>
    [x-cloak] { display: none !important; }
    /* क्लीन, मॉडर्न और ग्लोबल सैन-सेरिफ़ टाइपोग्राफी जो इमेज के टूटे हुए फॉन्ट को बदल देगी */
    body {
        background-color: #f4f6f9;
        font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    }
</style>

<div class="min-h-screen py-8 text-gray-800" x-data="bookingForm()">
    <div class="max-w-6xl mx-auto px-4 sm:px-6">
        
        <!-- Premium Modern Header Area -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 mb-8">
            <div>
                <h1 class="text-2xl font-extrabold tracking-tight text-gray-900 sm:text-3xl">Review & Checkout</h1>
                <p class="text-sm text-gray-500 mt-1">Please enter passenger info exactly as it appears on official travel documents.</p>
            </div>
            
            <!-- Live Expiry Timer Pill -->
            <div class="flex items-center gap-2 bg-red-50 text-[#aa0022] border border-red-100 px-4 py-2 rounded-xl text-xs font-bold shadow-xs">
                <i class="fa-solid fa-stopwatch animate-pulse text-sm"></i>
                <span>Seats Held For: <strong class="font-black">10:00 Mins</strong></span>
            </div>
        </div>

        <form action="{{ route('flight.checkout') }}" method="POST" id="flightBookingForm" @submit.prevent="validateAndSubmit">
            @csrf
            <input type="hidden" name="flight_id" value="{{ $flight->id ?? 'FL-7892' }}">

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
                
                <!-- LEFT SIDE: PASSENGER & CONTACT FORMS -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Form Block 1: Passenger Information -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-md bg-[#aa0022] text-white flex items-center justify-center text-xs font-bold">1</span>
                            <h2 class="font-bold text-gray-900 text-base">Passenger Information</h2>
                            <span class="ml-auto text-xs font-bold text-[#aa0022] bg-red-50/60 px-2 py-1 rounded-sm">Adult 1</span>
                        </div>
                        
                        <div class="p-6 space-y-6">
                            <input type="hidden" name="travellers[0][type]" value="adult">
                            
                            <div class="grid grid-cols-1 sm:grid-cols-12 gap-4">
                                <!-- Title Select -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Title <span class="text-[#aa0022]">*</span></label>
                                    <div class="relative">
                                        <select name="travellers[0][title]" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-medium focus:border-[#aa0022] focus:ring-3 focus:ring-red-100 transition outline-none appearance-none cursor-pointer" required>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Ms.">Ms.</option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>

                                <!-- First Name Input -->
                                <div class="sm:col-span-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">First Name <span class="text-[#aa0022]">*</span></label>
                                    <input type="text" name="travellers[0][first_name]" placeholder="e.g. John" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:border-[#aa0022] focus:ring-3 focus:ring-red-100 transition outline-none" required>
                                </div>

                                <!-- Last Name Input -->
                                <div class="sm:col-span-4">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Last Name <span class="text-[#aa0022]">*</span></label>
                                    <input type="text" name="travellers[0][last_name]" placeholder="e.g. Doe" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:border-[#aa0022] focus:ring-3 focus:ring-red-100 transition outline-none" required>
                                </div>

                                <!-- Gender Select -->
                                <div class="sm:col-span-2">
                                    <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Gender <span class="text-[#aa0022]">*</span></label>
                                    <div class="relative">
                                        <select name="travellers[0][gender]" class="w-full bg-white border border-gray-300 rounded-xl px-3 py-2.5 text-sm font-medium focus:border-[#aa0022] focus:ring-3 focus:ring-red-100 transition outline-none appearance-none cursor-pointer" required>
                                            <option value="male">Male</option>
                                            <option value="female">Female</option>
                                        </select>
                                        <i class="fa-solid fa-chevron-down absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none text-xs"></i>
                                    </div>
                                </div>
                            </div>

                            <!-- Companion/Child Add-on Module -->
                            <div x-data="{ showChild: false }" class="mt-4 pt-4 border-t border-gray-100">
                                <div class="flex justify-between items-center bg-gray-50 p-3 rounded-xl border border-gray-200/60">
                                    <span class="text-xs font-semibold text-gray-600 flex items-center gap-2">
                                        <i class="fa-solid fa-children text-gray-400 text-sm"></i> Travelling with a child or infant?
                                    </span>
                                    <button type="button" @click="showChild = !showChild" class="text-xs font-bold text-[#aa0022] hover:text-[#88001b] transition cursor-pointer">
                                        <span x-show="!showChild"><i class="fa-solid fa-plus mr-1"></i> Add Child Passenger</span>
                                        <span x-show="showChild"><i class="fa-solid fa-minus mr-1"></i> Remove Child</span>
                                    </button>
                                </div>

                                <!-- Collapsible Child Form Fields -->
                                <template x-if="showChild">
    <div class="mt-4 p-4 bg-gray-50/50 rounded-xl border border-gray-200/80 grid grid-cols-1 sm:grid-cols-4 gap-4">

        <input type="hidden" name="travellers[1][type]" value="child">

        <div>
            <label>Title</label>
            <select name="travellers[1][title]">
                <option value="Mstr.">Mstr.</option>
                <option value="Miss.">Miss.</option>
            </select>
        </div>

        <div>
            <label>First Name</label>
            <input
                type="text"
                name="travellers[1][first_name]"
            >
        </div>

        <div>
            <label>Last Name</label>
            <input
                type="text"
                name="travellers[1][last_name]"
            >
        </div>

        <div>
            <label>Gender</label>
            <select name="travellers[1][gender]">
                <option value="male">Male</option>
                <option value="female">Female</option>
            </select>
        </div>

    </div>
</template>
                            </div>
                        </div>
                    </div>

                    <!-- Form Block 2: Contact Notifications -->
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
                        <div class="bg-gray-50 border-b border-gray-100 px-6 py-4 flex items-center gap-3">
                            <span class="w-6 h-6 rounded-md bg-[#aa0022] text-white flex items-center justify-center text-xs font-bold">2</span>
                            <h2 class="font-bold text-gray-900 text-base">Contact Notifications</h2>
                        </div>
                        
                        <div class="p-6 grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Mobile Number <span class="text-[#aa0022]">*</span></label>
                                <div class="flex rounded-xl border border-gray-300 overflow-hidden focus-within:border-[#aa0022] focus-within:ring-3 focus-within:ring-red-100 transition">
                                    <span class="bg-gray-50 text-gray-500 font-bold px-4 flex items-center text-sm border-r border-gray-200">+91</span>
                                    <input type="tel" name="contact[mobile]" x-model="mobile" @blur="validateMobile" placeholder="9876543210" class="w-full bg-white px-4 py-2.5 text-sm font-medium outline-none">
                                </div>
                                <p x-show="mobileError" class="text-[#aa0022] text-xs font-bold mt-2 flex items-center gap-1" x-cloak>
                                    <i class="fa-solid fa-triangle-exclamation"></i> <span x-text="mobileError"></span>
                                </p>
                            </div>

                            <div>
                                <label class="block text-xs font-bold text-gray-500 uppercase tracking-wide mb-1.5">Email Address <span class="text-[#aa0022]">*</span></label>
                                <input type="email" name="contact[email]" x-model="email" @blur="validateEmail" placeholder="yourname@domain.com" class="w-full bg-white border border-gray-300 rounded-xl px-4 py-2.5 text-sm font-medium focus:border-[#aa0022] focus:ring-3 focus:ring-red-100 transition outline-none">
                                <p x-show="emailError" class="text-[#aa0022] text-xs font-bold mt-2 flex items-center gap-1" x-cloak>
                                    <i class="fa-solid fa-triangle-exclamation"></i> <span x-text="emailError"></span>
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Modern Operational Checkbox Agreement -->
                    <div class="flex items-start gap-3 bg-white border border-gray-200/80 p-4 rounded-xl shadow-2xs">
                        <input type="checkbox" id="termsBox" x-model="acceptedTerms" class="mt-0.5 w-4 h-4 rounded-sm accent-[#aa0022] cursor-pointer">
                        <label for="termsBox" class="text-xs text-gray-500 font-medium leading-relaxed select-none cursor-pointer">
                            I accept and agree to the standard airline fare terms, reservation policies, and booking confirmation conditions.
                        </label>
                    </div>
                </div>

                <!-- RIGHT SIDE: PREMIUM FIXED SIDEBAR FOR PAYMENT -->
                <div class="lg:col-span-1 lg:sticky lg:top-6">
                    <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">
                        <div class="bg-[#aa0022] text-white px-5 py-4">
                            <h3 class="text-xs font-black uppercase tracking-widest text-red-100">Fare Summary</h3>
                        </div>
                        
                        <div class="p-5 space-y-4">
                            <div class="flex justify-between items-center text-sm">
                                <span class="text-gray-500 font-medium">Base Fare (1 Adult)</span>
                                <span class="font-bold text-gray-900">₹5,420</span>
                            </div>
                            <div class="flex justify-between items-center text-sm pb-4 border-b border-gray-100 border-dashed">
                                <span class="text-gray-500 font-medium">Taxes & Fees</span>
                                <span class="font-bold text-gray-900">₹830</span>
                            </div>
                            
                            <div class="flex justify-between items-baseline pt-2">
                                <span class="text-sm font-bold text-gray-900">Total Payable</span>
                                <span class="text-3xl font-black text-[#aa0022] tracking-tight">₹6,250</span>
                            </div>
                        </div>

                        <!-- Action Submit Section -->
                        <div class="p-4 bg-gray-50 border-t border-gray-100">
                            <button type="submit" 
                                    :disabled="!acceptedTerms" 
                                    :class="acceptedTerms ? 'bg-[#aa0022] hover:bg-[#88001b] cursor-pointer shadow-md' : 'bg-gray-300 text-gray-500 cursor-not-allowed'" 
                                    class="w-full text-white font-extrabold text-sm py-3.5 px-4 rounded-xl transition duration-150 flex items-center justify-center gap-2 uppercase tracking-wider">
                                <span>Proceed to Payment</span>
                                <i class="fa-solid fa-arrow-right text-xs"></i>
                            </button>
                        </div>
                    </div>
                </div>

            </div>
        </form>
    </div>
</div>

<script>
    function bookingForm() {
        return {
            mobile: '',
            email: '',
            mobileError: '',
            emailError: '',
            acceptedTerms: false,

            validateMobile() {
                const regex = /^[6-9]\d{9}$/;
                if (!this.mobile) {
                    this.mobileError = 'Mobile number is required.';
                } else if (!regex.test(this.mobile)) {
                    this.mobileError = 'Please enter a valid 10-digit number.';
                } else {
                    this.mobileError = '';
                }
            },

            validateEmail() {
                const regex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
                if (!this.email) {
                    this.emailError = 'Email address is required.';
                } else if (!regex.test(this.email)) {
                    this.emailError = 'Please enter a valid email address.';
                } else {
                    this.emailError = '';
                }
            },

            validateAndSubmit() {
                this.validateMobile();
                this.validateEmail();

                if (!this.mobileError && !this.emailError && this.acceptedTerms) {
                    document.getElementById('flightBookingForm').submit();
                } else if (!this.acceptedTerms) {
                    alert('Please acknowledge and accept the terms to proceed.');
                }
            }
        }
    }
</script>
@endsection