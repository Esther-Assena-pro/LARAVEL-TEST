<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Booking;
use App\Models\Property;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class BookingManager extends Component
{
    public $property_id;
    public $start_date;
    public $end_date;
    public $total_price = 0;
    public $message = ''; 
    public $showModal = false;
    public $isFormValid = false; // Statut du formulaire
    public $bookings; // Pour stocker mes réservations

    protected $rules = [
        'property_id' => 'required|exists:properties,id',
        'start_date' => 'required|date|after_or_equal:today',
        'end_date' => 'required|date|after:start_date',
    ];

    protected $messages = [
        'property_id.required' => 'Veuillez sélectionner une propriété.',
        'start_date.required' => 'La date de début est obligatoire.',
        'start_date.date' => 'La date doit être valide.',
        'start_date.after_or_equal' => 'La date de début doit être aujourd’hui ou plus tard.',
        'end_date.required' => 'La date de fin est obligatoire.',
        'end_date.date' => 'La date doit être valide.',
        'end_date.after' => 'La date de fin doit être après la date de début.',
    ];

    public function mount()
    {
        $this->reset(['message', 'start_date', 'end_date', 'total_price', 'isFormValid']);
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $this->bookings = Booking::where('user_id', auth()->id() ?? 1)->get();
    }

    public function openModal($propertyId)
    {
        $this->reset(['message', 'start_date', 'end_date', 'total_price', 'isFormValid']);
        $this->property_id = $propertyId;
        $this->start_date = Carbon::today()->toDateString();
        $this->end_date = Carbon::today()->addDay()->toDateString();
        $this->showModal = true;
        $this->calculatePrice();
        $this->checkFormValidity();
        $this->emit('loadDisabledDates', $this->getDisabledDates());
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->reset(['message', 'start_date', 'end_date', 'total_price', 'isFormValid']);
    }

    public function updated($property)
    {
        Log::debug('updated triggered for:', ['property' => $property, 'values' => [
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]]);
        if (in_array($property, ['property_id', 'start_date', 'end_date'])) {
            $this->calculatePrice();
            $this->validateOnly($property); 
            $this->checkFormValidity(); 
            if ($property === 'property_id' || $property === 'start_date' || $property === 'end_date') {
                $this->emit('loadDisabledDates', $this->getDisabledDates());
            }
        }
    }

    public function calculatePrice()
    {
        Log::debug('calculatePrice called with:', [
            'property_id' => $this->property_id,
            'start_date' => $this->start_date,
            'end_date' => $this->end_date,
        ]);
        $this->total_price = 0; // Réinitialisation
        try {
            if ($this->property_id && $this->start_date && $this->end_date) {
                $property = Property::find($this->property_id);
                if ($property && $property->price_per_night !== null) {
                    $start = new \DateTime($this->start_date);
                    $end = new \DateTime($this->end_date);
                    if ($end >= $start) {
                        $nights = max(1, $start->diff($end)->days);
                        $this->total_price = $property->price_per_night * $nights;
                        Log::info('Price calculated:', ['nights' => $nights, 'price_per_night' => $property->price_per_night, 'total_price' => $this->total_price]);
                    } else {
                        $this->addError('end_date', 'La date de fin doit être après la date de début.');
                        Log::warning('Invalid date range:', ['start' => $start->format('Y-m-d'), 'end' => $end->format('Y-m-d')]);
                    }
                } else {
                    Log::error('Property not found or price_per_night missing:', ['property_id' => $this->property_id, 'property' => $property]);
                }
            } else {
                Log::debug('Missing required fields:', ['property_id' => $this->property_id, 'start_date' => $this->start_date, 'end_date' => $this->end_date]);
            }
        } catch (\Exception $e) {
            Log::error('Exception in calculatePrice:', ['message' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
        }
    }

    public function checkFormValidity()
    {
        $this->isFormValid = !empty($this->property_id) && !empty($this->start_date) && !empty($this->end_date) && $this->getErrorBag()->isEmpty();
        Log::debug('Form validity checked:', ['isFormValid' => $this->isFormValid, 'errors' => $this->getErrorBag()->toArray()]);
    }

    public function getDisabledDates()
    {
        $disabledDates = [];
        if ($this->property_id) {
            $bookings = Booking::where('property_id', $this->property_id)->get();
            $disabledDates = $bookings->flatMap(function ($booking) {
                $start = new \DateTime($booking->start_date);
                $end = new \DateTime($booking->end_date);
                $interval = new \DatePeriod($start, new \DateInterval('P1D'), $end);
                return array_map(fn($date) => $date->format('Y-m-d'), iterator_to_array($interval));
            })->unique()->values()->all();
        }
        return $disabledDates;
    }

    public function saveBooking()
    {
        Log::info('saveBooking called:', ['user_id' => auth()->id(), 'property_id' => $this->property_id, 'start_date' => $this->start_date, 'end_date' => $this->end_date, 'total_price' => $this->total_price]);
        $this->validate();

        $conflict = Booking::where('property_id', $this->property_id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                    ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                    ->orWhere(function ($query) {
                        $query->where('start_date', '<=', $this->start_date)
                            ->where('end_date', '>=', $this->end_date);
                    })
                    ->orWhere(function ($query) {
                        $query->where('start_date', '>=', $this->start_date)
                            ->where('start_date', '<', $this->end_date);
                    })
                    ->orWhere(function ($query) {
                        $query->where('end_date', '>', $this->start_date)
                            ->where('end_date', '<=', $this->end_date);
                    });
            })->where('id', '!=', null)->first(); 

        if ($conflict) {
            Log::warning('Conflit détecté : ', ['existing' => $conflict->toArray(), 'new' => [
                'property_id' => $this->property_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
            ]]);
            $this->message = 'Cette propriété est déjà réservée pour ces dates.';
            return;
        }

        $property = Property::find($this->property_id);
        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);
        $nights = max(1, $start->diff($end)->days);
        $total_price = $property->price_per_night * $nights;

        try {
            Booking::create([
                'user_id' => auth()->id() ?? 1,
                'property_id' => $this->property_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'total_price' => $total_price,
            ]);
            $this->message = 'Réservation réussie ! Bon séjour !';
            $this->loadBookings();
            session()->flash('success', $this->message);
        } catch (\Exception $e) {
            Log::error('Erreur dans saveBooking: ' . $e->getMessage() . ', Data: ' . json_encode([
                'user_id' => auth()->id() ?? 1,
                'property_id' => $this->property_id,
                'start_date' => $this->start_date,
                'end_date' => $this->end_date,
                'total_price' => $total_price,
            ]));
            $this->message = 'Erreur lors de la réservation : ' . $e->getMessage();
            session()->flash('error', $this->message);
        }

        $this->showModal = true;
        $this->emit('loadDisabledDates', $this->getDisabledDates());
    }

    public function render()
    {
        return view('livewire.booking-manager', [
            'properties' => Property::all(),
            'bookings' => $this->bookings,
        ]);
    }
}