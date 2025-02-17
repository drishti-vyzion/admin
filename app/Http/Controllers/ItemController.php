<?php

namespace App\Http\Controllers;

use App\Http\Resources\CreateResource;
use App\Http\Resources\{
    ItemResource,
    ItemListResource
};
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

use function Pest\Laravel\json;

class ItemController extends Controller
{

    public function index()
    {
        if (auth::user()->role === 'retailer') {
            $item = Item::where('created_by', Auth::id())->get();
        } else {
            $item = item::all();
        }
        return ItemListResource::collection($item);
    }
    public function show($id)
    {
        // $item = Item::where('id', $id)->first();
        $item = Item::where('id', $id)->where('created_by', Auth::id())->first();
        if (!$item) {
            return response()->json(['message' => 'Item not found or deleted'], 404);
        }
        return new ItemListResource($item);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'description' => 'required',
            'category_id' => 'required',
            'item_varient_id' => 'required',
            'image' => 'required|image',
            'price' => 'required|numeric',
        ]);
// if  (Auth::user()->role == 'retailer'){
  
// }
$imagePath = $request->file('image')->store('items', 'public');
// if ($request->created_by == 2){
        $item = Item::create([
            'created_by' =>Auth::id(),
            'name' => $request->name,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'image' => $imagePath,
            'item_varient_id' => $request->item_varient_id,
            'price' => $request->price,
        ]);
    // }
        return new ItemListResource($item);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'sometimes|string',
            'category_id' => 'sometimes|integer',
            'image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $item = Item::findOrFail($id);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($item->image);
            $item->image = $request->file('image')->store('items', 'public');
        }

        $item->update($request->except('image'));
        return new ItemListResource($item);
    }

    public function destroy(Item $item)
    {
        if ((int) $item->created_by !== Auth::id()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }
        if ($item->image && Storage::disk('public')->exists($item->image)) {
            Storage::disk('public')->delete($item->image);
        }
        $item->delete();
        return response()->json(['message' => 'Item deleted successfully']);
    }

    public function getByCategory($categoryId)
    {
        return response()->json(Item::where('category_id', $categoryId)->get(), 200);
    }
}
