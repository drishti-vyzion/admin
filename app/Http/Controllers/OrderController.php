<?php

namespace App\Http\Controllers;

use App\Mail\CardOrderMail;
use App\Mail\OrderMail;
use App\Models\Card;
use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    public function buyNow(Request $request)
    {
        $request->validate(['item_id' => 'required|exists:items,id']);
        $item = Item::find($request->item_id);
        $quantity = $request->quantity ?? 1;
        $totalPrice = $item->price * $quantity;
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);
        OrderItem::create([
            'order_id' => $order->id,
            'item_id' => $item->id,
            'quantity' => $quantity,
            'price' => $item->price * $quantity,
        ]);
        $user = Auth::user();
        Mail::to($user->email)->send(new OrderMail($user, $item, $order));
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
            'items' => $item,
        ]);
    }

    public function checkout(Request $request)
    {
        $cards = Card::where('user_id', Auth::id())->with('item')->get();
        if ($cards->isEmpty()) {
            return response()->json(['message' => 'Your cart is empty.'], 400);
        }
        $totalPrice = $cards->sum(fn($card) => $card->price * $card->quantity);
        $order = Order::create([
            'user_id' => Auth::id(),
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);
        foreach ($cards as $card) {
            OrderItem::create([
                'order_id' => $order->id,
                'item_id' => $card->item->id,  // Assuming `item` relationship exists
                'quantity' => $card->quantity,
                'price' => $totalPrice
            ]);
        }
        $user = Auth::user();
        Mail::to($user->email)->send(new CardOrderMail($user, $order));
        return response()->json([
            'message' => 'Order created successfully',
            'order' => $order,
            'items' => $cards->map(fn($card) => $card),
        ]);
    }

    public function store(Request $request)
    {
        $request->validate(['user_id' => 'required|exists:users,id']);
        $cards = Card::all();
        $order = Order::find($request->id);
        // dd($order);
        // foreach ($cards as $card) {
        //     OrderItem::create([
        //         'order_id' => $order->id,
        //         'item_id' => $card->item->id,
        //         'quantity' => $card->quantity,
        //         'price' => $card->item->price,
        //     ]);
        // }
        $final_price = $cards->sum(fn($order) => $order->total_price);
        return response()->json([
            $cards,
            $order,
            $final_price
        ]);
    }
}
