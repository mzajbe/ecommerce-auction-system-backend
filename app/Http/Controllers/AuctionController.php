<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Auction::all();
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validatedData = $request->validate([
                'car_name' => 'required|string|max:255',
                'model' => 'required|string|max:255',
                'description' => 'nullable|string',
                'image_url' => 'required|string',
                'passenger_capacity' => 'required|integer',
                'body_style' => 'required|string|max:255',
                'cylinders' => 'required|integer',
                'color' => 'required|string|max:255',
                'engine_type' => 'required|string|max:255',
                'transmission' => 'required|string|max:255',
                'vehicle_type' => 'required|string|max:255',
                'fuel' => 'required|string|max:255',
                'damage_description' => 'nullable|string',
                'starting_price' => 'required|numeric',
            ]);

            $auction = Auction::create($validatedData);
            return response()->json($auction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auction = Auction::findOrFail($id);
        return response()->json($auction);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $auction = Auction::findOrFail($id);

        $validatedData = $request->validate([
            'car_name' => 'sometimes|required|string|max:255',
            'model' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'image_url' => 'sometimes|required|string',
            'passenger_capacity' => 'sometimes|required|integer',
            'body_style' => 'sometimes|required|string|max:255',
            'cylinders' => 'sometimes|required|integer',
            'color' => 'sometimes|required|string|max:255',
            'engine_type' => 'sometimes|required|string|max:255',
            'transmission' => 'sometimes|required|string|max:255',
            'vehicle_type' => 'sometimes|required|string|max:255',
            'fuel' => 'sometimes|required|string|max:255',
            'damage_description' => 'nullable|string',
            'starting_price' => 'sometimes|required|numeric',
        ]);

        $auction->update($validatedData);
        return response()->json($auction);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $auction = Auction::findOrFail($id);
        $auction->delete();

        return response()->json(['message' => 'Auction deleted successfully.']);
    }
}
