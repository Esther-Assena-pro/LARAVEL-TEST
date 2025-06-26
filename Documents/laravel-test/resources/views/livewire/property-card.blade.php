<div class="bg-white dark:bg-gray-800 shadow-xl rounded-xl overflow-hidden transform transition-all duration-300 hover:shadow-2xl hover:-translate-y-2">
    <div class="relative">
        @php
            $imagePath = isset($property) && $property->image ? $property->image : 'images/properties/placeholder.jpg';
            $imagePath = strpos($imagePath, 'images/properties/') === 0 ? $imagePath : 'images/properties/' . $imagePath;
            $fullPath = public_path($imagePath);
            $imageExists = file_exists($fullPath);
        @endphp
        @if($imageExists)
            <img src="{{ asset($imagePath) }}" alt="{{ isset($property) ? ($property->name ?? 'Propriété') : 'Propriété' }}" class="w-full h-48 object-cover transition-transform duration-300 hover:scale-105" onerror="this.src='{{ asset('images/placeholder.jpg') }}'">
            <div class="absolute inset-0 bg-gradient-to-t from-black/40 to-transparent"></div>
        @else
            <div class="w-full h-48 bg-gray-200 dark:bg-gray-700 flex items-center justify-center">
                <span class="text-gray-500 dark:text-gray-400">Image non disponible (Path: {{ $imagePath }})</span>
            </div>
        @endif
    </div>
    <div class="p-6">
        <h3 class="text-xl font-bold text-gray-900 dark:text-white mb-3 tracking-tight">{{ isset($property) ? ($property->name ?? 'Propriété sans nom') : 'Propriété sans nom' }}</h3>
        <p class="text-gray-600 dark:text-gray-300 text-sm leading-relaxed mb-4 line-clamp-2">{{ isset($property) ? ($property->description ?? 'Aucune description disponible') : 'Aucune description disponible' }}</p>
        <p class="text-gray-700 dark:text-gray-200 text-lg font-medium mb-4">{{ __('Prix par nuit') }}: <span class="text-blue-600 dark:text-blue-300">{{ isset($property) ? number_format($property->price_per_night ?? 0, 2) : '0.00' }} €</span></p>
        <div class="flex justify-between items-center">
            @if(isset($property) && $property->id)
                @auth
                    <a href="{{ route('properties.show', ['id' => $property->id]) }}" class="text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-200 font-medium transition-colors duration-200">
                        {{ __('Voir les détails') }}
                    </a>
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-opacity-50">
                        {{ __('Réserver') }}
                    </a>
                @else
                    <a href="{{ route('properties.show', ['id' => $property->id]) }}" class="text-blue-600 dark:text-blue-300 hover:text-blue-800 dark:hover:text-blue-200 font-medium transition-colors duration-200">
                        {{ __('Voir les détails') }}
                    </a>
                @endauth
            @else
                <span class="text-gray-600 dark:text-gray-400">{{ __('Détails non disponibles') }}</span>
                <span class="text-gray-600 dark:text-gray-400">{{ __('Réserver non disponible') }}</span>
            @endif
        </div>
    </div>  
</div>