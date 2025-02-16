<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" href="https://business.udemy.com/wp-content/themes/udemy-business-child/images/favicon-32x32.png" sizes="32x32"
        type="image/png">
    <link href="{{ url('css/bootstrap.5.3.2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ url('css/app.css') }}" type="text/css"/>
    <link rel="stylesheet" href="{{ url('css/color.css') }}" type="text/css"/>
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    {{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous"> --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @stack('css')
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        {{-- @yield('content') --}}
        {{ $slot }}
    </div>

    <script src="{{ url('./js/bootstrap.5.3.2_dist_js_bootstrap.bundle.min.js') }}"></script>
    <script>
        // =============== toggle theme dark/light ===============
        function toggleTheme() {
            themeText = 'dark';
            themeIconClass = "bi bi-brightness-high-fill";

            if (document.documentElement.getAttribute('data-bs-theme') == 'dark'){
                themeText = 'light';
                themeIconClass = "bi bi-moon-stars-fill";
            }

            document.documentElement.setAttribute('data-bs-theme', themeText);
            document.getElementById('theme-icon').className = themeIconClass;
        }
        // =============== toggle theme dark/light ===============
    </script>

    @stack('js')
</body>

</html>
