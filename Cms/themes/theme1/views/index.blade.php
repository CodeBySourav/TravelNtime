@php
    $theme1_tour_package = getContent('theme1_tour_package.content', true);

    $theme1_service = serviceTypeTab();
    $theme1_popular_services = popularServices();

@endphp

@extends('theme::layouts.app')
@section('title')
    <title>{{ $seo_setting->seo_title }}</title>
    <meta name="title" content="{{ $seo_setting->seo_title }}">
    <meta name="description" content="{!! strip_tags(clean($seo_setting->seo_description)) !!}">
    <style>
      /* Mobile view styles */
        @media (max-width: 768px) {
     

            .hero-bg img {
                margin-top: 91px;
                }
            }
            .package-card h4 {
                font-size: 22px !important; 
            }
        
            .package-card p {
                font-size: 20px;
                font-weight: 600;
            }
        
            .package-card {
                height: auto; /* Optional: allows card to expand with content on smaller screens */
                margin-bottom: 27px;
                height: inherit ;
            } 
        }
    </style>
@endsection
@section('front-content')
     
    <!-- hero -->
<section class="hero">
  <div class="hero-bg">
    <img src="{{ asset('frontend/assets/img/Hero-Image.png') }}" alt="Travel N Time">
    
  </div>


  <div class="search-section">
   
    <div class="search-tabs">
      <div class="tabs">
        <button class="tab active">Flights</button>
        <button class="tab">Hotels</button>
        <button class="tab">Holidays</button>
        <button class="tab">Bus</button>
      </div>
    
      <div class="search-tabs-form">
            <!-- flight-search.html -->


        <form id="flightForm" class="flight-search" method="POST" action="submit-flight-form.php">
    <div class="trip-types">
      <label><input type="checkbox" name="trip_type[]" value="ONEWAY" checked> <span>ONEWAY</span></label>
      <label><input type="checkbox" name="trip_type[]" value="RETURN" checked> <span>RETURN</span></label>
      <label><input type="checkbox" name="trip_type[]" value="MULTI-CITY" checked> <span>MULTI-CITY</span></label>
      <label><input type="checkbox" name="trip_type[]" value="AIR CALENDER"> <span>AIR CALENDER</span></label>
    </div>

    <div class="search-fields">
      <input type="text" name="from_city" placeholder="Leaving from, Enter City" required>
      <input type="text" name="to_city" placeholder="Going to, Enter City" required>
      <input type="date" name="departure_date" required>
    </div>

    <p class="traveler-note">Travelers (upto 9 per Booking)</p>

    <div class="passenger-select">
      <select name="adults">
        <option value="">Adults (12+)</option>
        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
      </select>

      <select name="children">
        <option value="">Children (2 - 11)</option>
        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
      </select>

      <select name="infants">
        <option value="">Infants (0 - 2)</option>
        <option>1</option><option>2</option><option>3</option><option>4</option><option>5</option>
      </select>

      <select name="class">
        <option value="">Class</option>
        <option>Economy</option>
        <option>Premium Economy</option>
        <option>Business</option>
        <option>First Class</option>
      </select>

      <button type="submit" class="search-btn">
        <i class="fas fa-search"></i> SEARCH
      </button>
    </div>
  </form>
  <div id="form-message" style="text-align:center; margin-top:20px; font-weight:bold;"></div>

      </div>
    </div>
  </div>
</section>

<!-- welcome -->
<section class="welcome-section">
  <div class="container-welcome">
    <h1 class="heading-bold-font-h1"><span class="heading-bold-font-h1-1">Recommended Tours</span></h1>
     
    <section class="tour-section" style="background-color: transparent; margin: 0;">
  <div class=" ">
    
    <div class="tour-grid">

            <!-- Swiper Slider Wrapper -->
            <div class="swiper recommendedSwiper">
                <div class="swiper-wrapper">
                    @foreach ($theme1_popular_services as $service)
                      @if (strtolower($service->country) == 'india')
                        <div class="swiper-slide">
                          <div class="package-card">
                            <img src="{{ asset('storage/' . $service?->thumbnail?->file_path) }}" alt="{{ $service?->thumbnail?->caption ?? $service?->translation?->title }}">
                            <h4>{{ Str::limit($service?->translation?->title, 45) }}</h4>
                            <p>
                              <span>
                                <svg width="13" height="16" viewBox="0 0 13 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12.3329 6.7071C12.3329 11.2324 6.55512 15.1111 6.55512 15.1111C6.55512 15.1111 0.777344 11.2324 0.777344 6.7071C0.777344 5.16402 1.38607 3.68414 2.46962 2.59302C3.55316 1.5019 5.02276 0.888916 6.55512 0.888916C8.08748 0.888916 9.55708 1.5019 10.6406 2.59302C11.7242 3.68414 12.3329 5.16402 12.3329 6.7071Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                  <path d="M6.55512 8.64649C7.61878 8.64649 8.48105 7.7782 8.48105 6.7071C8.48105 5.636 7.61878 4.7677 6.55512 4.7677C5.49146 4.7677 4.6292 5.636 4.6292 6.7071C4.6292 7.7782 5.49146 8.64649 6.55512 8.64649Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $service?->location }}
                              </span>
                              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.00175 3.73329V7.99996L10.8462 9.42218M15.1128 8.00003C15.1128 11.9274 11.9291 15.1111 8.00174 15.1111C4.07438 15.1111 0.890625 11.9274 0.890625 8.00003C0.890625 4.07267 4.07438 0.888916 8.00174 0.888916C11.9291 0.888916 15.1128 4.07267 15.1128 8.00003Z" stroke="currentColor" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                              {{ $service?->duration }}
                            </p>
                            @include('tourbooking::front.services.ratting', [
                                'avgRating' => $service?->active_reviews_avg_rating ?? 0,
                                'ratingCount' => $service?->active_reviews_count ?? 0,
                                'ratingClass' => 'tg-listing-card-review mb-5',
                            ])
                            <button onclick="window.location.href='{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}'">Enquire Now</button>
                            <button class="whatsapp-btn">
                              <a href="https://wa.me/?text=I'm%20interested%20in%20{{ urlencode($service?->translation?->title) }}" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                              </a>
                            </button>
                          </div>
                        </div>
                      @endif
                    @endforeach
                </div>

                <!-- Pagination dots -->
               <div class="swiper-pagination recommended-pagination"></div>
            </div>
        </div>
    </div>
</section>
    
  </div>
  <div class="transparent-img">
    <img src="{{ asset('frontend/assets/img/transparent-image.png') }}" alt="travel">
  </div>
  
</section>

<!-- upcoming -->
<section class="upcoming-packages">
  <div class="container-upcoming">
    <div class="package-grid-left-heading-container-30">
      <h2 class="heading-bold-font-h2"><span class="heading-bold-font-h2-1">Upcoming</span> <br> <span class="heading-semi-bold-font-h2-2"> Packages</span></h2>
    </div>
    <div class="package-grid-right-container-70">
      <div class="package-card">
        <img src="{{ asset('frontend/assets/img/upcoming-package-1') }}.png" alt="Krabi">
        <h4>THAI MASH UP PHUKET - KRABI - BANGKOK 7N/8D</h4>
        <p><span> From <del>₹ 30,000 PP*</del> </span> ₹ 25,000 PP*</p>
        <button>Enquire Now</button>
      </div>
      <div class="package-card">
        <img src="{{ asset('frontend/assets/img/upcoming-package-2.png') }}" alt="Thailand">
        <h4>SUPER SAVER THAILAND - BANGKOK-PATTAYA 4N/5D</h4>
        <p> <span> From <del>₹ 15,000 PP*</del> </span> ₹ 11,999 PP*</p>
        <button>Enquire Now</button>
      </div>
    </div>
  </div>
</section>


<!-- domestic -->
<section class="tour-section">
  <div class="international-gallery">
    <h2 class="heading-bold-font-h2"><span class="heading-bold-font-h2-1">Domestic </span>  <span class="heading-semi-bold-font-h2-2"> Tours</span></h2>
    <div class="tour-grid">

            <!-- Swiper Slider Wrapper -->
            <div class="swiper mySwiper">
                <div class="swiper-wrapper">
                    @foreach ($theme1_popular_services as $service)
                      @if (strtolower($service->country) == 'india')
                        <div class="swiper-slide">
                          <div class="package-card">
                            <img src="{{ asset('storage/' . $service?->thumbnail?->file_path) }}" alt="{{ $service?->thumbnail?->caption ?? $service?->translation?->title }}">
                            <h4>{{ Str::limit($service?->translation?->title, 45) }}</h4>
                            <p>
                              <span>
                                <svg width="13" height="16" viewBox="0 0 13 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M12.3329 6.7071C12.3329 11.2324 6.55512 15.1111 6.55512 15.1111C6.55512 15.1111 0.777344 11.2324 0.777344 6.7071C0.777344 5.16402 1.38607 3.68414 2.46962 2.59302C3.55316 1.5019 5.02276 0.888916 6.55512 0.888916C8.08748 0.888916 9.55708 1.5019 10.6406 2.59302C11.7242 3.68414 12.3329 5.16402 12.3329 6.7071Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                  <path d="M6.55512 8.64649C7.61878 8.64649 8.48105 7.7782 8.48105 6.7071C8.48105 5.636 7.61878 4.7677 6.55512 4.7677C5.49146 4.7677 4.6292 5.636 4.6292 6.7071C4.6292 7.7782 5.49146 8.64649 6.55512 8.64649Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                                {{ $service?->location }}
                              </span>
                              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M8.00175 3.73329V7.99996L10.8462 9.42218M15.1128 8.00003C15.1128 11.9274 11.9291 15.1111 8.00174 15.1111C4.07438 15.1111 0.890625 11.9274 0.890625 8.00003C0.890625 4.07267 4.07438 0.888916 8.00174 0.888916C11.9291 0.888916 15.1128 4.07267 15.1128 8.00003Z" stroke="currentColor" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                              </svg>
                              {{ $service?->duration }}
                            </p>
                            @include('tourbooking::front.services.ratting', [
                                'avgRating' => $service?->active_reviews_avg_rating ?? 0,
                                'ratingCount' => $service?->active_reviews_count ?? 0,
                                'ratingClass' => 'tg-listing-card-review mb-5',
                            ])
                            <button onclick="window.location.href='{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}'">Enquire Now</button>
                            <button class="whatsapp-btn">
                              <a href="https://wa.me/?text=I'm%20interested%20in%20{{ urlencode($service?->translation?->title) }}" target="_blank">
                                <i class="fab fa-whatsapp"></i>
                              </a>
                            </button>
                          </div>
                        </div>
                      @endif
                    @endforeach
                </div>

                <!-- Pagination dots -->
                <div class="swiper-pagination"></div>
            </div>
        </div>
    </div>
</section>

<!-- international -->
<section class="tour-section-international">
  <div class="domestic-gallery">
    <h2 class="heading-bold-font-h2 left"><span class="heading-bold-font-h2-1" style="color: #ffffff;">INTERNATIONAL </span>  <span class="heading-semi-bold-font-h2-2" style="color: #ffffff;"> Tours</span></h2>
    <div class="tour-grid">

    <!-- Swiper Slider Wrapper -->
                <div class="swiper mySwiper">
                    <div class="swiper-wrapper">
                        @foreach ($theme1_popular_services as $service)
                              @if (strtolower($service->country) != 'india')
                                <div class="swiper-slide">
                                  <div class="package-card">
                                    <img src="{{ asset('storage/' . $service?->thumbnail?->file_path) }}" alt="{{ $service?->thumbnail?->caption ?? $service?->translation?->title }}">
                                    <h4>{{ Str::limit($service?->translation?->title, 45) }}</h4>
                                    <p>
                                      <span>
                                        <svg width="13" height="16" viewBox="0 0 13 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                          <path d="M12.3329 6.7071C12.3329 11.2324 6.55512 15.1111 6.55512 15.1111C6.55512 15.1111 0.777344 11.2324 0.777344 6.7071C0.777344 5.16402 1.38607 3.68414 2.46962 2.59302C3.55316 1.5019 5.02276 0.888916 6.55512 0.888916C8.08748 0.888916 9.55708 1.5019 10.6406 2.59302C11.7242 3.68414 12.3329 5.16402 12.3329 6.7071Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                          <path d="M6.55512 8.64649C7.61878 8.64649 8.48105 7.7782 8.48105 6.7071C8.48105 5.636 7.61878 4.7677 6.55512 4.7677C5.49146 4.7677 4.6292 5.636 4.6292 6.7071C4.6292 7.7782 5.49146 8.64649 6.55512 8.64649Z" stroke="currentColor" stroke-width="1.15556" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        {{ $service?->location }}
                                      </span>
                                      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M8.00175 3.73329V7.99996L10.8462 9.42218M15.1128 8.00003C15.1128 11.9274 11.9291 15.1111 8.00174 15.1111C4.07438 15.1111 0.890625 11.9274 0.890625 8.00003C0.890625 4.07267 4.07438 0.888916 8.00174 0.888916C11.9291 0.888916 15.1128 4.07267 15.1128 8.00003Z" stroke="currentColor" stroke-width="1.06667" stroke-linecap="round" stroke-linejoin="round" />
                                      </svg>
                                      {{ $service?->duration }}
                                    </p>
                                    @include('tourbooking::front.services.ratting', [
                                        'avgRating' => $service?->active_reviews_avg_rating ?? 0,
                                        'ratingCount' => $service?->active_reviews_count ?? 0,
                                        'ratingClass' => 'tg-listing-card-review mb-5',
                                    ])
                                    <button onclick="window.location.href='{{ route('front.tourbooking.services.show', ['slug' => $service?->slug]) }}'">Enquire Now</button>
                                    <button class="whatsapp-btn">
                                      <a href="https://wa.me/?text=I'm%20interested%20in%20{{ urlencode($service?->translation?->title) }}" target="_blank">
                                        <i class="fab fa-whatsapp"></i>
                                      </a>
                                    </button>
                                  </div>
                                </div>
                              @endif
                            @endforeach
                    </div>

                    <!-- Pagination dots -->
                    <div class="swiper-pagination"></div>
                </div>

            </div>
        </div>
    </section>



  <!--   gallery  -->

<section class="vacation-gallery">
  <div class="gallery-container">
  <div class="gallery-grid">
    <div class="grid-item large-img">
      <img src="{{ asset('frontend/assets/img/gallery-1.png') }} " alt="Airplane" />
    </div>
    <div class="grid-item tall-img">
      <img src="{{ asset('frontend/assets/img/gallery-2.png') }} " alt="Tourist" />
    </div>
    <div class="grid-item large-img">
      <img src="{{ asset('frontend/assets/img/gallery-3.png') }} " alt="Lion Safari" />
    </div>
    <div class="grid-item wide-img">
      <img src="{{ asset('frontend/assets/img/gallery-4.png') }} " alt="Family Trip" />
    </div>
  </div>

  <div class="gallery-text">
    <h2>
      We Make <br />
      <span>Perfect Vacations</span> <br />
      for your
    </h2>
  </div>
  </div>
</section>



<!--  logo slider  -->

<section class="logo-slider-section py-4">
  <div class="container text-center">
    <h2 class="fw-bold" style="font-size: 28px;">Our Partners</h2>
  </div>
  <div class="logo-slider-container">
  <div class="swiper logoSwiper">
  <div class="swiper-wrapper">
    <!-- Slide 1 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/indigo-logo.png') }}" alt="IndiGo Logo" />
    </div>
    <!-- Slide 2 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/IATA-logo.png') }}" alt="IATA Logo" />
    </div>
    <!-- Slide 3 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/thai-logo.png') }}" alt="THAI Logo" />
    </div>
    <!-- Slide 4 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/air-india-logo.png') }}" alt="Air India Logo" />
    </div>
    <!-- Slide 5 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/lufthansa-logo.png') }}" alt="Lufthansa Logo" />
    </div>

    <!-- Slide 1 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/indigo-logo.png') }}" alt="IndiGo Logo" />
    </div>
    <!-- Slide 2 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/IATA-logo.png') }}" alt="IATA Logo" />
    </div>
    <!-- Slide 3 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/thai-logo.png') }}" alt="THAI Logo" />
    </div>
    <!-- Slide 4 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/air-india-logo.png') }}" alt="Air India Logo" />
    </div>
    <!-- Slide 5 -->
    <div class="swiper-slide">
      <img src="{{ asset('frontend/assets/img/lufthansa-logo.png') }}" alt="Lufthansa Logo" />
    </div>

  </div>
</div>
  </div>
</section>





@endsection
