@if (count($paginatedHotels) > 0)
    <div class="tg-listing-grid-item">
        <div @class(['row list-card', 'list-card-open' => $isListView == 'true'])>
            @foreach ($paginatedHotels as $key => $hotel)
                <div class="col-xxl-4 col-xl-6 col-lg-6 col-md-6 tg-grid-full">
                    <div class="tg-listing-card-item tg-listing-4-card-item mb-25">
                        <div class="tg-listing-card-thumb tg-listing-2-card-thumb mb-15 fix p-relative">
                            <a href="javascript:void(0);">
                                <img class="tg-card-border w-100"
                                    src="{{ $hotel['HotelPicture'] ?? asset('frontend/assets/img/shape/no-image.png') }}"
                                    alt="{{ $hotel['HotelName'] ?? 'Hotel Image' }}">

                                @if(isset($hotel['HotelRating']) && $hotel['HotelRating'] === 'FiveStar')
                                    <span class="tg-listing-item-price-discount shape"
                                        style="background-image: url('{{ asset('frontend/assets/img/shape/price-shape-2.png') }}')">Luxury</span>
                                @endif
                            </a>
                            <div class="tg-listing-2-price">
                                <strong>₹{{ $hotel['Price']['OfferedPriceRoundedOff'] ?? 0 }}</strong>
                            </div>
                        </div>
                        <div class="tg-listing-card-content p-relative">
                            <h4 class="tg-listing-card-title mb-5">
                                <a href="javascript:void(0);">
                                    {{ Str::limit($hotel['HotelName'] ?? 'No Name', 45) }}
                                </a>
                            </h4>
                            <span class="tg-listing-card-duration-map d-inline-block">
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
                                {{ $hotel['HotelAddress'] ?? 'Address details unstated' }}
                            </span>

                            @php 
                                // Simple mapping string/numeric star counts to standard rating configurations
                                $ratingMap = ['OneStar' => 1, 'TwoStar' => 2, 'ThreeStar' => 3, 'FourStar' => 4, 'FiveStar' => 5];
                                $stars = $ratingMap[$hotel['HotelRating'] ?? ''] ?? intval($hotel['HotelRating'] ?? 0);
                            @endphp
                            
                            @include('tourbooking::front.services.ratting', [
                                'avgRating' => $stars ?: 4, 
                                'ratingCount' => 1
                            ])

                            <div class="tg-listing-avai d-flex align-items-center justify-content-between">
                                <a class="tg-listing-avai-btn"
                                    href="{{ route('front.tourbooking.front.hotel.details',[
                                            'traceId'=>$traceId,
                                            'resultIndex'=>$hotel['ResultIndex'],
                                            'hotelCode'=>$hotel['HotelCode'],
                                            'categoryId'  => $hotel['SupplierHotelCodes'][0]['CategoryId'] ?? ''
                                    ]) }}">
                                        View Details
                                    </a>
                                <div class="tg-listing-item-wishlist" onclick="this.classList.toggle('active')">
                                    <a href="javascript:void(0);">
                                        <svg width="20" height="18" viewBox="0 0 20 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.5167 16.3416C10.2334 16.4416 9.76675 16.4416 9.48341 16.3416C7.06675 15.5166 1.66675 12.075 1.66675 6.24165C1.66675 3.66665 3.74175 1.58331 6.30008 1.58331C7.81675 1.58331 9.15841 2.31665 10.0001 3.44998C10.8417 2.31665 12.1917 1.58331 13.7001 1.58331C16.2584 1.58331 18.3334 3.66665 18.3334 6.24165C18.3334 12.075 12.9334 15.5166 10.5167 16.3416Z"
                                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
        <div class="text-center mt-50 mb-30">
            @include('components.front.custom-pagination', ['items' => $paginatedHotels])
        </div>
    </div>
@else
    <div class="col-12">
        Hotels Not found.
    </div>
@endif

