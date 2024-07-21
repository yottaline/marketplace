<!DOCTYPE html>
@if (app()->getLocale() == 'ar')
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="rtl" style="direction: rtl;" direction="rtl">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
        integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">
@else
    <html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="ltr" style="direction: ltr;" direction="ltr">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
@endif

@include('layouts.header')

<body id="page-top">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark px-2 fixed-top">
        <div class="d-flex w-100 align-items-center">
            <div class="me-auto">
                <a class="h5 bi bi-list link-light p-2 m-0 me-3" role="button" data-bs-toggle="offcanvas"
                    data-bs-target="#navOffcanvas" aria-controls="navOffcanvas"></a>
                <a class="navbar-brand fw-bold" href="/">{{ __('Dashboard') }}</a>
            </div>
            @yield('search')

            <!-- Example split danger button -->
            <div class="btn-group">
                <button type="button" class="btn bi bi-translate text-white dropdown-toggle" data-bs-toggle="dropdown"
                    aria-expanded="false">
                </button>
                <ul class="dropdown-menu">
                    <li>
                        <a class="dropdown-item" href="lang/en">EN</a>
                    </li>
                    <li><a class="dropdown-item" href="lang/ar">AR</a></li>
                </ul>
            </div>

        </div>
    </nav>

    @include('layouts.sidebar')

    <div id="wrapper">
        @yield('content')
    </div>

    @include('layouts.footer')
</body>

@yield('js')

</html>
