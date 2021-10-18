@php
    $title = '500 - Server error';
    $message = $exception->getMessage() ?: 'Something went wrong on our end.';
@endphp

@include('layouts._error', ['title' => $title, 'message' => $message])
