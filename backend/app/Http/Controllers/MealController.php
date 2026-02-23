<?php

namespace App\Http\Controllers;

use App\Models\Meal;
use Illuminate\Http\Request;

class MealController extends Controller
{
    /**
     * GET INDEX: Returns all meals logged by the user for the current date.
     */
    public function index(Request $request)
    {
        // 1. Find all meals belonging to the logged-in user for today's date
        $meals = Meal::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->orderBy('id', 'desc')
            ->get();

        // 2. Return the list of meals as a JSON list (array)
        return response()->json($meals);
    }

    /**
     * STORE: Saves a new meal to the user's log.
     */
    public function store(Request $request)
    {
        // 1. Validate the data coming from the frontend (e.g. name of food, calories)
        $validated = $request->validate([
            'name' => 'required|string',
            'cal' => 'required|numeric', // Supports decimals now
            'pro' => 'required|numeric', // Supports decimals now
            'type' => 'required|string', // Breakfast, Lunch, etc.
            'time' => 'required|string',
            'score' => 'nullable|integer',
        ]);

        // 2. Create a new record in the 'meals' SQLite table
        $meal = Meal::create([
            'user_id' => $request->user()->id, // Link this meal to the logged-in user
            'name' => $validated['name'],
            'cal' => $validated['cal'],
            'pro' => $validated['pro'],
            'type' => $validated['type'],
            'time' => $validated['time'],
            'date' => date('Y-m-d'), // Save as today's date automatically
            'score' => $validated['score'] ?? 75,
        ]);

        // 3. Return the newly created meal so the frontend can display it instantly
        return response()->json($meal, 201);
    }

    /**
     * DESTROY: Deletes a specific meal by its ID.
     */
    public function destroy(Request $request, $id)
    {
        // 1. Find the meal but ONLY if it belongs to the current user (security)
        $meal = Meal::where('user_id', $request->user()->id)->findOrFail($id);

        // 2. Erase it from existence
        $meal->delete();

        return response()->json(['message' => 'Meal deleted']);
    }

    /**
     * CLEAR: Erases all of today's meals for the user.
     */
    public function clear(Request $request)
    {
        // 1. Delete all of today's records for this specific user
        Meal::where('user_id', $request->user()->id)
            ->where('date', date('Y-m-d'))
            ->delete();

        return response()->json(['message' => 'All today\'s meals cleared']);
    }
}