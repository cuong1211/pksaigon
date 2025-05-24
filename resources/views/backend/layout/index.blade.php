<!DOCTYPE html>
<!--
Author: Keenthemes
Product Name: Metronic - Bootstrap 5 HTML, VueJS, React, Angular & Laravel Admin Dashboard Theme
Purchase: https://1.envato.market/EA4JP
Website: http://www.keenthemes.com
Contact: support@keenthemes.com
Follow: www.twitter.com/keenthemes
Dribbble: www.dribbble.com/keenthemes
Like: www.facebook.com/keenthemes
License: For each use you must have a valid license purchased only from above link in order to legally use the theme for your project.
-->
<html lang="en">
<!--begin::Head-->

<head>
    @include('backend.layout.source')
    @stack('csscustom')
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_body" class="header-tablet-and-mobile-fixed aside-enabled">
    <!--begin::Root-->
    <div class="d-flex flex-column flex-root"  id="nonPrintable">
        <!--begin::Page-->
        <div class="page d-flex flex-row flex-column-fluid">
            <!--begin::Aside-->
            @include('backend.layout.sidebar')
            <!--end::Aside-->
            <!--begin::Wrapper-->
            <div class="wrapper d-flex flex-column flex-row-fluid" id="kt_wrapper">
                <!--begin::Header-->
                @include('backend.layout.header')
                <!--end::Header-->
                <!--begin::Main-->
                @yield('content')
                <!--begin::Footer-->
                @include('backend.layout.footer')
                <!--end::Footer-->
                <script>
                    var hostUrl = "assets/";
                </script>
                <!--begin::Javascript-->
                <!--begin::Global Javascript Bundle(used by all pages)-->
                <!--end::Page Custom Javascript-->
                <!--end::Javascript-->
                @yield('js')
                @stack('jscustom')
            </div>
            <!--end::Wrapper-->
        </div>
        <!--end::Page-->
    </div>
    <!--end::Root-->
</body>
<!--end::Body-->

</html>
