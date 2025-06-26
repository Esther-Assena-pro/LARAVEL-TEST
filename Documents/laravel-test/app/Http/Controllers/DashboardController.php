<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        return view('dashboard', [
            'bookings' => $request->user()->bookings()->with(['property' => function ($query) {
                $query->select('id', 'name');
            }])->latest()->take(10)->get(),
            'property_id' => $request->query('property_id'),
        ]);
    }
}
