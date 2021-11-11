<svg
    xmlns="http://www.w3.org/2000/svg"
    fill="none"
    stroke="currentColor"
    {{ $attributes->merge(['viewBox' => '0 0 24 24', 'class' => 'hidden']) }}
    style="display:none"
>
    {{ $slot }}
</svg>
