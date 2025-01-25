<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;
use App\Models\Auction;
use App\Models\Bid;
use Illuminate\Support\Facades\Auth;

class CartController extends Controller
{
   /**
     * Add the winning auction to the user's cart.
     */
    public function addWinningAuctionToCart($auctionId)
    {
        // Check if the auction has ended
        $auction = Auction::findOrFail($auctionId);

        if (now()->lt($auction->end_time)) {
            return response()->json(['message' => 'Auction has not ended yet'], 400);
        }

        // Find the highest bid for the auction
        $winningBid = Bid::where('auction_id', $auctionId)
            ->orderBy('bid_amount', 'desc')
            ->first();

        if (!$winningBid) {
            return response()->json(['message' => 'No bids found for this auction'], 404);
        }

        // Add the auction to the winner's cart
        $cart = Cart::create([
            'user_id' => $winningBid->user_id,
            'auction_id' => $auctionId,
            'price' => $winningBid->bid_amount,
        ]);

        return response()->json(['message' => 'Winning auction added to cart', 'cart' => $cart], 200);
    }

    /**
     * View the user's cart.
     */
    public function viewCart()
    {
        $userId = Auth::id();
        $cartItems = Cart::with('auction')
            ->where('user_id', $userId)
            ->get();

        return response()->json($cartItems);
    }
}
