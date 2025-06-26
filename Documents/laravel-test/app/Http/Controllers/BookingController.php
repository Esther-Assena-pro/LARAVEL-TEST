<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function store(Request $request)
    {
        if (!Auth::check()) {
            Log::warning('Tentative de réservation sans authentification. Session ID: ' . session()->getId());
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être connecté pour réserver.',
                'debug' => ['auth' => false, 'session_id' => session()->getId()]
            ], 401);
        }

        $userId = Auth::id();
        if (!$userId) {
            Log::error('Utilisateur authentifié mais user_id est null. Session ID: ' . session()->getId() . ', User: ' . json_encode(Auth::user()));
            return response()->json([
                'success' => false,
                'message' => 'Erreur d\'authentification interne.',
                'debug' => ['auth' => true, 'user_id' => $userId, 'user' => Auth::user()]
            ], 500);
        }

        try {
            $request->validate([
                'property_id' => 'required|exists:properties,id',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);

            $property = Property::findOrFail($request->property_id);
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $nights = max(1, $startDate->diff($endDate)->days);
            $totalPrice = $nights * $property->price_per_night;

            Log::info('Création de réservation : user_id=' . $userId . ', property_id=' . $property->id . ', total_price=' . $totalPrice);

            $booking = Booking::create([
                'user_id' => $userId,
                'property_id' => $property->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Réservation réussie, bon séjour !',
                'redirect' => route('dashboard'),
                'total_price' => $totalPrice
            ]);
        } catch (\Exception $e) {
            Log::error('Erreur dans store: ' . $e->getMessage() . ', User: ' . json_encode(Auth::user()) . ', Request: ' . json_encode($request->all()));
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la réservation : ' . $e->getMessage(),
                'debug' => ['user' => Auth::user(), 'request' => $request->all()],
                'total_price' => $totalPrice ?? 0
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            $booking->delete();

            return redirect()->route('dashboard')->with('success', 'Réservation supprimée avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur dans destroy: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        try {
            $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();
            return view('booking.edit', compact('booking'));
        } catch (\Exception $e) {
            return redirect()->route('dashboard')->with('error', 'Réservation non trouvée ou inaccessible.');
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $booking = Booking::where('id', $id)->where('user_id', Auth::id())->firstOrFail();

            $request->validate([
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after:start_date',
            ]);

            $property = $booking->property;
            $startDate = new \DateTime($request->start_date);
            $endDate = new \DateTime($request->end_date);
            $nights = max(1, $startDate->diff($endDate)->days);
            $totalPrice = $nights * $property->price_per_night;

            $booking->update([
                'start_date' => $startDate,
                'end_date' => $endDate,
                'total_price' => $totalPrice,
            ]);

            return redirect()->route('dashboard')->with('success', 'Réservation mise à jour avec succès !');
        } catch (\Exception $e) {
            Log::error('Erreur dans update: ' . $e->getMessage());
            return redirect()->route('dashboard')->with('error', 'Erreur lors de la mise à jour : ' . $e->getMessage());
        }
    }
}