<button {{ $attributes->merge(['class' => 'px-4 py-2 rounded text-white bg-primary hover:bg-secondary transition']) }}>
    {{ $slot }}
</button>