<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
    @if (session()->has('success'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4 rounded-md" role="alert">
            <p class="font-semibold">{{ session('success') }}</p>
            <p class="mt-2 italic text-sm">"Le voyage commence là où le confort s’arrête."</p>
        </div>
    @endif
    @if (session()->has('error'))
        <div class="bg-red-100 dark:bg-red-900 border-l-4 border-red-500 text-red-700 dark:text-red-300 p-4 mb-4 rounded-md" role="alert">
            <p class="font-semibold">{{ session('error') }}</p>
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">{{ __('Réservez votre séjour') }}</h2>

    <form wire:submit.prevent="saveBooking" class="space-y-4">
        <div>
            <label for="property_id" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Propriété') }}</label>
            <select wire:model="property_id" id="property_id" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Sélectionnez une propriété') }}</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
            @error('property_id') <span class="text-red-500 dark:text-red-400 text-sm">{{ $errors->first('property_id') }}</span> @enderror
        </div>

        <div>
            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date de début') }}</label>
            <input type="text" wire:model.debounce.500ms="start_date" id="start_date" class="flatpickr mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="{{ __('Sélectionnez une date') }}" data-disabled-dates="{{ json_encode($disabledDates ?? []) }}">
            @error('start_date') <span class="text-red-500 dark:text-red-400 text-sm">{{ $errors->first('start_date') }}</span> @enderror
        </div>

        <div>
            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Date de fin') }}</label>
            <input type="text" wire:model.debounce.500ms="end_date" id="end_date" class="flatpickr mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="{{ __('Sélectionnez une date') }}" data-disabled-dates="{{ json_encode($disabledDates ?? []) }}">
            @error('end_date') <span class="text-red-500 dark:text-red-400 text-sm">{{ $errors->first('end_date') }}</span> @enderror
        </div>

        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Prix total (€):') }} {{ number_format($total_price ?? 0, 2) }} €
        </div>

        <div class="flex space-x-2">
            @if (!is_null($editingId)) 
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Valider la réservation') }}</button>
                <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">{{ __('Annuler') }}</button>
            @else
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Réserver maintenant') }}</button>
            @endif
            @if (isset($message) && $message && !session()->has('success') && !session()->has('error'))
                <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span>
            @endif
            @error('overlap') <span class="text-red-500 dark:text-red-400 text-sm">{{ $errors->first('overlap') }}</span> @enderror
        </div>
    </form>

    @if ($bookings->isNotEmpty())
        <h3 class="text-xl font-bold mt-6 mb-4 text-gray-900 dark:text-gray-100">{{ __('Vos réservations en cours') }}</h3>
    <div class="space-y-4">
        @foreach ($bookings as $booking)
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-md p-4">
                <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Propriété:') }}</strong> {{ $booking->property->name }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Dates:') }}</strong> {{ $booking->start_date }} - {{ $booking->end_date }}</p>
                <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Prix total (€):') }}</strong> {{ number_format($booking->total_price ?? 0, 2) }} €</p>
                <div class="mt-2 flex space-x-2">
                    <button wire:click="editBooking({{ $booking->id }})" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Modifier') }}</button>
                    <button wire:click="deleteBooking({{ $booking->id }})" class="text-red-500 dark:text-red-400 hover:underline">{{ __('Supprimer') }}</button>
                </div>
            </div>
        @endforeach
    </div>
@endif

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                disable: JSON.parse(document.querySelector('.flatpickr').getAttribute('data-disabled-dates') || '[]'),
                onChange: function(selectedDates, dateStr, instance) {
                    const startDateInput = document.getElementById('start_date');
                    const endDateInput = document.getElementById('end_date');
                    if (instance.element.id === 'start_date' && selectedDates.length > 0) {
                        endDateInput._flatpickr.set('minDate', dateStr);
                        @this.set('start_date', dateStr, true); 
                    } else if (instance.element.id === 'end_date' && selectedDates.length > 0) {
                        @this.set('end_date', dateStr, true); 
                    }
                }
            });
        });
    </script>
    @endpush
</div>