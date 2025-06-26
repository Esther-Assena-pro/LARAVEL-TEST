<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Modifier la Réservation') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-white dark:bg-gray-900">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-lg rounded-lg p-6 border border-gray-200 dark:border-gray-700">
                @if (session('success'))
                    <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                        {{ session('success') }}
                    </div>
                @endif
                @if (session('error'))
                    <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                        {{ session('error') }}
                    </div>
                @endif

                <form action="{{ route('booking.update', $booking->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="mb-4">
                        <label for="property_name" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Propriété</label>
                        <input type="text" id="property_name" name="property_name" value="{{ $booking->property->name ?? 'Inconnue' }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                    </div>
                    <div class="mb-4">
                        <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de début</label>
                        <input type="date" id="start-date" name="start_date" value="{{ $booking->start_date }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                    </div>
                    <div class="mb-4">
                        <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Date de fin</label>
                        <input type="date" id="end-date" name="end_date" value="{{ $booking->end_date }}" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" required>
                    </div>
                    <div class="mb-4">
                        <label for="total_price" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Prix total</label>
                        <input type="text" id="total_price" name="total_price" value="{{ number_format($booking->total_price ?? 0, 2) }} €" class="mt-1 block w-full border border-gray-300 dark:border-gray-600 rounded-md bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" readonly>
                    </div>
                    <button type="submit" class="bg-blue-600 dark:bg-blue-700 text-white py-2 px-4 rounded-md hover:bg-blue-700 dark:hover:bg-blue-600 transition-colors duration-200">Mettre à jour</button>
                    <button type="button" id="delete-booking" class="ml-4 bg-red-600 dark:bg-red-700 text-white py-2 px-4 rounded-md hover:bg-red-700 dark:hover:bg-red-600 transition-colors duration-200">Supprimer</button>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr/dist/l10n/fr.js"></script> 
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            flatpickr(".datepicker", {
                minDate: "today",
                dateFormat: "Y-m-d",
                locale: "fr", 
                onChange: function(selectedDates, dateStr, instance) {
                    instance.element.form.submit();
                }
            });

            document.getElementById('delete-booking').addEventListener('click', function(e) {
                if (confirm('Êtes-vous sûr de vouloir supprimer cette réservation ? Cette action est irréversible.')) {
                    window.location.href = "{{ route('booking.destroy', $booking->id) }}";
                }
            });
        });
    </script>
    @endpush
</x-app-layout>