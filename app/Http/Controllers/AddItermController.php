<?php

namespace App\Http\Controllers;

use App\Http\Resources\CardResource;
use App\Models\Card;
use App\Models\Item;
use App\Models\ItemVarient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddItermController extends Controller
{
    public function index()
    {
        $card = Card::where('user_id', Auth::id())->with('item')->get();
        return CardResource::collection($card);
    }
    // Add to Cart
    public function store(Request $request)
    { 
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
           // 'quantity' => 'required'
        ]); 
        $item = Item::find($request->item_id);
        if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
        }
        $cards = Card::where('user_id', Auth::id())->with('item')->get();
        $card = Card::create([
            'user_id' => Auth::id(),
            'item_id' => $request->item_id,
            'quantity' => $request->quantity,
        ]);
        // $itemVarient = ItemVarient::find($request->price);
        $totalPrice = $item->price * $card->quantity;
        return response()->json([
            'data' => new CardResource($card),
            'total_price' => $totalPrice
        ]);
    
}
    // Remove from Cart
    public function destroy($id)
    {
        $card = Card::where('user_id', Auth::id())->where('id', $id)->firstOrFail();
        if (!$card) return response()->json(['message' => 'Item not found'], 404);
        $card->delete();
        return response()->json(['message' => 'Item removed from cart']);
    }
}
