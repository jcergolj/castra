@php
    $title = '401 - Unauthorized';
    $message = $exception->getMessage() ?: 'You are not authorized.';
@endphp

@include('layouts._error', ['title' => $title, 'message' => $message])
