<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'AppTrackEasy') }}</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/sass/custom-navbar.scss', 'resources/sass/style.scss' , 'resources/js/app.js',])
    

</head>
<body>
    <div id="app">
        @include('includes.navbar')


        <main class="py-4">
            <div class="alert alert-danger" id="validationErrors" style="display: none;"></div>
            <div class="alert alert-success" id="successMessage" style="display: none;"></div>

            @yield('content')
        </main>
    </div>
    <script>
        $(document).ready(function(){
            console.log('hello there');
        })
    </script>
</body>
</html>
