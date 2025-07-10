<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    {{--CSRF Token--}}
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', config('app.name', '@Master Layout'))</title>
    @include('partial.head')
    @include('partial.scripts')
    <style>
        .spinner {
            width: 60px;
            height: 60px;
            border: 6px solid #000; /* Black border */
            border-top-color: #2E7FEC; /* Red top border */
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            margin: 100px auto;
            z-index: 9999;
            position: fixed;
            left: 100vh;
            top: 25vh;
            display: none;
        }

        @keyframes spin {
            to {
            transform: rotate(360deg);
            }
        }
    </style>
</head>
<body>
    <div class="spinner"></div>
    @include('partial.header')
   
    @yield('content')

    @include('partial.footer')

    
</body>
</html>
