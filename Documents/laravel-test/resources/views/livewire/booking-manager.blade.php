<div class="max-w-2xl mx-auto p-6 bg-white dark:bg-gray-800 rounded-md shadow-md">
    @if (session()->has('message'))
        <div class="bg-green-100 dark:bg-green-900 border-l-4 border-green-500 text-green-700 dark:text-green-300 p-4 mb-4" role="alert">
            {{ session('message') }}
        </div>
    @endif

    <h2 class="text-2xl font-bold mb-4 text-gray-900 dark:text-gray-100">{{ __('Book your stay') }}</h2>

    <form wire:submit.prevent="saveBooking" class="space-y-4">
        <div>
            <label for="propertyId" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Property') }}</label>
            <select wire:model="propertyId" id="propertyId" class="mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100">
                <option value="">{{ __('Select a property') }}</option>
                @foreach ($properties as $property)
                    <option value="{{ $property->id }}">{{ $property->name }}</option>
                @endforeach
            </select>
            @error('propertyId') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="startDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('Start date') }}</label>
            <input type="text" wire:model.debounce.500ms="startDate" id="startDate" class="flatpickr mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="{{ __('Select a date') }}">
            @error('startDate') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <div>
            <label for="endDate" class="block text-sm font-medium text-gray-700 dark:text-gray-300">{{ __('End date') }}</label>
            <input type="text" wire:model.debounce.500ms="endDate" id="endDate" class="flatpickr mt-1 block w-full border-gray-300 dark:border-gray-600 rounded-md shadow-sm focus:ring-blue-600 focus:border-blue-600 bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100" placeholder="{{ __('Select a date') }}">
            @error('endDate') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>

        <div class="text-lg font-semibold text-gray-900 dark:text-gray-100">
            {{ __('Total price (€)') }}: {{ number_format($totalPrice ?? 0, 2) }} € 
        </div>

        <div class="flex space-x-2">
            @if (!is_null($editingId)) 
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Validate booking') }}</button>
                <button wire:click="cancelEdit" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">{{ __('Cancel') }}</button>
            @else
                <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">{{ __('Book now') }}</button>
            @endif
            @error('overlap') <span class="text-red-500 dark:text-red-400 text-sm">{{ $message }}</span> @enderror
        </div>
    </form>

    @if ($bookings->isNotEmpty())
        <h3 class="text-xl font-bold mt-6 mb-4 text-gray-900 dark:text-gray-100">{{ __('Your current bookings') }}</h3>
        <div class="space-y-4">
            @foreach ($bookings as $booking)
                <div class="bg-white dark:bg-gray-800 shadow-md rounded-md p-4">
                    <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Property') }}:</strong> {{ $booking->property->name }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Dates') }}:</strong> {{ $booking->start_date }} - {{ $booking->end_date }}</p>
                    <p class="text-gray-700 dark:text-gray-300"><strong>{{ __('Total price (€)') }}:</strong> {{ number_format($booking->total_price ?? 0, 2) }} €</p>
                    <div class="mt-2 flex space-x-2">
                        <button wire:click="edit({{ $booking->id }})" class="text-blue-600 dark:text-blue-400 hover:underline">{{ __('Modifier') }}</button>
                        <button wire:click="delete({{ $booking->id }})" class="text-red-500 dark:text-red-400 hover:underline">{{ __('Supprimer') }}</button>
                    </div>
                </div>
            @endforeach
        </div>
    @endif
</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            flatpickr('.flatpickr', {
                dateFormat: 'Y-m-d',
                minDate: 'today',
                onChange: function(selectedDates, dateStr, instance) {
                    const startDateInput = document.getElementById('startDate');
                    const endDateInput = document.getElementById('endDate');
                    if (instance.element.id === 'startDate' && selectedDates.length > 0) {
                        endDateInput._flatpickr.set('minDate', dateStr);
                        Livewire.emit('updateStartDate', dateStr);
                    } else if (instance.element.id === 'endDate' && selectedDates.length > 0) {
                        Livewire.emit('updateEndDate', dateStr);
                    }
                }
            });
        });
    </script>
@endpush