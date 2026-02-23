<?php

namespace App\Http\Controllers;

use App\Models\Water;
use Illuminate\Http\Request;

class WaterController extends Controller
{
    /**
     * SHOW: Retrieves how many glasses of water the user drank today.
     */
    public function show(Request $request)
    {
        // 1. Search for today's water record for this user
        $water = Water::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->first();

        // 2. If it doesn't exist yet, return 0 glasses. Otherwise, return the record.
        return response()->json($water ?: ['glasses' => 0]);
    }

    /**
     * STORE: Updates or creates today's water intake.
     */
    public function store(Request $request)
    {
        // 1. Ensure the glasses count is a valid number
        $request->validate(['glasses' => 'required|integer|min:0']);

        // 2. updateOrCreate: This is a smart Laravel function. 
        // It looks for a record matching the userId and date.
        // If it finds one, it UPDATES it. If not, it CREATES a new one.
        $water = Water::updateOrCreate(
            ['user_id' => $request->user()->id, 'date' => date('Y-m-d')],
            ['glasses' => $request->glasses]
        );

        return response()->json(['message' => 'Water updated', 'glasses' => $water->glasses]);
    }
}