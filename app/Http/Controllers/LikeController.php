<?php

namespace App\Http\Controllers;

use App\Http\Resources\LikeResource;
use App\Models\Item;
use App\Models\like;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LikeController extends Controller
{
    public function index()
    {
        $like = like::where('user_id', Auth::id())->get();
        return LikeResource::collection($like);
    }
    public function show($id)
    {     
        $like = Like::findOrFail($id);
        return new LikeResource($like);
    }
    public function store(Request $request)
    {  
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'user_id' => 'required|exists:users,id',
        ]);
      
         $item = Item::find($request->item_id);
         if (!$item) {
            return response()->json(['error' => 'Item not found'], 404);
         }
        $like = like::create([
             'item_id' => $request->item_id,
             'user_id' => Auth::id(),
        ]);
    
        return new LikeResource($like);
    } 
    public function destroy($id){
      
        $like = Like::findOrFail($id);  
        if ($like->user_id !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        $like->delete();  
        return response()->json(['message' => 'Like deleted successfully']);
            }
    }
    

