<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->


    <!-- Fonts -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.css" rel="stylesheet">
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ asset('css/app.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
</head>
<body>
@auth()
    <div class="container">
        <div class="alert clearfix">
            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-inline">
                @csrf
                <button type="submit" class="btn btn-primary pull-right ">Выход</button>
            </form>
        </div>
    </div>
@endauth
<div>
<div class="row" >
    <nav id="sidebar" class="col sidebar">

        <ul class="nav flex-column vertical-nav">
            <li class="nav-item">
                <a class="nav-link bi bi-list" href="{{ route('categories') }}"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link bi bi-card-checklist" href="{{ route('articles') }}"></a>
            </li>
            <li class="nav-item">
                <a class="nav-link bi bi-people" href="{{ route('users') }}"></a>
            </li>
        </ul>
    </nav>
</div>

<script src="{{ asset('js/app.js') }}" defer></script>
<script src="{{ asset('node_modules/tinymce/tinymce.js') }}"></script>
<!-- Подключение библиотеки Switchery и JavaScript-кода -->
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/switchery/0.8.2/switchery.min.js"></script>

<div class="container">
    @yield('content')
</div>
</div>
</div>
</body>
</html>
