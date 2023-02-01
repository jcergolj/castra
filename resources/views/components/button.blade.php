<button
    {{ $attributes->merge(['type' => 'submit', 'class' => 'py-2 px-4 text-center rounded-md bg-blue-600 hover:bg-blue-500 text-white']) }}>
    {{ $slot }}
</button>
