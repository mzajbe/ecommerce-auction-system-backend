<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Bid;

class BidController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'auction_id' => 'required|exists:auctions,id',
            'bid_amount' => 'required|numeric|min:0',
        ]);

        $bid = Bid::create([
            'user_id' => auth()->id(),
            'auction_id' => $request->auction_id,
            'bid_amount' => $request->bid_amount,
        ]);

        return response()->json($bid, 201);
    }

    // public function index($auctionId)
    // {
    //     $bids = Bid::where('auction_id', $auctionId)->orderBy('bid_amount', 'desc')->get();
    //     return response()->json($bids);
    // }

    public function index($auctionId)
    {
        $bids = Bid::where('auction_id', $auctionId)
            ->with('user') // Include the user data
            ->orderBy('bid_amount', 'desc')
            ->get();

        return response()->json($bids);
    }
}
