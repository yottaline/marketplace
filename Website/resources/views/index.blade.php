<!DOCTYPE html>
<html lang="ar" dir="rtl" style="direction: rtl;" direction="rtl">

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.rtl.min.css"
    integrity="sha384-dpuaG1suU0eT09tx5plTaGMLBsfDLzUCCUXOY2j/LSvXYuG6Bqs43ALlhIqAJVRb" crossorigin="anonymous">

<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link
    href="https://fonts.googleapis.com/css2?family=Lateef:wght@200;300;400;500;600;700;800&family=Noto+Nastaliq+Urdu:wght@400..700&display=swap"
    rel="stylesheet">

@include('layouts.header')

<body id="page-top">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark px-2 fixed-top">
        <div class="d-flex w-100 align-items-center container">
            <div class="me-auto">
                <a class="navbar-brand fw-bold" href="/">المتجر</a>
            </div>
            <div class="text-center">
                @yield('search')
            </div>


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
            <a href="\cart\" class="btn text-white position-relative">
                <i class="bi bi-cart4"></i>
                <span
                    class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-white text-success"
                    id="cartCount">
                    0
                    <span class="visually-hidden">unread messages</span>
                </span>
            </a>
            <a href="\account\" class="btn text-white bi bi-person-circle"></a>

        </div>
    </nav>
    <div class="text-white d-flex flex-row mt-5 w-100 p-2" style="background: #3a3a3a">
        <a href="\" class="btn text-white pl-2">كل المنتجات</a>
        @foreach (App\Models\Category::fetch() as $category)
            <a href="\subcategories\{{ $category->category_code }}"
                class="btn text-white pl-2">{{ $category->category_name }}</a>
        @endforeach

    </div>
    <div id="wrapper">
        @yield('content')
    </div>

    @include('layouts.footer')
</body>

@yield('js')

</html>
