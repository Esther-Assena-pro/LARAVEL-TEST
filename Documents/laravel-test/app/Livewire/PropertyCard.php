<?php

namespace App\Livewire;

use Livewire\Component;

class PropertyCard extends Component
{
    public $property;

    public function mount($property)
    {
        $this->property = $property;
    }

    public function openBooking()
    {
        $this->emitTo('booking-manager', 'openModal', $this->property->id);
    }

    public function render()
    {
        return view('livewire.property-card');
    }
}