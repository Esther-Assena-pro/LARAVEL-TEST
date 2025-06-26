<?php

namespace App\Http\Controllers;

use App\Models\Property;
use Illuminate\Http\Request;

class PropertyController extends Controller
{
    public function index()
    {
        $properties = Property::all();
        return view('properties.index', compact('properties'));
    }

    public function show($id)
    {
        $property = Property::findOrFail($id);
        return view('properties.show', compact('property'));
    }

    public function dashboard()
    {
        $bookings = auth()->check() ? auth()->user()->bookings()->with('property')->get() : collect();
        $properties = Property::all(); 
        return view('dashboard', compact('bookings', 'properties'));
    }
}