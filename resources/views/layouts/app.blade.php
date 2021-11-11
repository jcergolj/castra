<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta
        name="viewport"
        content="width=device-width, initial-scale=1, shrink-to-fit=no"
    >
    <meta
        http-equiv="x-ua-compatible"
        content="ie=edge"
    >
    <meta
        name="referrer"
        content="always"
    >
    <meta
        name="csrf-token"
        content="{{ csrf_token() }}"
    >

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link
        rel="stylesheet"
        href="{{ asset('css/app.css') }}"
    >

    <script
        src="{{ asset('js/app.js') }}"
        defer
    ></script>

    @stack('scripts')
</head>

<body>
    <x-status.layout></x-status.layout>

    {{ $slot }}
</body>

</html>
