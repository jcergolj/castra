@php
    $title = '429 - Too many requests';
    $message = $exception->getMessage() ?: 'Too many requests.';
@endphp

@include('layouts._error', ['title' => $title, 'message' => $message])
