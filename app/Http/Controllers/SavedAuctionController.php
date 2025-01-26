<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\SavedAuction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SavedAuctionController extends Controller
{
   /**
     * Save an auction for later with explicit user_id and auction_id.
     */
    public function save(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'auction_id' => 'required|exists:auctions,id',
            ]);

            // Check if the auction is already saved
            $existing = SavedAuction::where('user_id', $validated['user_id'])
                ->where('auction_id', $validated['auction_id'])
                ->first();

            if ($existing) {
                return response()->json(['message' => 'Auction already saved.'], 400);
            }

            // Save the auction
            $savedAuction = SavedAuction::create([
                'user_id' => $validated['user_id'],
                'auction_id' => $validated['auction_id'],
            ]);

            return response()->json($savedAuction, 201);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error saving auction: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while saving the auction.'], 500);
        }
    }

    /**
     * Get all saved auctions for a specific user.
     */
    public function index(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            // Fetch all saved auctions for the user
            $savedAuctions = SavedAuction::with('auction')
                ->where('user_id', $validated['user_id'])
                ->get();

            return response()->json($savedAuctions);
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error fetching saved auctions: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while fetching saved auctions.'], 500);
        }
    }

    /**
     * Remove a saved auction with explicit user_id and auction_id.
     */
    public function remove(Request $request)
    {
        try {
            // Validate the input
            $validated = $request->validate([
                'user_id' => 'required|exists:users,id',
                'auction_id' => 'required|exists:auctions,id',
            ]);

            // Delete the saved auction
            $deleted = SavedAuction::where('user_id', $validated['user_id'])
                ->where('auction_id', $validated['auction_id'])
                ->delete();

            if ($deleted) {
                return response()->json(['message' => 'Auction removed from saved list.']);
            } else {
                return response()->json(['message' => 'Auction not found in saved list.'], 404);
            }
        } catch (\Exception $e) {
            // Log the error for debugging
            Log::error('Error removing saved auction: ' . $e->getMessage());
            return response()->json(['error' => 'An error occurred while removing the auction.'], 500);
        }
    }
}
