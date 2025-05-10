<div {{ $attributes->merge(['class' => 'bg-white shadow-md rounded-lg p-4']) }}>
    <img src="{{ $image }}" alt="Propriété" class="w-full h-48 object-cover rounded-t-lg">
    <h3 class="text-lg font-bold mt-2">{{ $title }}</h3>
    <p class="text-gray-600">{{ $description }}</p>
    <p class="text-primary font-semibold mt-2">{{ $price }} €/nuit</p>
    <div class="mt-4">
        {{ $slot }}
    </div>
</div>