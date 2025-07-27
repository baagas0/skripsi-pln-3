<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <base href="{{ url('/') }}">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>AMATI - Aplikasi Manajemen Pembelajaran dan Sertifikasi</title>
    <meta name="description" content="AMATI - Aplikasi Manajemen Pembelajaran dan Sertifikasi" />
    <meta name="keywords" content="AMATI, Aplikasi Manajemen Pembelajaran dan Sertifikasi, PLN, semarang, jawa tengah" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta charset="utf-8" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:type" content="article" />
    <meta property="og:title" content="AMATI - Aplikasi Manajemen Pembelajaran dan Sertifikasi" />
    <meta property="og:url" content="{{ url('/') }}" />
    <meta property="og:site_name" content="AMATI | Aplikasi Manajemen Pembelajaran dan Sertifikasi" />
    <link rel="canonical" href="{{ url('/') }}" />
    <link rel="shortcut icon" href="{{ asset('media/logos/favicon.ico') }}" />

    <!--begin::Fonts-->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700" />
    <!--end::Fonts-->
    <!--begin::Global Stylesheets Bundle(used by all pages)-->
    <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
    <!--end::Global Stylesheets Bundle-->
    <!--end::Layout Themes-->

</head>

<body id="kt_body" class="bg-body">
    <!--begin::Main-->
    <div class="d-flex flex-column flex-root">
        <!--begin::Authentication - Signup Free Trial-->
        <div class="d-flex flex-column flex-xl-row flex-column-fluid" style="background-image: url({{ asset('assets/media/bg-login.jpg') }});background-size: cover;">
            <!--begin::Aside-->
            <div class="d-flex flex-column flex-lg-row-auto w-xl-300px positon-xl-relative" >
                <!--begin::Wrapper-->
                <div class="d-flex flex-column position-xl-fixed top-0 bottom-0 w-xl-800px scroll-y d-flex align-items-center justify-content-center">
                    <!--begin::Content-->
                    <div class="d-flex flex-row-fluid flex-column text-center justify-content-center h-100">
                        <!--begin::Logo-->
                        <a href="{{ route('login') }}" class="py-3 mb-2">
                            <img alt="Logo" src="{{ asset('assets/media/logo-pln.png') }}" class="h-60px" />
                        </a>
                        <!--end::Logo-->
                        <!--begin::Title-->
                        <h1 class="fw-bolder fs-5qx pb-2" style="color: #ffffff;">AMATI</h1>
                        <!--end::Title-->
                        <!--begin::Description-->
                        <p class="fw-bold fs-1qx mt-0" style="color: #ffffff;">(Aplikasi Manajemen Pembelajaran dan Sertifikasi)</p>
                        <!--end::Description-->
                    </div>
                    <!--end::Content-->
                    <!--begin::Illustration-->
                    {{-- <div class="d-flex flex-row-auto bgi-no-repeat bgi-position-x-center bgi-size-contain bgi-position-y-bottom min-h-100px min-h-lg-350px" style="background-image: url(assets/media/illustrations/sketchy-1/10-dark.png)"></div> --}}
                    <!--end::Illustration-->
                </div>
                <!--end::Wrapper-->
            </div>
            <!--begin::Aside-->
            <!--begin::Content-->
            <div class="d-flex flex-column flex-lg-row-fluid d-flex align-items-center justify-content-center">
                @yield('content')
            </div>
            <!--end::Right Content-->
        </div>
        
        <!--end::Authentication - Signup Free Trial-->
    </div>
    <!--end::Main-->
    <script>
        var hostUrl = "assets/";
    </script>
    <!--begin::Javascript-->
    <!--begin::Global Javascript Bundle(used by all pages)-->
    <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
    <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
    <!--end::Global Javascript Bundle-->
    <!--begin::Page Custom Javascript(used by this page)-->
    <script src="{{ asset('assets/js/login.js') }}"></script>
    <!--end::Page Custom Javascript-->
    <script>
        const Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000
        });
    </script>
    @if (session()->has('error'))
        <script>
            Toast.fire({
                icon: 'error',
                title: "{{ session()->get('error') }}"
            })
        </script>
    @endif
    <!--end::Javascript-->
</body>
<!--end::Body-->

</html>