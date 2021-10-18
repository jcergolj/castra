@php
    $title = '403 - Forbidden';
    $message = $exception->getMessage() ?: 'You are not allowed to see this page.';
@endphp

@include('layouts._error', ['title' => $title, 'message' => $message])
