<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Role;
use App\Http\Requests\StoreProductRequest;
use App\Http\Requests\UpdateProductRequest;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Models\Room;
use App\Models\Quotation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Instantiate a new ProductController instance.
     */


    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        return view('products.index', [
            'products' => Product::orderby('code')->latest()->paginate(25)
        ]);
    }

    public function filterByRoom(Room $room)
    {
        // Retrieve all products that belong to this category

        $products = Product::whereHas('rooms', function ($query) use ($room) {
            $query->where('room_id', $room->id);
        })
        ->orderby('code')
        ->get();

        $quotation = Quotation::where('user_id', Auth::id())
            ->where('status', 'pending')->first();


        // Return the view with the products filtered by category
        return view('products.shop', compact('products', 'room', 'quotation'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {

        $rooms = Room::all(); // Fetch all rooms for the Select2 dropdown
        $roles = Role::all(); // Fetch all roles for the price input
        return view('products.create', compact('rooms', 'roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreProductRequest $request): RedirectResponse

    {
        $request->validate([
            'name' => 'required|string|max:255',
            'code' => 'required|string|max:10',
            'description' => 'required|string',
            'image' => 'nullable|image|max:2048',
            'sku' => 'nullable|string|max:50',
            'rooms' => 'required|array',
            'roles' => 'required|array',
        ]);

        // Handle image upload
        $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : null;
        /*if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('images', 'public');

        }*/
        $product = Product::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'image' => $imagePath,
            'sku' => $request->sku,
        ]);

        $product->rooms()->sync($request->rooms);
        $rolesWithPrices = [];
        foreach ($request->roles as $key => $roleId) {
            $rolesWithPrices[$roleId] = ['price' => $request->prices[$key]];
        }

        // Sync roles with prices
        $product->roles()->sync($rolesWithPrices);
        return redirect()->route('products.index')->with('success', 'Product created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product, Room $room): View
    {
        return view('products.show', [
            'product' => $product,
            'room' => $room
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product): View
    {
        $rooms = Room::all(); // Fetch all rooms for the Select2 dropdown
        $roles = Role::all(); // Fetch all roles for the price input

        return view('products.edit', [
            'product' => $product,
            'rooms' => $rooms,
            'roles' => $roles
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(UpdateProductRequest $request, Product $product): RedirectResponse
{
    // Validate the request data
    $request->validate([
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:10',
        'description' => 'required|string',
        'image' => 'nullable|image|max:2048',
        'sku' => 'nullable|string|max:50',
        'rooms' => 'required|array',
        'roles' => 'required|array',
    ]);

    // Handle image upload
    $imagePath = $request->file('image') ? $request->file('image')->store('products', 'public') : $product->image;

    // Update the product
    $product->update([
        'name' => $request->name,
        'code' => $request->code,
        'description' => $request->description,
        'image' => $imagePath,
        'sku' => $request->sku,
    ]);

    // Sync rooms
    $product->rooms()->sync($request->rooms);

    // Prepare roles with prices
    $rolesWithPrices = [];
foreach ($request->roles as $roleId => $price) {
    $rolesWithPrices[$roleId] = ['price' => $price ?? 0];
}
// Sync roles with prices
$product->roles()->sync($rolesWithPrices);

       return redirect()->route('products.index')->with('success', 'Prodotto aggiornato con successo.');
}

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        return redirect()->route('products.index')
            ->withSuccess('Prodotto cancellato con successo.');
    }

    public function searchByRoom(Request $request)
    {
        $roomId = $request->input('room_id');
       // $products = Product::where('room_id', $roomId)->get();
        $products = Room::find($roomId)->products;

        return response()->json($products);
    }
}
