@php
    use Modules\Partner\App\Models\Partner;
    $theme4_hero = getContent('theme4_hero.content', true);

    $partners = Partner::latest()->get();

    $theme4_destinations = destinations();

@endphp
@php
    $theme4_tour_package = getContent('theme4_tour_package.content', true);

    $theme4_popular_services = popularServices(20, false);

    // Featured Tours (ONLY 2)
    $featuredPackages = $theme4_popular_services
        ->where('is_featured', 1)
        ->take(2);

    // Domestic (India)
    $domesticPackages = $theme4_popular_services->filter(function ($service) {
        return strtolower(trim($service->country ?? '')) === 'india'
            && $service->service_type_id != 8;
    });

    // International (Non-India)
    $internationalPackages = $theme4_popular_services->filter(function ($service) {
        return strtolower(trim($service->country ?? '')) !== 'india'
            && $service->service_type_id != 8;
    });

    // Visa Services
    $visaServices = $theme4_popular_services->filter(function ($service) {
        return (int) $service->service_type_id === 8;
    });
@endphp

@if ($theme4_hero)
    <!-- tg-hero-area-start -->
    <div class="tg-hero-area tg-hero-tu-2-wrapper include-bg"
        data-background="{{ asset(getSingleImage($theme4_hero, 'background_image')) }}">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="tg-hero-3-content text-center mb-40">
                        <h4 class="tg-hero-3-subtitle wow fadeInUp" data-wow-delay=".4s" data-wow-duration=".6s">
                            {{ getTranslatedValue($theme4_hero, 'sub_title') }}
                        </h4>
                        <h2 class="tg-hero-3-title wow fadeInUp" data-wow-delay=".6s" data-wow-duration=".6s">
                            {{ getTranslatedValue($theme4_hero, 'title') }}
                        </h2>
                    </div>
                </div>
                <div class="col-12">
    <div class="tg-booking-form-item tg-booking-form-3 mb-45">
        <div x-data="{ service: 'flight' }">

            <div class="d-flex justify-content-center mb-30">

                <button type="button"
                        class="btn mx-2"
                        :class="service=='flight' ? 'btn-custom-active' : 'btn-custom-inactive'"
                        @click="service='flight'">
                    ✈ Flight
                </button>

                <button type="button"
                        class="btn mx-2"
                        :class="service=='hotel' ? 'btn-custom-active' : 'btn-custom-inactive'"
                        @click="service='hotel'">
                    🏨 Hotel
                </button>

                <button type="button"
                        class="btn mx-2"
                        :class="service=='tour' ? 'btn-custom-active' : 'btn-custom-inactive'"
                        @click="service='tour'">
                    🌍 Tour
                </button>

                <button type="button"
                        class="btn mx-2"
                        :class="service=='visa' ? 'btn-custom-active' : 'btn-custom-inactive'"
                        @click="service='visa'">
                    🛂 Visa
                </button>

            </div>

            <div x-show="service=='flight'">
                @include('search.flight')
            </div>

            <div x-show="service=='hotel'" x-cloak>
                @include('search.hotel')
            </div>

            <div x-show="service=='tour'" x-cloak>
                @include('search.tour')
            </div>

            <div x-show="service=='visa'" x-cloak>
                @include('search.visa')
            </div>

        </div>
    </div>
</div>
<style>
/* Active Tab Style (Primary Brand Color) */
.btn-custom-active {
    background-color: #aa0022 !important;
    color: #ffffff !important;
    border: 2px solid #aa0022 !important;
    transition: all 0.3s ease;
}

/* Inactive Tab Style (White background with Red border & text) */
.btn-custom-inactive {
    background-color: #ffffff !important;
    color: #aa0022 !important;
    border: 2px solid #aa0022 !important;
    transition: all 0.3s ease;
}

/* Hover Effects */
.btn-custom-active:hover {
    background-color: #88001b !important; /* Slightly darker shade for feedback */
    border-color: #88001b !important;
    color: #ffffff !important;
}

.btn-custom-inactive:hover {
    background-color: #aa0022 !important;
    color: #ffffff !important;
}
</style>
                @if ($partners->count() > 0)
                    <div class="col-12">
                        <div class="tg-brand-wrap">
                            <div class="swiper-container tg-brand-slide fix">
                                <div class="swiper-wrapper slide-transtion">
                                    @foreach ($partners as $key => $partner)
                                        <div class="swiper-slide">
                                            <div class="tg-brand-items">
                                                <a href="{{ $partner?->link ?? '#' }}"><img
                                                        src="{{ $partner?->logo }}" alt="logo">
                                                </a>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
   @if ($featuredPackages->count() > 0)
<section class="upcoming-packages-section pt-100 pb-100"
    style="background: url('https://seocompanygurugram.com/travelntime/uploads/website-images/coclor1.jpeg') center/cover no-repeat;">

    <div class="container">
        <div class="row" style="margin-top: -78px;">

            <!-- LEFT TEXT -->
            <div class="col-lg-4 col-md-5">
                <div class="upcoming-left-content mb-40">
                    <h2 class="featured-title">
                        <span style="color: var(--tg-theme-primary); font-weight: 800; font-size: 45px;">
                            Featured
                        </span><br>
                        <span style="font-size: 40px;">Packages</span>
                    </h2>

                    <p class="mt-20 mb-30">
                        Our most loved and hand-picked tour packages.
                    </p>

                    <a href="{{ url('tour-booking/services') }}" class="tg-btn">
                        View All Tours
                    </a>
                </div>
            </div>

            <!-- FEATURED LIST -->
            <div class="col-lg-8 col-md-7">
                <div class="row">

                    @foreach ($featuredPackages as $service)
                        <div class="col-sm-6 mb-30">
                            <div class="package-card shadow-sm h-100">

                                <div class="package-img">
                                    <img
                                        src="{{ asset('storage/' . $service?->thumbnail?->file_path) }}"
                                        class="img-fluid"
                                        alt="{{ $service?->translation?->title }}">
                                </div>

                                <div class="tg-listing-card-content">
                                <div class="tg-listing-card-duration-tour d-flex align-items-center gap-3">

                                    @if ($service?->duration)
                                        <span class="tg-listing-card-duration-map mb-5">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_16_2737)">
                                                    <path
                                                        d="M7.99979 3.73329V7.99996L10.8442 9.42218M15.1109 8.00003C15.1109 11.9274 11.9271 15.1111 7.99978 15.1111C4.07242 15.1111 0.888672 11.9274 0.888672 8.00003C0.888672 4.07267 4.07242 0.888916 7.99978 0.888916C11.9271 0.888916 15.1109 4.07267 15.1109 8.00003Z"
                                                        stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_165_2737">
                                                        <rect width="16" height="16" fill="white" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                            {{ $service?->duration }}
                                        </span>
                                    @endif

                                    @if ($service?->group_size)
                                        <span class="tg-listing-card-duration-time mb-5">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M1.61756 14.0445C1.40423 14.0445 1.19978 13.9378 1.09312 13.8311L1.00423 13.68C0.950894 13.5822 0.888672 13.4756 0.888672 13.3156C0.888672 12.2222 1.19978 11.0756 1.77756 9.99114C2.31978 9.06669 3.13756 8.22225 4.04423 7.65336C3.68867 7.18225 3.41312 6.59558 3.27089 6.00892C3.20867 5.55558 3.14645 4.72003 3.34201 4.0178C3.53756 3.24447 4.04423 2.55114 4.31978 2.21336C4.71089 1.82225 5.31534 1.32447 5.99089 1.12892C6.47978 0.968916 6.97756 0.888916 7.46645 0.888916H8.01756C8.71978 0.977805 9.37756 1.23558 9.93756 1.63558C10.4798 2.02669 10.8976 2.48892 11.2531 3.11114C11.582 3.68892 11.7509 4.35558 11.7509 5.09336C11.7509 6.05336 11.4487 6.96003 10.8887 7.66225C11.3064 7.9378 11.7331 8.24003 12.1598 8.58669C12.8798 9.30669 13.2887 10.0267 13.6264 10.6934C13.9642 11.5289 14.1153 12.3467 14.1153 13.2267C14.1153 13.44 14.0087 13.6445 13.902 13.7511C13.7953 13.8578 13.5998 13.9645 13.3776 13.9645C13.2976 13.9645 13.182 13.9645 13.0664 13.8756C12.9509 13.84 12.8531 13.7511 12.8265 13.6356L12.6576 13.4667V13.3956C12.6131 13.3067 12.5776 13.2445 12.5776 13.1556C12.5776 12.5422 12.462 11.9467 12.1953 11.2445C11.9731 10.64 11.5909 10.1067 11.0576 9.65336C10.6042 9.28003 10.1776 8.92447 9.68867 8.69336C9.00423 9.10225 8.27534 9.30669 7.46645 9.30669C6.69312 9.30669 5.90201 9.09336 5.24423 8.70225C4.39089 9.10225 3.67978 9.71558 3.19089 10.4889C2.63089 11.3689 2.34645 12.2934 2.34645 13.2356C2.34645 13.4489 2.23978 13.6534 2.13312 13.76C2.07089 13.92 1.85756 14.0445 1.61756 14.0445ZM6.94201 7.84003C7.00423 7.84003 7.11089 7.8578 7.21756 7.88447C7.30645 7.90225 7.38645 7.92003 7.45756 7.92003C7.83978 7.92003 8.20423 7.84003 8.48867 7.6978C9.03089 7.46669 9.39534 7.16447 9.76867 6.64892C10.0531 6.21336 10.2131 5.70669 10.2131 5.17336C10.2131 4.44447 9.92867 3.77781 9.39534 3.24447C8.90645 2.69336 8.28423 2.42669 7.46645 2.42669C6.93312 2.42669 6.41756 2.5778 5.98201 2.87114C5.43089 3.19114 5.13756 3.68003 4.94201 4.07114C4.70201 4.62225 4.65756 5.1378 4.79089 5.68892C4.86201 6.18669 5.13756 6.71114 5.53756 7.11114C5.92867 7.50225 6.45312 7.77781 6.94201 7.84003Z"
                                                    fill="currentColor" />
                                            </svg>
                                            {{ $service?->group_size }}
                                        </span>
                                    @endif
                                </div>

                                <h4 class="tg-listing-card-title mb-10">
                                    <a
                                        href="{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}">
                                        {{ Str::limit($service?->translation?->title, 45) }}
                                    </a>
                                </h4>

                                @if ($service?->location)
                                    <div class="tg-listing-card-duration-tour mb-20">
                                        <span class="tg-listing-card-duration-map">
                                            <svg width="13" height="16" viewBox="0 0 13 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.3329 6.7071C12.3329 11.2324 6.55512 15.1111 6.55512 15.1111C6.55512 15.1111 0.777344 11.2324 0.777344 6.7071C0.777344 5.16402 1.38607 3.68414 2.46962 2.59302C3.55316 1.5019 5.02276 0.888916 6.55512 0.888916C8.08748 0.888916 9.55708 1.5019 10.6406 2.59302C11.7242 3.68414 12.3329 5.16402 12.3329 6.7071Z"
                                                    stroke="currentColor" stroke-width="1.15556" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M6.55512 8.64649C7.61878 8.64649 8.48105 7.7782 8.48105 6.7071C8.48105 5.636 7.61878 4.7677 6.55512 4.7677C5.49146 4.7677 4.6292 5.636 4.6292 6.7071C4.6292 7.7782 5.49146 8.64649 6.55512 8.64649Z"
                                                    stroke="currentColor" stroke-width="1.15556" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            {{ $service?->location }}
                                        </span>
                                    </div>
                                @endif
                                <div class="tg-listing-card-price d-flex align-items-end justify-content-between">
                                     
                                    <button
                                        class="bk-search-button w-100"
                                        onclick="window.location.href='{{ route('front.tourbooking.services.show', ['slug' => $service->slug]) }}'">
                                        Enquire Now
                                    </button>
                                </div>
                            </div>
                            </div>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>
    </div>
</section>
@endif


    @push('style_section')
        <style>
            .tg-brand-items img {
                filter: brightness(0) invert(1);
            }
        </style>
        <style>
        @media (max-width: 767px) {
    .featured-title {
        margin-top: 30px;
    }
}

    .upcoming-packages-section {
        background: #f9f9f9; /* Light background to separate from hero */
    }

    .package-card {
        background: #fff;
        border-radius: 15px;
        overflow: hidden;
        transition: transform 0.3s ease;
        border: 1px solid #eee;
        height: 100%;
    }

    .package-card:hover {
        transform: translateY(-10px);
    }

    .package-img img {
        width: 100%;
        height: 200px;
        object-fit: cover;
    }

    .package-content h4 {
        font-size: 16px;
        font-weight: 700;
        margin-bottom: 15px;
        height: 45px;
        overflow: hidden;
    }

    .price-tag del {
        color: #888;
        font-size: 14px;
    }

    .price-tag strong {
        display: block;
        color: #ff5e14; /* Use your theme color */
        font-size: 18px;
    }

    /* Reusing your button style or creating a variation */
    .package-card .bk-search-button {
        padding: 10px 20px;
        font-size: 14px;
        margin-top: 15px;
    }
</style>
    @endpush


    @push('js_section')
        <script>
            (function($) {
                "use strict"
                $(document).ready(function() {

                    // Initialize timepicker
                    $(".timepicker").flatpickr({
                        enableTime: true,
                        noCalendar: true,
                        dateFormat: "H:i",
                        time_24hr: true
                    });
                });
            })(jQuery);
        </script>

        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 
 <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
 <script>
    $('.service-tab').click(function(){

    let service=$(this).data('service');

    $('.service-tab').removeClass('active');
    $(this).addClass('active');

    if(service=="flight"){
        $('#searchContainer').load('/search/form/flight');
    }

    if(service=="hotel"){
        $('#searchContainer').load('/search/form/hotel');
    }

    if(service=="tour"){
        $('#searchContainer').load('/search/form/tour');
    }

    if(service=="visa"){
        $('#searchContainer').load('/search/form/visa');
    }

});
</script>
    @endpush
@endif
