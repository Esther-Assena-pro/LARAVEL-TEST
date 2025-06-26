<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Détails de la Propriété') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gradient-to-b from-white via-gray-50 to-white dark:from-gray-900 dark:via-gray-800 dark:to-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl">
                <div class="relative">
                    @php
                        $imagePath = $property->image ?? 'images/properties/placeholder.jpg';
                        $imagePath = strpos($imagePath, 'images/properties/') === 0 ? $imagePath : 'images/properties/' . $imagePath;
                        $fullPath = public_path($imagePath);
                        $imageExists = file_exists($fullPath);
                    @endphp
                    @if($imageExists)
                        <img src="{{ asset($imagePath) }}" alt="{{ $property->name }}" class="w-full h-80 object-cover transition-transform duration-300 hover:scale-105" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/50 to-transparent"></div>
                    @else
                        <div class="w-full h-80 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                            <span class="text-gray-500 dark:text-gray-400">Image non disponible (Path: {{ $imagePath }})</span>
                        </div>
                    @endif
                </div>
                <div class="p-8">
                    <h3 class="text-3xl font-bold text-gray-900 dark:text-white mb-4 tracking-tight">{{ $property->name }}</h3>
                    <p class="text-gray-600 dark:text-gray-300 text-lg leading-relaxed mb-6">{{ $property->description }}</p>
                    <p class="text-gray-700 dark:text-gray-200 text-xl font-medium mb-6">{{ __('Prix par nuit') }}: <span class="text-blue-600 dark:text-blue-300">{{ number_format($property->price_per_night, 2) }} €</span></p>
                    <div class="flex space-x-4">
                        <a href="{{ route('dashboard') }}" class="inline-flex items-center px-6 py-3 bg-blue-600 dark:bg-blue-700 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 dark:hover:bg-blue-600 transition-all duration-300 transform hover:-translate-y-1">
                            {{ __('Réserver') }}
                        </a>
                        <button class="inline-flex items-center px-6 py-3 bg-gray-200 dark:bg-gray-700 text-gray-800 dark:text-gray-200 font-semibold rounded-lg shadow-md hover:bg-gray-300 dark:hover:bg-gray-600 transition-all duration-300 transform hover:-translate-y-1" onclick="history.back()">
                            {{ __('Retour') }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>