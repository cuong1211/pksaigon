<!DOCTYPE html>
<html lang="zxx">

<head>
    @include('frontend.layouts.source')
    @stack('csscustom')
</head>

<body>
    @include('frontend.layouts.header')
    <section>
        @yield('content')
    </section>
    <!-- Footer Start -->
    @include('frontend.layouts.footer')
    <!-- Footer End -->
    @yield('js')
    @stack('jscustom')

</body>

</html>
