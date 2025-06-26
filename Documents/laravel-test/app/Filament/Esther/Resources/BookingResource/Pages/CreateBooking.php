<?php

namespace App\Filament\Esther\Resources\BookingResource\Pages;

use App\Filament\Esther\Resources\BookingResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Filament\Forms\Get;

class CreateBooking extends CreateRecord
{
    protected static string $resource = BookingResource::class;

    protected function mutateFormDataBeforeCreate(array $data): array
    {
        
        $property = \App\Models\Property::find($data['property_id']);
        if (!$property) {
            throw new \Exception('Propriété non trouvée.');
        }
        $start = new \DateTime($data['start_date']);
        $end = new \DateTime($data['end_date']);
        $nights = max(1, $start->diff($end)->days);
        $data['total_price'] = $property->price_per_night * $nights;

        return $data;
    }

    protected function beforeCreate(): void
    {
        $data = $this->form->getState();
        if ($data['conflict_error']) {
            $this->halt();
        }
    }

    protected function handleRecordCreation(array $data): Model
    {
// Vérifier les conflits
        $conflict = \App\Models\Booking::where('property_id', $data['property_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_date', '<', $data['start_date'])
                            ->where('end_date', '>', $data['end_date']);
                    });
            })->first();
 
        if ($conflict) {
            \Log::warning('Conflit détecté : ', ['existing' => $conflict->toArray(), 'new' => $data]);
            throw new \Exception('Cette propriété est déjà réservée pour ces dates.');
        }

// Créer la réservation
        return parent::handleRecordCreation($data);  
    }
}