<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Auction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AuctionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $auctions = Auction::with('company')->get(); // Include company details for all auctions
        return response()->json($auctions);
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
                'start_time' => 'required|date_format:Y-m-d H:i:s',
                'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
                // 'is_live' => 'sometimes|boolean',
                'company_id' => 'required|exists:companies,id', // Validate company_id
            ]);

            // Automatically set is_live to false by default
            $validatedData['is_live'] = false;

            $auction = Auction::create($validatedData);
            // Include company details in the response
            $auction = Auction::with('company')->find($auction->id);
            return response()->json($auction, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function updateAuctionStatus()
{
    $now = Carbon::now();
    $auctions = Auction::all();

    foreach ($auctions as $auction) {
        $auction->is_live = $now->between($auction->start_time, $auction->end_time);
        $auction->save();
    }

    return response()->json(['message' => 'Auction statuses updated successfully.']);
}


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $auction = Auction::with('company')->findOrFail($id); // Include company details
        return response()->json($auction);
    }

    /**
 * Get all auctions for a specific company.
 */
public function getAuctionsByCompany($companyId)
{
    $auctions = Auction::with('company')
        ->where('company_id', $companyId)
        ->get();

    return response()->json($auctions);
}

/**
 * Get live auctions for a specific company.
 */
public function getLiveAuctionsByCompany($companyId)
{
    $now = Carbon::now();
    
    $liveAuctions = Auction::with('company')
        ->where('company_id', $companyId)
        ->where('start_time', '<=', $now)
        ->where('end_time', '>=', $now)
        ->get();

    return response()->json($liveAuctions);
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
            'start_time' => 'sometimes|required|date_format:Y-m-d H:i:s',
            'end_time' => 'sometimes|required|date_format:Y-m-d H:i:s|after:start_time',
            // 'validatedData' => 'sometimes|boolean',
            'company_id' => 'sometimes|required|exists:companies,id', // Validate company_id
        ]);
        $auction->update($validatedData);
        return response()->json($auction);
    }

    /**
     * Search for auctions based on query parameters.
     */

    public function search(Request $request)
    {
        $query = Auction::query();

        if ($request->has('car_name')) {
            $query->where('car_name', 'like', '%' . $request->input('car_name') . '%');
        }

        if ($request->has('model')) {
            $query->where('model', $request->input('model'));
        }

        if ($request->has('id')) {
            $query->where('id', $request->input('id'));
        }

        // Add more filters as needed...

        $results = $query->get();
        return response()->json($results);
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
