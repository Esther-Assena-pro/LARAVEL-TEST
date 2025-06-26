<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Propriétés') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($properties as $property)
                    <livewire:property-card :property="$property" :key="$property->id" />
                @empty
                    <p class="text-gray-600 dark:text-gray-300">Aucune propriété disponible.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>