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
    public $isFormValid = false;
    public $bookings;
    public $editingId = null;

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
        $this->reset(['start_date', 'end_date', 'total_price', 'isFormValid', 'editingId']);
        $this->loadBookings();
    }

    public function loadBookings()
    {
        $this->bookings = Booking::where('user_id', auth()->id() ?? 1)->get();
    }

    public function editBooking($id)
    {
        $booking = Booking::where('id', $id)->where('user_id', auth()->id() ?? 1)->first();
        if ($booking) {
            $this->editingId = $booking->id;
            $this->property_id = $booking->property_id;
            $this->start_date = $booking->start_date;
            $this->end_date = $booking->end_date;
            $this->total_price = $booking->total_price;
            $this->calculatePrice();
            $this->checkFormValidity();
        } else {
            $this->message = 'Réservation non trouvée ou inaccessible.';
            session()->flash('error', $this->message);
        }
    }

    public function cancelEdit()
    {
        $this->reset(['editingId', 'property_id', 'start_date', 'end_date', 'total_price', 'message']);
        $this->calculatePrice();
        $this->checkFormValidity();
    }

    public function updated($property)
    {
        if (in_array($property, ['property_id', 'start_date', 'end_date'])) {
            $this->calculatePrice();
            $this->validateOnly($property);
            $this->checkFormValidity();
        }
    }

    public function calculatePrice()
    {
        $this->total_price = 0;
        $this->message = '';
        if ($this->property_id && $this->start_date && $this->end_date) {
            $property = Property::find($this->property_id);
            if ($property && $property->price_per_night !== null) {
                $start = new \DateTime($this->start_date);
                $end = new \DateTime($this->end_date);
                $nights = max(1, $start->diff($end)->days);
                if ($nights > 14) {
                    $this->addError('end_date', 'Durée maximale de 14 nuits.');
                    return;
                }
                if ($end <= $start) {
                    $this->addError('end_date', 'Date de fin doit être après la date de début.');
                    return;
                }
                $this->total_price = $property->price_per_night * $nights;
            } else {
                $this->addError('property_id', 'Propriété invalide ou prix non défini.');
            }
        }
    }

    public function checkFormValidity()
    {
        $this->isFormValid = !empty($this->property_id) && !empty($this->start_date) && !empty($this->end_date) && $this->getErrorBag()->isEmpty();
    }

    public function getDisabledDatesProperty()
    {
        $disabledDates = [];
        if ($this->property_id) {
            $bookings = Booking::where('property_id', $this->property_id)
                ->where('id', '!=', $this->editingId)
                ->get();
            $disabledDates = $bookings->flatMap(function ($booking) {
                $start = new \DateTime($booking->start_date);
                $end = new \DateTime($booking->end_date);
                $interval = new \DatePeriod($start, new \DateInterval('P1D'), $end);
                return array_map(fn($date) => $date->format('Y-m-d'), iterator_to_array($interval));
            })->unique()->values()->all();
        }
        return $disabledDates;
    }

    public function updateStartDate($value)
    {
        $this->start_date = $value['value'];
        $this->validateOnly('start_date');
        $this->calculatePrice();
        $this->checkFormValidity();
    }

    public function updateEndDate($value)
    {
        $this->end_date = $value['value'];
        $this->validateOnly('end_date');
        $this->calculatePrice();
        $this->checkFormValidity();
    }

    public function saveBooking()
    {
        Log::info('saveBooking called:', ['user_id' => auth()->id(), 'property_id' => $this->property_id, 'start_date' => $this->start_date, 'end_date' => $this->end_date, 'editingId' => $this->editingId]);
        $this->validate();

        
        $conflict = Booking::where('property_id', $this->property_id)
            ->where(function ($query) {
                $query->whereBetween('start_date', [$this->start_date, $this->end_date])
                      ->orWhereBetween('end_date', [$this->start_date, $this->end_date])
                      ->orWhere(function ($query) {
                          $query->where('start_date', '<=', $this->start_date)
                                ->where('end_date', '>=', $this->end_date);
                      });
            })
            ->where('id', '!=', $this->editingId)
            ->where('user_id', auth()->id() ?? 1)
            ->first();

        if ($conflict) {
            $this->message = 'Conflit : cette propriété est déjà réservée pour ces dates.';
            session()->flash('error', $this->message);
            return;
        }

        $property = Property::find($this->property_id);
        if (!$property) {
            $this->message = 'Propriété introuvable.';
            session()->flash('error', $this->message);
            return;
        }

        $start = new \DateTime($this->start_date);
        $end = new \DateTime($this->end_date);
        $nights = max(1, $start->diff($end)->days);
        $total_price = $property->price_per_night * $nights;

        try {
            if ($this->editingId) {
                $booking = Booking::find($this->editingId);
                if ($booking && $booking->user_id === (auth()->id() ?? 1)) {
                    $booking->update([
                        'property_id' => $this->property_id,
                        'start_date' => $this->start_date,
                        'end_date' => $this->end_date,
                        'total_price' => $total_price,
                    ]);
                    $this->message = 'Réservation mise à jour avec succès !';
                } else {
                    throw new \Exception('Réservation non trouvée ou inaccessible.');
                }
            } else {
                Booking::create([
                    'user_id' => auth()->id() ?? 1,
                    'property_id' => $this->property_id,
                    'start_date' => $this->start_date,
                    'end_date' => $this->end_date,
                    'total_price' => $total_price,
                ]);
                $this->message = 'Réservation réussie ! Bon séjour !';
            }
            $this->loadBookings();
            session()->flash('success', $this->message);
            $this->reset(['start_date', 'end_date', 'total_price', 'message']);
        } catch (\Exception $e) {
            Log::error('Erreur dans saveBooking: ' . $e->getMessage());
            $this->message = 'Erreur lors de la réservation : ' . $e->getMessage();
            session()->flash('error', $this->message);
        }
    }

    public function deleteBooking($id)
    {
        try {
            $booking = Booking::where('id', $id)->where('user_id', auth()->id() ?? 1)->firstOrFail();
            $booking->delete();
            $this->loadBookings();
            $this->message = 'Réservation supprimée avec succès !';
            session()->flash('success', $this->message);
        } catch (\Exception $e) {
            Log::error('Erreur dans deleteBooking: ' . $e->getMessage());
            $this->message = 'Erreur lors de la suppression : ' . $e->getMessage();
            session()->flash('error', $this->message);
        }
    }

    public function render()
    {
        return view('livewire.booking-manager', [
            'properties' => Property::all(),
            'bookings' => $this->bookings,
            'disabledDates' => $this->getDisabledDatesProperty(),
        ]);
    }
}