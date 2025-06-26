<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @forelse ($properties as $property)
                    <livewire:property-card :property="$property" :key="$property->id" />
                @empty
                    <p class="text-gray-600 dark:text-gray-400">Aucune propriété.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>