<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Address;
use App\Http\Requests\StoreAddressRequest;

class AddressControllers extends Controller
{
    public function store(Request $request){

        $request->validate([
            'country' => 'required',
            'state' => 'required',
            'city' => 'required',
            'area' => 'required',
            'pin' => 'required',
            'house_no' => 'required',
        ]);
        $address = Address::create([
            'country' => $request->country,
            'state' => $request->state,
            'city' => $request->city,
            'area' => $request->area,
            'pin' => $request->pin,
            'house_no' => $request->house_no,
        ]);

        return response()->json([
            'address' => $address
        ]);
    }
}
