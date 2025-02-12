<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\ItemVarient;
use Illuminate\Http\Request;

class ItemVarientController extends Controller
{
    public function index()
    {
        $itemvarient = ItemVarient::all();
        return response()->json([
            'item_varien' => $itemvarient
        ]);
    }
    public function store(Request $request)
    { 
        $request->validate([
            'color' => 'required',
            'size' => 'required',
            'fabric' => 'required',
            'price' => 'required',
        ]);
     
        $itemvarient = ItemVarient::create([
            'color' => $request->color,
            'size' => $request->size,
            'fabric' => $request->fabric,
            'price' => $request->price,
        ]);
        return response()->json([
            'item_varien' => $itemvarient
        ]);
    }
}
