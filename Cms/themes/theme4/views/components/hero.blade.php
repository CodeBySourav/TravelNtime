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
                        <form x-data="bookingForm()" @submit.prevent="submitForm">
                            <div class="tg-booking-form-input-group d-flex align-items-end justify-content-between">
                                <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15">
                                    <span class="tg-booking-form-title mb-5">{{ __('translate.Destinations:') }}</span>
                                    <div class="tg-booking-add-input-field tg-booking-quantity-toggle">

                                        <span x-show="destination" x-text="destination" class="tg-booking-title-value">
                                            {{ __('translate.Where are you going . . .') }}
                                        </span>
                                        <span x-show="!destination" class="tg-booking-title-value">
                                            {{ __('translate.Where are you going . . .') }}
                                        </span>

                                        <span class="location">
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
                                        </span>
                                    </div>
                                    <div class="tg-booking-form-location-list tg-booking-quantity-active">
                                        <ul class="scrool-bar scrool-height pr-5">
                                            @foreach ($theme4_destinations as $key => $destination)
                                                <li
                                                    @click="selectDestination(`{{ $destination->id }}`, `{{ $destination->name }}`)">
                                                    <i class="fa-regular fa-location-dot"></i>
                                                    <span>{{ $destination->name }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                                <div class="tg-booking-form-parent-inner mr-15 mb-15">
                                    <span class="tg-booking-form-title mb-5">{{ __('translate.Check in:') }}</span>
                                    <div class="tg-booking-add-input-date p-relative">
                                        <input x-model="check_in" class="input timepicker" name="datetime-local"
                                            type="text" placeholder="12.00">
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.76501 0.777771V3.26668M4.23413 0.777771V3.26668M0.777344 5.75548H13.2218M2.16006 2.02211H11.8391C12.6027 2.02211 13.2218 2.57927 13.2218 3.26656V11.9778C13.2218 12.6651 12.6027 13.2222 11.8391 13.2222H2.16006C1.39641 13.2222 0.777344 12.6651 0.777344 11.9778V3.26656C0.777344 2.57927 1.39641 2.02211 2.16006 2.02211Z"
                                                    stroke="#737374" stroke-width="0.977778" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="tg-booking-form-parent-inner mr-15 mb-15">
                                    <span class="tg-booking-form-title mb-5">{{ __('translate.Check Out:') }}</span>
                                    <div class="tg-booking-add-input-date p-relative">
                                        <input x-model="check_out" class="input timepicker" name="datetime-local"
                                            type="text" placeholder="12.10">
                                        <span>
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.76501 0.777771V3.26668M4.23413 0.777771V3.26668M0.777344 5.75548H13.2218M2.16006 2.02211H11.8391C12.6027 2.02211 13.2218 2.57927 13.2218 3.26656V11.9778C13.2218 12.6651 12.6027 13.2222 11.8391 13.2222H2.16006C1.39641 13.2222 0.777344 12.6651 0.777344 11.9778V3.26656C0.777344 2.57927 1.39641 2.02211 2.16006 2.02211Z"
                                                    stroke="#737374" stroke-width="0.977778" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                    </div>
                                </div>
                                <div class="tg-booking-form-parent-inner tg-hero-quantity p-relative mr-15 mb-15">
                                    <span class="tg-booking-form-title mb-5">{{ __('translate.Guest:') }}</span>
                                    <div class="tg-booking-add-input-field tg-booking-quantity-toggle">
                                        <div>
                                            <!-- Show this when no values are selected -->
                                            <span x-show="!rooms && !adults && !children"
                                                class="tg-booking-title-value">
                                                {{ __('translate.+ Add Guests') }}
                                            </span>

                                            <!-- Show this when any value exists -->
                                            <span x-show="rooms || adults || children" class="tg-booking-title-value">
                                                <template x-if="rooms">
                                                    <span x-text="rooms + ' Room'"></span>
                                                </template>
                                                <template x-if="adults">
                                                    <span x-text="', ' + adults + ' Adult'"></span>
                                                </template>
                                                <template x-if="children">
                                                    <span x-text="', ' + children + ' Child'"></span>
                                                </template>
                                            </span>
                                        </div>
                                        <span class="location">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_53_98)">
                                                    <path
                                                        d="M1.51285 15.2889C1.33507 15.2889 1.15729 15.2 1.0684 15.1111C0.979514 14.9334 0.890625 14.8445 0.890625 14.6667C0.890625 13.4222 1.24618 12.1778 1.8684 11.0222C2.49063 9.95558 3.4684 8.9778 4.53507 8.35558C4.09062 7.82225 3.73507 7.11114 3.55729 6.40003C3.4684 5.68892 3.4684 4.88892 3.64618 4.26669C3.82396 3.55558 4.2684 2.84447 4.71285 2.31114C5.24618 1.7778 5.8684 1.33336 6.49063 1.15558C7.02396 0.977805 7.55729 0.888916 8.09063 0.888916C8.2684 0.888916 8.53507 0.888916 8.71285 0.888916C9.42396 0.977805 10.1351 1.24447 10.7573 1.68892C11.3795 2.13336 11.824 2.66669 12.1795 3.28892C12.5351 3.91114 12.7128 4.62225 12.7128 5.42225C12.7128 6.48892 12.3573 7.55558 11.6462 8.35558C12.1795 8.71114 12.7128 9.06669 13.2462 9.51114C13.9573 10.2222 14.4017 10.9334 14.8462 11.8222C15.2017 12.7111 15.3795 13.6 15.3795 14.5778C15.3795 14.7556 15.2906 14.9334 15.2017 15.0222C15.1128 15.1111 14.9351 15.2 14.7573 15.2C14.6684 15.2 14.5795 15.2 14.4906 15.1111C14.4017 15.1111 14.3128 15.0222 14.3128 14.9334C14.224 14.8445 14.224 14.8445 14.1351 14.7556C14.1351 14.6667 14.0462 14.5778 14.0462 14.4889C14.0462 13.6889 13.8684 12.9778 13.6017 12.2667C13.3351 11.5556 12.8906 10.9334 12.2684 10.4C11.7351 9.95558 11.2017 9.51114 10.5795 9.24447C9.8684 9.68892 9.0684 9.95558 8.09063 9.95558C7.20174 9.95558 6.31285 9.68892 5.60174 9.24447C4.62396 9.68892 3.73507 10.4 3.11285 11.3778C2.49063 12.3556 2.13507 13.4222 2.13507 14.5778C2.13507 14.7556 2.04618 14.9334 1.95729 15.0222C1.8684 15.2 1.69062 15.2889 1.51285 15.2889ZM8.09063 2.22225C7.4684 2.22225 6.84618 2.40003 6.31285 2.75558C5.69062 3.11114 5.33507 3.64447 5.0684 4.1778C4.80174 4.80003 4.71285 5.42225 4.89063 6.13336C4.97951 6.75558 5.33507 7.37781 5.77951 7.82225C6.22396 8.26669 6.84618 8.62225 7.4684 8.71114C7.64618 8.71114 7.91285 8.80003 8.09063 8.80003C8.53507 8.80003 8.97951 8.71114 9.33507 8.53336C9.95729 8.26669 10.4017 7.91114 10.8462 7.28892C11.2017 6.75558 11.3795 6.13336 11.3795 5.51114C11.3795 4.62225 11.024 3.82225 10.4017 3.20003C9.77951 2.48892 8.97951 2.22225 8.09063 2.22225Z"
                                                        fill="currentColor" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_53_98">
                                                        <rect width="16" height="16" fill="currentColor" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="tg-booking-form-location-list tg-quantity tg-booking-quantity-active">
                                        <ul>
                                            <li>
                                                <span class="mr-20">{{ __('translate.Rooms') }}</span>
                                                <div class="tg-booking-quantity-item">
                                                    <span @click="incrementRooms" class="increment">
                                                        <svg width="15" height="14" viewBox="0 0 15 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.21924 7H13.3836" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.30176 13V1" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    <input x-bind:value="rooms" class="tg-quantity-input"
                                                        type="text">
                                                    <span @click="decrementRooms" class="decrement">
                                                        <svg width="14" height="2" viewBox="0 0 14 2"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1 1H13" stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="mr-20">{{ __('translate.Adults') }}</span>
                                                <div class="tg-booking-quantity-item">
                                                    <span @click="incrementAdults" class="increment">
                                                        <svg width="15" height="14" viewBox="0 0 15 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.21924 7H13.3836" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.30176 13V1" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    <input x-bind:value="adults" class="tg-quantity-input"
                                                        type="text">
                                                    <span @click="decrementAdults" class="decrement">
                                                        <svg width="14" height="2" viewBox="0 0 14 2"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1 1H13" stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </li>
                                            <li>
                                                <span class="mr-20">{{ __('translate.Children') }}</span>
                                                <div class="tg-booking-quantity-item">
                                                    <span @click="incrementChildren" class="increment">
                                                        <svg width="15" height="14" viewBox="0 0 15 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.21924 7H13.3836" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.30176 13V1" stroke="currentColor"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                    <input x-bind:value="children" class="tg-quantity-input"
                                                        type="text">
                                                    <span @click="decrementChildren" class="decrement">
                                                        <svg width="14" height="2" viewBox="0 0 14 2"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1 1H13" stroke="currentColor" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="tg-booking-form-search-btn mb-15">
                                    <button class="bk-search-button" type="submit">
                                        {{ __('translate.Search') }}
                                        <span class="ml-5">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <g clip-path="url(#clip0_53_103)">
                                                    <path
                                                        d="M13.2218 13.2222L10.5188 10.5192M12.1959 6.48705C12.1959 9.6402 9.63977 12.1963 6.48662 12.1963C3.33348 12.1963 0.777344 9.6402 0.777344 6.48705C0.777344 3.3339 3.33348 0.777771 6.48662 0.777771C9.63977 0.777771 12.1959 3.3339 12.1959 6.48705Z"
                                                        stroke="currentColor" stroke-width="1.575"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </g>
                                                <defs>
                                                    <clipPath id="clip0_53_103">
                                                        <rect width="14" height="14" fill="currentColor" />
                                                    </clipPath>
                                                </defs>
                                            </svg>
                                        </span>
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
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

        <script>
            function bookingForm() {
                return {
                    destination: '',
                    destination_id: '',
                    check_in: '',
                    check_out: '',
                    rooms: '',
                    adults: '',
                    children: '',

                    selectDestination(destinationId, destinationName) {
                        this.destination_id = destinationId;
                        this.destination = destinationName;
                    },

                    incrementRooms() {
                        this.rooms++;
                    },
                    decrementRooms() {
                        if (this.rooms > 0) {
                            this.rooms--;
                        }
                    },
                    incrementAdults() {
                        this.adults++;
                    },
                    decrementAdults() {
                        if (this.adults > 0) {
                            this.adults--;
                        }
                    },
                    incrementChildren() {
                        this.children++;
                    },
                    decrementChildren() {
                        if (this.children > 0) {
                            this.children--;
                        }
                    },

                    submitForm() {
                        const params = new URLSearchParams({
                            destination: this.destination,
                            destination_id: this.destination_id,
                            check_in: this.check_in,
                            check_out: this.check_out,
                            rooms: this.rooms,
                            adults: this.adults,
                            children: this.children
                        });

                        window.location.href = `{{ route('front.tourbooking.services') }}?` + params.toString();
                    }
                }
            }
        </script>
    @endpush
@endif
