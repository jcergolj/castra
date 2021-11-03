@props(['disabled' => false])

<input
    {{ $disabled ? 'disabled' : '' }}
    {!! $attributes->merge(['class' => 'form-input w-full rounded-md focus:border-blue-600']) !!}
>
