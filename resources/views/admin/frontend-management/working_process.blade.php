@extends('admin.master_layout')
@section('title')
    <title>{{ __('translate.Frontend Section') }}</title>
@endsection

@section('body-header')
    <h3 class="crancy-header__title m-0">{{ __('translate.Frontend Section') }}</h3>
    <p class="crancy-header__text">{{ __('translate.Manage Content') }} >> {{ __('translate.Frontend Section') }}</p>
@endsection
@section('body-content')

    <section class="crancy-adashboard crancy-show">
        <div class="container container__bscreen">
            <div class="row">
                <div class="col-12">
                    <div class="crancy-body">
                        <div class="crancy-dsinner">
                            <div class="crancy-table crancy-table--v3 mg-top-30">
                                <div class="crancy-customer-filter">
                                    <div class="container">
                                         


                                        <h2>Our Working Process Images</h2>

                                        @if(session('success'))
                                            <div class="alert alert-success">{{ session('success') }}</div>
                                        @endif

                                        <form action="{{ url('admin/frontend-section/working-process') }}" method="POST" enctype="multipart/form-data">
                                            @csrf

                                            @for($i = 1; $i <= 4; $i++)
                                                <div class="mb-3">
                                                    <label for="image_{{ $i }}">Image {{ $i }}</label><br>
                                                    @if(isset($data->{'image_'.$i}))
                                                        <img src="{{ asset($data->{'image_'.$i}) }}" width="100" alt="Image {{ $i }}"><br>
                                                    @endif
                                                    <input type="file" name="image_{{ $i }}" id="image_{{ $i }}" class="form-control">
                                                </div>
                                            @endfor

                                            <button type="submit" class="btn btn-primary">Save Images</button>
                                        </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
                                    @endsection

@push('style_section')
<style>
    .nav-tabs .nav-link {
        color: #6c757d;
    }

    .nav-tabs .nav-link.active {
        color: #0d6efd;
        font-weight: bold;
    }

    .card-header h5 {
        font-size: 1.1rem;
        text-overflow: ellipsis;
        overflow: hidden;
        white-space: nowrap;
        max-width: 200px;
    }

    @media (max-width: 768px) {
        .card-header h5 {
            max-width: 150px;
        }
    }
</style>
@endpush
