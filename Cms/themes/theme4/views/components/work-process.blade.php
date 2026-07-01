@php
    $theme4_work_process = getContent('theme4_work_process.content', true);
    $translatedSlides = getTranslatedSlides($theme4_work_process, 'slides');
@endphp

@if ($theme4_work_process)
    <!-- tp-process-area-start -->
    <div class="tp-process-area include-bg pb-90 pt-120" data-background="{{ asset('frontend/assets/img/shape/work-bg4.png') }}">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="tg-process-content mb-30 wow fadeInLeft" data-wow-delay=".4s" data-wow-duration=".9s">
                        <h5 class="tg-section-su-subtitle su-subtitle-2 mb-15" style="background: #b8071a;">
                            {{ getTranslatedValue($theme4_work_process, 'sub_title') }}
                        </h5>
                        <h2 class="tg-section-su-title text-capitalize mb-15">
                            {!! strip_tags(clean(getTranslatedValue($theme4_work_process, 'title')), '<br>') !!}
                        </h2>
                        <p class="tg-section-su-para tg-section-su-para-2 mb-25">
                            {!! strip_tags(clean(getTranslatedValue($theme4_work_process, 'description')), '<br>') !!}
                        </p>
                        <a href="{{ getTranslatedValue($theme4_work_process, 'button_url') }}" class="tg-btn tg-btn-transparent">{{ getTranslatedValue($theme4_work_process, 'button_text') }}</a>
                    </div>
                </div>
                @if (count($translatedSlides) > 0)
                    <div class="col-lg-6">
                        <div class="tg-process-list mb-10 wow fadeInRight" data-wow-delay=".4s" data-wow-duration=".9s">
                            
 
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
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- tp-process-area-end -->
@endif



<style>
.gallery-grid {
  column-count: 2;
  column-gap: 20px;
  width: 60%;
}
.gallery-grid {
	width: 100%;
}
.grid-item {
  break-inside: avoid;
  margin-bottom: 20px;
}

.grid-item img {
  width: 100%;
  display: block;
  border: 4px solid #aa0022;
  border-radius: 4px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
}

</style>


