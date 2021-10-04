<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- CSRF Token -->
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@400;600;700&display=swap" rel="stylesheet">

        <link rel="stylesheet" href="{{ asset('lib/@fortawesome/fontawesome-free/css/all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('lib/animate.css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('css/app.css') }}">
        <link rel="stylesheet" href="{{ asset('css/cassie.css') }}">

    </head>
    <body style="background-color: #DEE2FF">
        <div id="app">
            <nav class="navbar navbar-expand-md navbar-light bg-white shadow-sm">
                <div class="container">
                    <a class="navbar-brand" href="{{ url('/') }}">
                        Simple
                        <span class="font-weight-bold text-success">C</span>
                        <span class="font-weight-bold text-primary">R</span>
                        <span class="font-weight-bold text-warning">U</span>
                        <span class="font-weight-bold text-danger">D</span>
                        Applications
                    </a>
                </div>
            </nav>
            <main-component></main-component>
        </div>


        <script src="{{ asset('js/app.js') }}"></script>
    </body>
</html>
