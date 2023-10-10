<!DOCTYPE html>
<html lang="en" data-bs-theme="light">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>
    <link rel="icon" href="https://getbootstrap.com/docs/5.3/assets/img/favicons/favicon-32x32.png" sizes="32x32"
        type="image/png">
    {{-- <link href="{{ url('css/bootstrap.5.3.2.min.css') }}" rel="stylesheet" type="text/css" />
    <link rel="stylesheet" href="{{ url('css/app.css') }}" type="text/css"/> --}}
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <style>
        p {
            font-size: 18px;
        }

        .question-text {
            text-align: justify;
        }

        .question--answer-item input.form-check-input {
            margin-left: 2px;
            margin-top: 0;
        }

        .question--answer-item label {
            width: 100%;
            cursor: pointer;
            margin-left: 8px;
        }

        .question--answer-item {
            margin: 12px 0;
            padding-left: 10px;
            display: flex;
            align-items: center;
        }

        .answer-item--text p {
            margin: 0;
            padding: 4px;
        }
    </style>

    @stack('css')
</head>

<body>
    @include('layouts.navbar')

    <div class="container">
        @yield('content')
    </div>

    <script src="{{ url('./js/bootstrap.5.3.2_dist_js_bootstrap.bundle.min.js') }}"></script>
    <script>
        function toggleTheme() {
            text = document.documentElement.getAttribute('data-bs-theme') == 'dark' ? 'light' : 'dark';

            document.documentElement.setAttribute('data-bs-theme', text);
            document.getElementById('btnSwitch').classList.toggle('btn-dark')
        }
    </script>

    {{-- count down timer --}}

    <script>
        // Set the date we're counting down to
        const currentDate = new Date();
        var timeItem = +document.getElementById("demo").getAttribute('data-time');
        var countDownDate = new Date(currentDate.getTime() + timeItem * 60000).getTime();

        // Update the count down every 1 second
        var x = setInterval(function() {

            // Get today's date and time
            var now = new Date().getTime();

            // Find the distance between now and the count down date
            var distance = countDownDate - now;

            // Time calculations for days, hours, minutes and seconds
            // var days = Math.floor(distance / (1000 * 60 * 60 * 24));
            var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
            var seconds = Math.floor((distance % (1000 * 60)) / 1000);

            // Display the result in the element with id="demo"
            document.getElementById("demo").innerHTML = hours + "h " +
                minutes + "m " + seconds + "s ";

            // If the count down is finished, write some text
            if (distance < 0) {
                clearInterval(x);
                document.getElementById("demo").innerHTML = "EXPIRED";
            }
        }, 1000);
    </script>


    @stack('js')
</body>

</html>
