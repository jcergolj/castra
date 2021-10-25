<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 px-4 text-center rounded-md w-full text-white text-sm']) }}
>
    {{ $slot }}
</button>
