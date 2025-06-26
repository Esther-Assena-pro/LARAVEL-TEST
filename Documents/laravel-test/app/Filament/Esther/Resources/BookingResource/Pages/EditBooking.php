<?php

namespace App\Filament\Esther\Resources\BookingResource\Pages;

use App\Filament\Esther\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;

class EditBooking extends EditRecord
{
    protected static string $resource = BookingResource::class;

    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Calculer total_price
        $property = \App\Models\Property::find($data['property_id']);
        if (!$property) {
            throw new \Exception('Propriété non trouvée.');
        }
        $start = new \DateTime($data['start_date']);
        $end = new \DateTime($data['end_date']);
        $nights = max(1, $start->diff($end)->days);
        $totalPrice = $property->price_per_night * $nights;

        // Préparer les données
        $recordData = array_merge($data, ['total_price' => $totalPrice]);

        // Vérifier les conflits, exclure la réservation actuelle
        $conflict = \App\Models\Booking::where('property_id', $data['property_id'])
            ->where(function ($query) use ($data) {
                $query->whereBetween('start_date', [$data['start_date'], $data['end_date']])
                    ->orWhereBetween('end_date', [$data['start_date'], $data['end_date']])
                    ->orWhere(function ($query) use ($data) {
                        $query->where('start_date', '<=', $data['start_date'])
                            ->where('end_date', '>=', $data['end_date']);
                    });
            })->where('id', '!=', $record->id)->first();

        if ($conflict) {
            \Log::warning('Conflit détecté : ', ['existing' => $conflict->toArray(), 'new' => $data]);
            throw new \Exception('Cette propriété est déjà réservée pour ces dates.');
        }

        // Mettre à jour la réservation
        $record->update($recordData);

        return $record;
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}