@php
    $theme4_destination = getContent('theme4_destination.content', true);
    $home4_destination_items = popularDestinations(6, false);
    
    
 
@endphp
@php
    $theme4_tour_package = getContent('theme4_tour_package.content', true);

    $theme4_popular_services = popularServices(20, false);

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

<!-- tg-location-area-start -->
<div class="tg-location-area tg-location-su-2-wrap fix pt-50 pb-50 p-relative" style="background-color: #f2fdf9;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-6">
                <div class="tg-location-section-title-wrap text-center mb-40">
                    <h5 class="tg-section-su-subtitle su-subtitle-2 mb-15 wow fadeInUp" data-wow-delay=".4s"
                        data-wow-duration=".9s">
                        {{ getTranslatedValue($theme4_destination, 'sub_title') }}
                    </h5>
                    <h2 class="tg-section-su-title text-capitalize wow fadeInUp mb-15" data-wow-delay=".5s"
                        data-wow-duration=".9s">
                        {{ getTranslatedValue($theme4_destination, 'title') }}
                    </h2>
                </div>
            </div>
        </div>

        @if ($home4_destination_items->count() > 0)
            <div class="row gx-30">
                @foreach ($home4_destination_items as $key => $destination_item)
                    <div @class([
                        'col-lg-4 col-md-6 mb-30',
                        'col-xl-6' => $key == 2 || $key == 3,
                        'col-xl-3' => $key != 2 && $key != 3,
                    ])>
                        <div class="tg-location-3-wrap tg-location-su-wrap p-relative tg-round-25 wow fadeInUp"
                            data-wow-delay=".{{$key + 3}}s" data-wow-duration=".9s">
                            <div class="tg-location-thumb tg-round-25">
                                <img class="w-100 tg-round-25"
                                    src="{{ asset('storage/' . $destination_item->image) }}"
                                    alt="{{ $destination_item->name }}">
                            </div>
                            <div class="tg-location-content tg-location-su-content">
                                <div class="content">
                                    <h3 class="tg-location-title mb-5"><a
                                            href="{{ route('front.tourbooking.services', ['destination_id' => $destination_item->id, 'destination' => $destination_item->name]) }}">
                                            {{ $destination_item->name }}
                                        </a>
                                    </h3>
                                </div>
                                <a class="icons"
                                    href="{{ route('front.tourbooking.services', ['destination_id' => $destination_item->id, 'destination' => $destination_item->name]) }}">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M2 13.0969L13.0969 2M13.0969 2H2M13.0969 2V13.0969"
                                            stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>
</div>
<div class="tg-location-area tg-location-su-2-wrap fix pt-20 pb-40 p-relative" style="background-color: rgb(246, 244, 250);">
    <div class="container">
         {{-- ================= VISA SERVICES ================= --}}
@if ($visaServices->count() > 0)
    <div class="row mt-40">
        <div class="col-12">
            <h3 class="text-center mb-30">Visa Services</h3>
        </div>

        @foreach ($visaServices as $service)
            <div class="col-xl-4 col-lg-4 col-md-6">
                <div class="tg-listing-card-item tg-listing-su-card-item mb-25">
                    <div class="tg-listing-card-thumb fix mb-25 p-relative">
                        <a href="{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}">
                            <img class="tg-card-border w-100"
                                 src="{{ asset('storage/' . $service?->thumbnail?->file_path) }}"
                                 alt="{{ $service?->translation?->title }}">
                        </a>
                    </div>

                    <div class="tg-listing-card-content">
                        <h4 class="tg-listing-card-title mb-10">
                            <a href="{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}">
                                {{ Str::limit($service?->translation?->title, 45) }}
                            </a>
                        </h4>

                        @if ($service?->location)
                            <div class="tg-listing-card-duration-tour mb-15">
                                <span class="tg-listing-card-duration-map">
                                    {{ $service->location }}
                                </span>
                            </div>
                        @endif

                        <div class="tg-listing-card-price d-flex justify-content-between">
                            <span class="tg-listing-card-currency-amount">
                                <button onclick="window.location.href='{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}'">Enquire Now</button>
                            <button class="whatsapp-btn">
                              <a href="https://wa.me/?text=I'm%20interested%20in%20{{ urlencode($service?->translation?->title) }}" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                              </a>
                            </button>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach

        <div class="col-12">
            <div class="text-center mt-15">
                <a href="{{ url('tour-booking/services') }}?service_type_ids[]=8"
                   class="tg-btn tg-btn-transparent tg-btn-su-transparent">
                    See All Visa Services
                </a>
            </div>
        </div>
    </div>
@endif
    </div>
</div>
<!-- tg-location-area-end -->

@push('style_section')
    <style>
        .tg-location-su-wrap .tg-location-thumb img {
            height: 324px;
        }
    </style>
@endpush
