<!DOCTYPE html>
<html class="no-js" lang="en">

<head>
    <!-- Meta Tags -->
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Favicon Icon -->
    <link rel="shortcut icon" href="{{ asset($general_setting->favicon) }}" type="image/x-icon">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Site Title -->
    @yield('title')

    <link rel="stylesheet" href="{{ asset('frontend/assets/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/animate.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/magnific-popup.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/swiper-bundle.min.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/flatpicker.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/odometer.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/default.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/dev.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/cookie_consent.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/custom.css') }}">
    <link rel="stylesheet" href="{{ asset('frontend/assets/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('global/toastr/toastr.min.css') }}">

    @stack('style_section')


    @if ($general_setting->google_analytic_status == 1)
        <script async src="https://www.googletagmanager.com/gtag/js?id={{ $general_setting->google_analytic_id }}"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }
            gtag('js', new Date());
            gtag('config', '{{ $general_setting->google_analytic_id }}');
        </script>
    @endif


    @if ($general_setting->pixel_status == 1)
        <script>
            ! function(f, b, e, v, n, t, s) {
                if (f.fbq) return;
                n = f.fbq = function() {
                    n.callMethod ?
                        n.callMethod.apply(n, arguments) : n.queue.push(arguments)
                };
                if (!f._fbq) f._fbq = n;
                n.push = n;
                n.loaded = !0;
                n.version = '2.0';
                n.queue = [];
                t = b.createElement(e);
                t.async = !0;
                t.src = v;
                s = b.getElementsByTagName(e)[0];
                s.parentNode.insertBefore(t, s)
            }(window, document, 'script',
                'https://connect.facebook.net/en_US/fbevents.js');
            fbq('init', '{{ $general_setting->pixel_app_id }}');
            fbq('track', 'PageView');
        </script>
        <noscript>
            <img height="1" width="1" style="display:none"
                src="https://www.facebook.com/tr?id={{ $general_setting->pixel_app_id }}&ev=PageView&noscript=1" /></noscript>
    @endif

</head>

<body class="td_theme_2">

    @if ($general_setting->preloader_status == 'enable')
        <!-- Start Preloader -->
        <div id="loading">
            <div class="loader"></div>
        </div>
        <!-- End Preloader -->
    @endif

    @if ($general_setting->preloader_status == 'enable')
        <!-- Scroll-top -->
        <button class="scroll__top scroll-to-target" data-target="html">
            <i class="fa-sharp fa-regular fa-arrow-up"></i>
        </button>
        <!-- Scroll-top-end-->
    @endif


    <!-- header-area -->
<!-- header-area -->
<header class="tg-header-height tg-header-tu-2-wrap">
    <div class="tg-header-top tg-header-top-space tg-primary-bg d-none d-lg-block">
        <div class="container-fluid container-1790">
            <div class="row">
                <div class="col-lg-6">
                    @if ($footer->address || $footer->email)
                        <div class="tg-header-top-info d-flex align-items-center" style="width: 150%;">
                            <a href="{{ $footer->address_url }}"><i class="mr-5 fa-regular fa-location-dot"></i>
                                {{ $footer->address }}</a>
                            <span class="tg-header-dvdr mr-20 ml-20"></span>
                            <a href="mailto:{{ $footer->email }}"><i class="mr-5 fa-regular fa-envelope"></i>
                                {{ $footer->email }}</a>
                        </div>
                    @endif
                </div>
                <div class="col-lg-6">
                    <div class="tg-header-top-social d-flex align-items-center justify-content-end">
                        <span>Follow us:</span>
                        <ul>

                            @if ($footer->facebook)
                                <li>
                                    <a href="{{ $footer->facebook }}">
                                        <svg width="9" height="16" viewBox="0 0 9 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.58537 9V15.977H2.5371L2.5371 9V8.97702H2.51412H0.0229779L0.0229779 6.12735H2.51412H2.5371V6.10437V3.8975C2.5371 2.6467 2.90528 1.67868 3.55238 1.02341C4.19947 0.368155 5.12809 0.0229779 6.25425 0.0229779C6.79466 0.0229779 7.34778 0.0717824 7.76599 0.120635C7.97505 0.145056 8.15029 0.169475 8.27324 0.187786C8.33471 0.196941 8.38311 0.204568 8.41612 0.209903L8.44761 0.215073V2.63796H7.22202C6.60193 2.63796 6.19145 2.83282 5.93646 3.13112C5.68203 3.42876 5.58537 3.82594 5.58537 4.22531V6.10437V6.12735H5.60834L8.32802 6.12735L7.89574 8.97702H5.60834H5.58537V9Z"
                                                fill="white" stroke="white" stroke-width="0.0459559" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                            @if ($footer->twitter)
                                <li>
                                    <a href="{{ $footer->twitter }}">
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.57996 5.94609L4.09923 1.06609C4.05887 1.00939 4.00552 0.963171 3.94366 0.931289C3.88179 0.899407 3.8132 0.882786 3.7436 0.882813L1.90651 0.882813C1.82644 0.882915 1.74793 0.905048 1.6796 0.946788C1.61127 0.988527 1.55574 1.04826 1.51909 1.11946C1.48244 1.19065 1.46609 1.27056 1.47182 1.35042C1.47756 1.43029 1.50516 1.50704 1.5516 1.57227L6.0076 7.81954M7.57996 5.94609L12.036 12.1934C12.0824 12.2586 12.11 12.3353 12.1157 12.4152C12.1215 12.4951 12.1051 12.575 12.0685 12.6462C12.0318 12.7174 11.9763 12.7771 11.908 12.8188C11.8396 12.8606 11.7611 12.8827 11.6811 12.8828H9.84396C9.77436 12.8828 9.70577 12.8662 9.6439 12.8343C9.58204 12.8025 9.52869 12.7562 9.48833 12.6995L6.0076 7.81954M7.57996 5.94609L11.8287 0.882813L7.57996 5.94609ZM6.0076 7.81954L1.75887 12.8828L6.0076 7.81954Z"
                                                fill="white" />
                                            <path
                                                d="M7.57996 5.94609L4.09923 1.06609C4.05887 1.00939 4.00552 0.963171 3.94366 0.931289C3.88179 0.899407 3.8132 0.882786 3.7436 0.882813L1.90651 0.882813C1.82644 0.882915 1.74793 0.905048 1.6796 0.946788C1.61127 0.988527 1.55574 1.04826 1.51909 1.11946C1.48244 1.19065 1.46609 1.27056 1.47182 1.35042C1.47756 1.43029 1.50516 1.50704 1.5516 1.57227L6.0076 7.81954M7.57996 5.94609L12.036 12.1934C12.0824 12.2586 12.11 12.3353 12.1157 12.4152C12.1215 12.4951 12.1051 12.575 12.0685 12.6462C12.0318 12.7174 11.9763 12.7771 11.908 12.8188C11.8396 12.8606 11.7611 12.8827 11.6811 12.8828H9.84396C9.77436 12.8828 9.70577 12.8662 9.6439 12.8343C9.58204 12.8025 9.52869 12.7562 9.48833 12.6995L6.0076 7.81954M7.57996 5.94609L11.8287 0.882813M6.0076 7.81954L1.75887 12.8828"
                                                stroke="white" stroke-width="1.09091" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                            @if ($footer->linkedin)
                                <li>
                                    <a href="{{ $footer->twitter }}">
                                        <svg width="15" height="16" viewBox="0 0 15 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M14.1623 5.09053L14.1623 5.09074C14.2284 6.25545 14.2284 9.74501 14.1623 10.9097L14.1623 10.9099C14.1057 12.0379 13.8484 13.0331 13.0289 13.8528L13.0288 13.8528C12.2093 14.6757 11.2144 14.9331 10.0866 14.9865L10.0864 14.9865C8.92198 15.0526 5.43005 15.0526 4.2656 14.9865L4.26545 14.9865C3.13768 14.9299 2.14582 14.6725 1.32313 13.8528C0.5005 13.0331 0.243148 12.0379 0.189745 10.9099L0.189733 10.9097C0.123643 9.74501 0.123643 6.2523 0.189734 5.08759L0.189741 5.08744C0.246291 3.95933 0.500521 2.96419 1.32313 2.14452C2.14581 1.32479 3.1408 1.06739 4.26539 1.01398L4.2656 1.01396C5.43005 0.947858 8.92198 0.947858 10.0864 1.01396L10.0866 1.01397C11.2144 1.07053 12.2093 1.32795 13.0289 2.14764L13.0289 2.14767C13.8515 2.96736 14.1089 3.96252 14.1623 5.09053ZM11.3466 13.525L11.3466 13.525C11.9729 13.2738 12.4531 12.7935 12.7011 12.1701C12.8871 11.7028 12.9579 10.9152 12.9826 10.1195C13.0004 9.5493 12.9946 8.97233 12.9898 8.50242C12.9879 8.31575 12.9862 8.14597 12.9862 8.00023C12.9862 7.85446 12.9879 7.68471 12.9898 7.4981C12.9946 7.02846 13.0004 6.45201 12.9826 5.88218C12.9579 5.0868 12.8871 4.29926 12.7011 3.83034L12.7011 3.83026C12.45 3.20387 11.9698 2.72353 11.3466 2.47547L11.3465 2.47545C10.8793 2.29106 10.0919 2.22025 9.29635 2.19506C8.72203 2.17688 8.14082 2.18245 7.66912 2.18698C7.48664 2.18873 7.32055 2.19032 7.17759 2.19032C7.03185 2.19032 6.86214 2.1886 6.67557 2.18671C6.20604 2.18196 5.62971 2.17612 5.06001 2.19388C4.2648 2.21867 3.47744 2.28948 3.00862 2.47546L3.00854 2.47549C2.38228 2.72669 1.90206 3.20698 1.65405 3.83031L1.65403 3.83038C1.46968 4.2977 1.39889 5.08522 1.37371 5.88099C1.35553 6.45544 1.3611 7.03678 1.36563 7.50859C1.36738 7.69111 1.36897 7.85723 1.36897 8.00023C1.36897 8.146 1.36725 8.31575 1.36536 8.50236C1.3606 8.972 1.35477 9.54845 1.37253 10.1183C1.39731 10.9137 1.46811 11.7012 1.65404 12.1701L1.65408 12.1702C1.90521 12.7966 2.3854 13.2769 3.00859 13.525L3.00866 13.525C3.47588 13.7094 4.26322 13.7802 5.05882 13.8054C5.63314 13.8236 6.21435 13.818 6.68606 13.8135C6.86853 13.8117 7.03462 13.8101 7.17759 13.8101C7.32332 13.8101 7.49303 13.8119 7.67961 13.8137C8.14914 13.8185 8.72546 13.8243 9.29516 13.8066C10.0904 13.7818 10.8777 13.711 11.3466 13.525ZM3.58178 8.00023C3.58178 6.00938 5.1872 4.40362 7.17759 4.40362C9.16797 4.40362 10.7734 6.00938 10.7734 8.00023C10.7734 9.99107 9.16797 11.5968 7.17759 11.5968C5.1872 11.5968 3.58178 9.99107 3.58178 8.00023ZM4.80193 8.00023C4.80193 9.31082 5.87047 10.3764 7.17759 10.3764C8.48471 10.3764 9.55324 9.31082 9.55324 8.00023C9.55324 6.68966 8.48788 5.62405 7.17759 5.62405C5.8673 5.62405 4.80193 6.68966 4.80193 8.00023ZM11.7655 4.23258C11.7655 4.68922 11.3979 5.05386 10.9444 5.05386C10.4879 5.05386 10.1233 4.68618 10.1233 4.23258C10.1233 3.77904 10.491 3.41131 10.9444 3.41131C11.3978 3.41131 11.7655 3.77904 11.7655 4.23258Z"
                                                fill="white" stroke="white" stroke-width="0.0459559" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                            @if ($footer->instagram)
                                <li>
                                    <a href="{{ $footer->instagram }}">
                                        <svg width="13" height="16" viewBox="0 0 13 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M4.08029 7.40077L4.08212 7.39284L4.07856 7.38553C3.84184 6.89968 3.775 6.30752 3.775 5.7703C3.775 4.4978 4.51636 3.77083 5.25254 3.62174C5.62101 3.54713 5.98782 3.61709 6.26218 3.83452C6.53617 4.05167 6.72096 4.41831 6.72096 4.94322C6.72096 5.56378 6.51219 6.16596 6.30166 6.7677L6.29606 6.7837C6.08791 7.3786 5.87978 7.97343 5.87978 8.57851C5.87978 9.42002 6.57056 10.0056 7.37482 10.0056C8.48776 10.0056 9.21303 9.1968 9.6589 8.18263C10.105 7.16799 10.275 5.94088 10.275 5.08748C10.275 3.94208 9.87129 3.05554 9.19307 2.4556C8.51523 1.856 7.56543 1.54462 6.47629 1.54462C3.94073 1.54462 1.97978 3.38152 1.97978 5.97226C1.97978 6.59757 2.17018 7.0702 2.36009 7.42331C2.42496 7.54391 2.49014 7.6512 2.5473 7.7453C2.57352 7.78845 2.59805 7.82882 2.62009 7.86643C2.65537 7.92665 2.68427 7.97973 2.70433 8.02666C2.72447 8.0738 2.73493 8.11299 2.73493 8.14574C2.73493 8.26764 2.69016 8.54882 2.60588 8.7996C2.56378 8.92489 2.51236 9.04091 2.45282 9.12515C2.39273 9.21017 2.32822 9.25758 2.26085 9.25758C2.07579 9.25758 1.85805 9.17983 1.63463 9.02602C1.41147 8.87237 1.18399 8.64372 0.979829 8.34437C0.57158 7.74577 0.257353 6.86557 0.257353 5.74145C0.257353 2.20957 3.47334 0.0229779 6.73438 0.0229779C9.72636 0.0229779 12.4467 2.09298 12.4467 5.26059C12.4467 6.75264 12.0675 8.32513 11.2677 9.52386C10.4683 10.7218 9.2491 11.5465 7.56599 11.5465C7.16806 11.5465 6.71743 11.4463 6.31782 11.253C5.91812 11.0597 5.57128 10.7742 5.37827 10.4047L5.35031 10.3512L5.33562 10.4098C5.23972 10.7919 5.15751 11.131 5.08337 11.4368C4.8764 12.2904 4.73231 12.8847 4.52864 13.4332C4.25298 14.1756 3.8681 14.8338 3.0684 15.9357C3.04218 15.9455 3.02123 15.9542 3.0049 15.9609C2.99748 15.9639 2.99102 15.9666 2.98545 15.9688C2.9644 15.9771 2.95876 15.9774 2.95625 15.9769C2.95453 15.9765 2.95058 15.975 2.93731 15.9594C2.93364 15.9551 2.92931 15.9498 2.92424 15.9436C2.91424 15.9313 2.90138 15.9156 2.88522 15.8972C2.87075 15.7449 2.85452 15.5935 2.83835 15.4425C2.79028 14.9938 2.74265 14.5492 2.74265 14.0988C2.74265 13.1207 2.96672 11.9231 3.24627 10.7308C3.386 10.1348 3.53947 9.5406 3.68555 8.97616L3.69538 8.93819C3.83781 8.38786 3.97276 7.86647 4.08029 7.40077Z"
                                                fill="white" stroke="white" stroke-width="0.0459559" />
                                        </svg>
                                    </a>
                                </li>
                            @endif
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="tg-header-4-bootom tg-header-lg-space" id="header-sticky">
        <div class="container-fluid container-1790">
            <div class="row align-items-center">
                <div class="col-lg-8 col-5">
                    <div class="tgmenu__wrap d-flex align-items-center">
                        <div class="logo flex-auto">
                            <a href="{{ route('home') }}"><img src="{{ asset($general_setting->secondary_logo) }}"
                                    alt="Logo"></a>
                        </div>
                        <nav class="tgmenu__nav  ml-160 d-none d-xl-block">
                            <div class="tgmenu__navbar-wrap tgmenu__main-menu tgmenu__navbar-wrap-4 d-none d-xl-flex">
                                @include('components.common_navitems')
                            </div>
                        </nav>
                    </div>
                </div>
                <div class="col-lg-4 col-7">
                    <div
                        class="tg-menu-right-action tg-menu-right-action-2 tg-menu-4-right-action d-flex align-items-center justify-content-end">
                        <div class="tg-header-contact-info ml-20 d-flex align-items-center">
                            <span class="tg-header-contact-icon mr-5 d-none d-xl-block">
                                <svg width="21" height="21" viewBox="0 0 21 21" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M17.5747 15.8619L15.8138 17.6228C15.7656 17.6732 15.7236 17.7026 15.6627 17.7362C13.1757 19.0753 8.40326 16.5734 6.21009 14.2626C6.18698 14.2374 6.16809 14.2185 6.14502 14.1954C3.83427 12.0021 1.33257 7.22927 2.67157 4.7421C2.70515 4.68124 2.73453 4.64134 2.78491 4.5931L4.54573 2.83006C4.67586 2.69992 4.82067 2.64116 5.00114 2.64116H5.01583C5.20471 2.64327 5.35163 2.71044 5.47965 2.84895L7.75047 5.30044C7.98973 5.55651 7.98131 5.95109 7.73368 6.19877L6.26666 7.66589C5.85321 8.08148 5.67271 8.62926 5.75877 9.20856C5.94134 10.428 6.55419 11.574 7.63293 12.7095C7.65603 12.7326 7.67489 12.7536 7.69799 12.7746C8.83342 13.8534 9.97723 14.4663 11.1966 14.6488C11.7779 14.7349 12.3257 14.5544 12.7412 14.1388L14.2062 12.6738C14.4538 12.4261 14.8484 12.4177 15.1065 12.6549L17.5578 14.9259C17.6963 15.0539 17.7614 15.2008 17.7656 15.3897C17.7698 15.5785 17.709 15.7276 17.5747 15.8619ZM18.3428 14.0779L15.8914 11.8069C15.1779 11.1457 14.0781 11.1667 13.3897 11.8552L11.9227 13.3223C11.7695 13.4755 11.5827 13.5364 11.3687 13.5049C10.3907 13.358 9.45254 12.8459 8.49341 11.9349C8.485 11.9287 8.47872 11.9202 8.47031 11.9118C7.56155 10.9547 7.04946 10.0144 6.90257 9.03849C6.87109 8.82439 6.93195 8.6376 7.08518 8.48229L8.5522 7.01728C9.2406 6.32883 9.2616 5.22902 8.59837 4.51331L6.32966 2.06182C5.98758 1.69451 5.54055 1.49304 5.03893 1.48462C4.53735 1.47624 4.08401 1.65672 3.72725 2.01354L1.96638 3.77452C1.83836 3.90256 1.73971 4.0348 1.65368 4.19431C1.24444 4.95199 1.08073 5.8776 1.16679 6.93962C1.24023 7.85682 1.49628 8.86008 1.92863 9.91793C2.70726 11.8279 3.9854 13.742 5.34746 15.035C5.35794 15.0434 5.36425 15.0497 5.37263 15.0581C6.66546 16.4202 8.57737 17.7006 10.4872 18.4792C11.5471 18.9095 12.5482 19.1656 13.4653 19.2411C13.6479 19.2558 13.8263 19.2621 14.0005 19.2621C14.8421 19.2621 15.5829 19.0921 16.2105 18.7542C16.37 18.6681 16.5043 18.5695 16.6323 18.4415L18.3931 16.6784C18.7478 16.3237 18.9304 15.8704 18.922 15.3687C18.9115 14.8649 18.7122 14.42 18.3428 14.0779Z"
                                        fill="currentColor" />
                                </svg>
                            </span>
                            <div class="tg-header-contact-number d-none d-xl-block">
                                <span>{{ __('translate.Call Us') }}:</span>
                                <a href="tel:{{ $footer->phone }}">{{ $footer->phone }}</a>
                            </div>
                        </div>
                        <div class="tg-header-btn ml-30 d-none d-sm-block">
                            @guest('web')
                                <a class="tg-btn-header"
                                   href="https://wa.me/911244222401"
                                   target="_blank">
                                
                                    <span>
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.7 17.2C1.5 17.2 1.3 17.1 1.2 17C1.1 16.8 1 16.7 1 16.5C1 15.1 1.4 13.7 2.1 12.4C2.8 11.2 3.9 10.1 5.1 9.4C4.6 8.8 4.2 8 4 7.2C3.9 6.4 3.9 5.5 4.1 4.8C4.3 4 4.8 3.2 5.3 2.6C5.9 2 6.6 1.5 7.3 1.3C7.9 1.1 8.5 1 9.1 1C9.3 1 9.6 1 9.8 1C10.6 1.1 11.4 1.4 12.1 1.9C12.8 2.4 13.3 3 13.7 3.7C14.1 4.4 14.3 5.2 14.3 6.1C14.3 7.3 13.9 8.5 13.1 9.4C13.7 9.8 14.3 10.2 14.9 10.7C15.7 11.5 16.2 12.3 16.7 13.3C17.1 14.3 17.3 15.3 17.3 16.4C17.3 16.6 17.2 16.8 17.1 16.9C17 17 16.8 17.1 16.6 17.1C16.5 17.1 16.4 17.1 16.3 17C16.2 17 16.1 16.9 16.1 16.8C16 16.7 16 16.7 15.9 16.6C15.9 16.5 15.8 16.4 15.8 16.3C15.8 15.4 15.6 14.6 15.3 13.8C15 13 14.5 12.3 13.8 11.7C13.2 11.2 12.6 10.7 11.9 10.4C11.1 10.9 10.2 11.2 9.1 11.2C8.1 11.2 7.1 10.9 6.3 10.4C5.2 10.9 4.2 11.7 3.5 12.8C2.8 13.9 2.4 15.1 2.4 16.4C2.4 16.6 2.3 16.8 2.2 16.9C2.1 17.1 1.9 17.2 1.7 17.2ZM9.1 2.5C8.4 2.5 7.7 2.7 7.1 3.1C6.4 3.5 6 4.1 5.7 4.7C5.4 5.4 5.3 6.1 5.5 6.9C5.6 7.6 6 8.3 6.5 8.8C7 9.3 7.7 9.7 8.4 9.8C8.6 9.8 8.9 9.9 9.1 9.9C9.6 9.9 10.1 9.8 10.5 9.6C11.2 9.3 11.7 8.9 12.2 8.2C12.6 7.6 12.8 6.9 12.8 6.2C12.8 5.2 12.4 4.3 11.7 3.6C11 2.8 10.1 2.5 9.1 2.5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                
                                    {{ __('Enquiry') }}
                                </a>

                            @else
                                <a class="tg-btn-header"
                                    href="{{ Auth::guard('web')->user()->is_seller == 1 ? route('agency.dashboard') : route('user.dashboard') }}">
                                    <span>
                                        <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M1.7 17.2C1.5 17.2 1.3 17.1 1.2 17C1.1 16.8 1 16.7 1 16.5C1 15.1 1.4 13.7 2.1 12.4C2.8 11.2 3.9 10.1 5.1 9.4C4.6 8.8 4.2 8 4 7.2C3.9 6.4 3.9 5.5 4.1 4.8C4.3 4 4.8 3.2 5.3 2.6C5.9 2 6.6 1.5 7.3 1.3C7.9 1.1 8.5 1 9.1 1C9.3 1 9.6 1 9.8 1C10.6 1.1 11.4 1.4 12.1 1.9C12.8 2.4 13.3 3 13.7 3.7C14.1 4.4 14.3 5.2 14.3 6.1C14.3 7.3 13.9 8.5 13.1 9.4C13.7 9.8 14.3 10.2 14.9 10.7C15.7 11.5 16.2 12.3 16.7 13.3C17.1 14.3 17.3 15.3 17.3 16.4C17.3 16.6 17.2 16.8 17.1 16.9C17 17 16.8 17.1 16.6 17.1C16.5 17.1 16.4 17.1 16.3 17C16.2 17 16.1 16.9 16.1 16.8C16 16.7 16 16.7 15.9 16.6C15.9 16.5 15.8 16.4 15.8 16.3C15.8 15.4 15.6 14.6 15.3 13.8C15 13 14.5 12.3 13.8 11.7C13.2 11.2 12.6 10.7 11.9 10.4C11.1 10.9 10.2 11.2 9.1 11.2C8.1 11.2 7.1 10.9 6.3 10.4C5.2 10.9 4.2 11.7 3.5 12.8C2.8 13.9 2.4 15.1 2.4 16.4C2.4 16.6 2.3 16.8 2.2 16.9C2.1 17.1 1.9 17.2 1.7 17.2ZM9.1 2.5C8.4 2.5 7.7 2.7 7.1 3.1C6.4 3.5 6 4.1 5.7 4.7C5.4 5.4 5.3 6.1 5.5 6.9C5.6 7.6 6 8.3 6.5 8.8C7 9.3 7.7 9.7 8.4 9.8C8.6 9.8 8.9 9.9 9.1 9.9C9.6 9.9 10.1 9.8 10.5 9.6C11.2 9.3 11.7 8.9 12.2 8.2C12.6 7.6 12.8 6.9 12.8 6.2C12.8 5.2 12.4 4.3 11.7 3.6C11 2.8 10.1 2.5 9.1 2.5Z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    {{ __('translate.Dashboard') }}
                                </a>
                            @endguest
                        </div>
                        <div class="tg-header-menu-bar lh-1 p-relative ml-15">
                            <button class="tgmenu-offcanvas-open-btn menu-tigger d-none d-xl-block">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                            <button class="tgmenu-offcanvas-open-btn mobile-nav-toggler d-block d-xl-none">
                                <span></span>
                                <span></span>
                                <span></span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Menu  -->
    @include('components.common_mobile_menu')
    <!-- End Mobile Menu -->

    <!-- offCanvas-menu -->
    @include('components.common_offcanvas')
    <!-- offCanvas-menu-end -->
</header>
<!-- header-area-end -->


    <!-- header-area-end -->
    @yield('front-content')


    <!-- footer-area-start -->
    <!-- footer-area-start -->
    <footer>
        <div class="tg-footer-area tg-footer-su-wrapper tg-footer-su-2-wrapper pt-130 include-bg" data-background="{{ asset('frontend/assets/img/shape/work-bg2.png') }}">
           <div class="container">
                <div class="tg-footer-top mb-45">
                    <div class="row">
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget mb-40">
                                <div class="tg-footer-logo mb-20">
                                    <a href="{{ route('home') }}"><img src="{{ asset($general_setting->footer_logo) }}" alt=""></a>
                                </div>
                                <p class="mb-20">{{ $footer->about_us }}</p>
                                <div class="tg-footer-form mb-30">
                                    <form action="{{ route('store-newsletter') }}" method="POST">
                                        @csrf
                                        <input type="email" placeholder="Enter your mail" name="email">
                                        <button class="tg-footer-form-btn" type="submit">
                                            <svg width="22" height="17" viewBox="0 0 22 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M1.52514 8.47486H20.4749M20.4749 8.47486L13.5 1.5M20.4749 8.47486L13.5 15.4497" stroke="white" stroke-width="1.77778" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                                <div class="tg-footer-social">
                                    @if ($footer->facebook)
                                        <a href="{{ $footer->facebook }}"><i class="fa-brands fa-facebook-f"></i></a>
                                    @endif
                                    @if ($footer->twitter)
                                        <a href="{{ $footer->twitter }}"><i class="fa-brands fa-twitter"></i></a>
                                    @endif
                                    @if ($footer->instagram)
                                        <a href="{{ $footer->instagram }}"><i class="fa-brands fa-instagram"></i></a>
                                    @endif
                                    @if ($footer->linkedin)
                                        <a href="{{ $footer->linkedin }}"><i class="fa-brands fa-linkedin-in"></i></a>
                                    @endif
                                    @if ($footer->youtube)
                                        <a href="{{ $footer->youtube }}"><i class="fa-brands fa-youtube"></i></a>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-link ml-80 mb-40">
                                <h3 class="tg-footer-widget-title mb-25">{{ __('translate.Quick Links') }}</h3>
                                {!! wp_nav_menu([
                                    'theme_location' => 'footer_menu_1',
                                    'menu_class' => '',
                                    'container' => false,
                                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                    'menu_id' => 'main-nav',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-link mb-40">
                                <h3 class="tg-footer-widget-title mb-25">{{ __('translate.Utility Pages') }}</h3>
                                {!! wp_nav_menu([
                                    'theme_location' => 'footer_menu_2',
                                    'menu_class' => '',
                                    'container' => false,
                                    'items_wrap' => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                                    'menu_id' => 'main-nav',
                                ]) !!}
                            </div>
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-6 col-sm-6">
                            <div class="tg-footer-widget tg-footer-info mb-40">
                                <h3 class="tg-footer-widget-title mb-25">{{ __('translate.Information') }}</h3>
                                <ul>
                                    @if ($footer->address)
                                    <li>
                                        <a class="d-flex" href="https://www.google.com/maps/@41.6758525,-86.2531698,18.17z">
                                            <span class="mr-15">
                                                <svg width="20" height="24" viewBox="0 0 20 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M19.0013 10.0608C19.0013 16.8486 10.3346 22.6668 10.3346 22.6668C10.3346 22.6668 1.66797 16.8486 1.66797 10.0608C1.66797 7.74615 2.58106 5.52634 4.20638 3.88965C5.83169 2.25297 8.03609 1.3335 10.3346 1.3335C12.6332 1.3335 14.8376 2.25297 16.4629 3.88965C18.0882 5.52634 19.0013 7.74615 19.0013 10.0608Z" stroke="white" stroke-width="1.73333" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M10.3346 12.9699C11.9301 12.9699 13.2235 11.6674 13.2235 10.0608C13.2235 8.45412 11.9301 7.15168 10.3346 7.15168C8.73915 7.15168 7.44575 8.45412 7.44575 10.0608C7.44575 11.6674 8.73915 12.9699 10.3346 12.9699Z" stroke="white" stroke-width="1.73333" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            {{ $footer->address }}
                                        </a>
                                    </li>
                                    @endif
                                    @if ($footer->phone)
                                    <li>
                                        <a class="d-flex" href="tel:{{ $footer->phone }}">
                                            <span class="mr-15">
                                                <i class="fa-sharp text-white fa-solid fa-phone"></i>
                                            </span>
                                            {{ $footer->phone }}
                                        </a>
                                    </li>
                                    @endif
                                    @if ($footer->working_days)
                                        <li class="d-flex">
                                            <span class="mr-15">
                                                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M11.9987 5.60006V12.0001L16.2654 14.1334M22.6654 12.0002C22.6654 17.8912 17.8897 22.6668 11.9987 22.6668C6.10766 22.6668 1.33203 17.8912 1.33203 12.0002C1.33203 6.10912 6.10766 1.3335 11.9987 1.3335C17.8897 1.3335 22.6654 6.10912 22.6654 12.0002Z" stroke="white" stroke-width="1.6" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </span>
                                            <p class="mb-0">
                                                {{ $footer->working_days }}
                                            </p>
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="tg-footer-copyright text-center">
                <span>
                    {{ $footer->copyright }}
                </span>
            </div>
        </div>
    </footer>
    <!-- footer-area-end -->

    <!-- footer-area-end -->

    @if ($general_setting->tawk_status == 1)
        <script type="text/javascript">
            var Tawk_API = Tawk_API || {},
                Tawk_LoadStart = new Date();
            (function() {
                var s1 = document.createElement("script"),
                    s0 = document.getElementsByTagName("script")[0];
                s1.async = true;
                s1.src = '{{ $general_setting->tawk_chat_link }}';
                s1.charset = 'UTF-8';
                s1.setAttribute('crossorigin', '*');
                s0.parentNode.insertBefore(s1, s0);
            })();
        </script>
    @endif



    @if ($general_setting->cookie_consent_status == 1)
        <!-- common-modal start  -->
        <div class="common-modal cookie_consent_modal d-none bg-white">
            <button type="button" class="btn-close cookie_consent_close_btn" aria-label="Close"></button>

            <h5>{{ __('translate.Cookies') }}</h5>
            <p>{{ $general_setting->cookie_consent_message }}</p>


            <a href="javascript:;"
                class="td_btn td_style_1 td_type_3 td_radius_30 td_medium td_fs_14 report-modal-btn cookie_consent_accept_btn">
                <span class="td_btn_in td_accent_color">
                    <span>{{ __('translate.Accept') }}</span>
                </span>
            </a>

        </div>
        <!-- common-modal end  -->
    @endif


    <!-- Script -->
    <script src="{{ asset('global/js/jquery-3.7.1.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/imagesloaded.pkgd.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.odometer.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/jquery.appear.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/flatpickr.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/nice-select.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/ajax-form.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/wow.min.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/cart.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/main.js') }}"></script>
    <script src="{{ asset('frontend/assets/js/script.js') }}"></script>
    <script src="{{ asset('global/toastr/toastr.min.js') }}"></script>

    <script>
        (function($) {
            "use strict"
            $(document).ready(function() {

                const session_notify_message = @json(Session::get('message'));
                const demo_mode_message = @json(Session::get('demo_mode'));

                if (session_notify_message != null) {
                    const session_notify_type = @json(Session::get('alert-type', 'info'));
                    switch (session_notify_type) {
                        case 'info':
                            toastr.info(session_notify_message);
                            break;
                        case 'success':
                            toastr.success(session_notify_message);
                            break;
                        case 'warning':
                            toastr.warning(session_notify_message);
                            break;
                        case 'error':
                            toastr.error(session_notify_message);
                            break;
                    }
                }

                if (demo_mode_message != null) {
                    toastr.warning(
                        "{{ __('translate.All Language keywords are not implemented in the demo mode') }}"
                    );
                    toastr.info("{{ __('translate.Admin can translate every word from the admin panel') }}");
                }

                const validation_errors = @json($errors->all());

                if (validation_errors.length > 0) {
                    validation_errors.forEach(error => toastr.error(error));
                }

                if (localStorage.getItem('tourex-cookie') != '1') {
                    $('.cookie_consent_modal').removeClass('d-none');
                }

                $('.cookie_consent_close_btn').on('click', function() {
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.cookie_consent_accept_btn').on('click', function() {
                    localStorage.setItem('tourex-cookie', '1');
                    $('.cookie_consent_modal').addClass('d-none');
                });

                $('.before_auth_wishlist').on("click", function() {
                    toastr.error("{{ __('translate.Please login first') }}")
                });

                $(".currency_code").on('change', function() {
                    var currency_code = $(this).val();

                    window.location.href = "{{ route('currency-switcher') }}" + "?currency_code=" +
                        currency_code;
                });

                $(".language_code").on('change', function() {
                    var language_code = $(this).val();

                    window.location.href = "{{ route('language-switcher') }}" + "?lang_code=" +
                        language_code;
                });

            });
        })(jQuery);
    </script>


    @stack('js_section')


</body>

</html>
