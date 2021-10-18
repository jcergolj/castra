@php
    $title = 'Page not Found';
    $message = $exception->getMessage() ?: 'Page you are looking for doesn\'t exists.';
@endphp

@include('layouts._error', ['title' => $title, 'message' => $message])
