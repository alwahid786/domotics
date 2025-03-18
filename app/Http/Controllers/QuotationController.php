<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductRoom;
use App\Mail\SendQuotation;
use App\Models\Quotation;
use App\Models\Room;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    public function addToQuotation(Request $request, Product $product, $productroom)
    {
        // Get the current user
        $user = Auth::user();

        // Check if the user already has an active quotation
        $quotation = Quotation::firstOrCreate(
            ['user_id' => $user->id, 'status' => 'pending'],
            ['status' => 'pending']
        );

        //$productroom = ProductRoom::find($productroom);

        $productprice= $product->priceByRole($user);

        if ($quotation->products()
            ->wherePivot('product_id', $product->id)
            ->wherePivot('product_room_id', $productroom)
            ->exists()
        ) {
            // If the product is already in the quotation for the selected room, update the quantity
            $quotation->products()->updateExistingPivot($product->id, [
                'quantity' => $request->input('quantity', 1),
                'price' => $productprice,
                'product_room_id' => $productroom,
            ]);
        } else {
            // Otherwise, attach the product with the selected room and other details
            $quotation->products()->attach($product->id, [
                'quantity' => $request->input('quantity', 1),
                'price' => $productprice,
                'product_room_id' => $productroom,
            ]);
        }

        return redirect()->back()->with('success', 'Product added to quotation.');
    }

    public function viewPendingQuotation()
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve the user's active (pending) quotation
        $quotation = Quotation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with('products.rooms') // Eager load products and rooms
            ->first();

        // Pass the quotation to the view
        return view('quotations.view', compact('quotation'));
    }

    public function viewGroupedQuotation()
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve the user's active quotation with products and their associated rooms
        $quotation = Quotation::where('user_id', $user->id)
            ->where('status', 'pending')
            ->with(['products'])
            ->first();

        // Group the products by room ID (product_room_id)
        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');

        return view('quotations.current', compact('quotation', 'productsGroupedByRoom'));
    }

    public function view($quotation)
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve the user's active quotation with products and their associated rooms
        $quotation = Quotation::where('id', $quotation)
            ///->where('status', 'pending')
            ->with(['products'])
            ->first();

        // Group the products by room ID (product_room_id)
        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');

        return view('quotations.view', compact('quotation', 'productsGroupedByRoom'));
    }

    public function confirmQuotation(Quotation $quotation)
    {
        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()&&!Auth::user()->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        // Update the quotation status to 'confirmed'
        $quotation->status = 'confirmed';
        $quotation->save();

        return redirect()->route('quotations.index')->with('success', 'Quotation confirmed successfully.');
    }

    public function completeQuotation(Quotation $quotation)
    {
        // Ensure the user owns the quotation
        $user = Auth::user();
        if ($quotation->user_id !== Auth::id()&&!$user->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        // Update the quotation status to 'completed'
        $quotation->status = 'completed';
        $quotation->save();

        return redirect()->route('quotations.index')->with('success', 'Quotation completed successfully.');
    }

    public function updateQuotationNotGrouped(Request $request)
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve the user's active quotation
        $quotation = Quotation::where('user_id', $user->id)->where('status', 'pending')->first();

        if ($quotation) {
            // Loop through the quantities and update the pivot table
            foreach ($request->quantities as $productId => $quantity) {
                // Ensure the quantity is at least 1
                $quantity = max($quantity, 1);

                // Update the quantity in the pivot table
                $quotation->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
            }
        }

        return redirect()->back()->with('success', 'Quotation quantities updated successfully.');
    }

    public function updateQuotation(Request $request)
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve the user's active quotation
        $quotation = $request->quotation_id?Quotation::find($request->quotation_id):Quotation::where('user_id', $user->id)->where('status', 'pending')->first();
        //dd($quotation);
        if ($quotation) {
            // Loop through the quantities and prices from the form
            foreach ($request->quantities as $productId => $rooms) {
                foreach ($rooms as $productRoomId => $quantity) {
                    // Ensure the quantity is at least 1
                    $quantity = max($quantity, 1);

                    // Get the price for the current product and room
                    $price = $request->prices[$productId][$productRoomId] ?? 0;
                    $note = $request->note[$productId][$productRoomId] ?? '';

                    //dd($productId, $productRoomId, $quantity, $price, $note);

                    $products = $quotation->products()->wherePivot('product_id', $productId)->wherePivot('product_room_id', $productRoomId)->get();

                    if (!$quotation->products()
                        ->wherePivot('product_id', $productId)
                        ->wherePivot('product_room_id', $productRoomId)
                        ->exists()
                    ) {
                        dd('error 31256');

                    } else {
                        // dd($products);

                    }

                    //dd($productId, $productRoomId, $quantity, $price, $note);
                    //update by pivot don't works
                    $quotation->products()->wherePivot('product_id', $productId)
                        ->wherePivot('product_room_id', $productRoomId)
                        ->updateExistingPivot($productId, [
                            'quantity' => $quantity,
                            'price' => $price,
                            'note' => $note,
                            'product_room_id' => $productRoomId
                        ]);

                }
            }

            // Apply discount if the user is an admin
            if ($user->hasRole('Admin')) {
                $discount = $request->input('discount', 0);
                $total = $quotation->products->sum(fn($product) => $product->pivot->quantity * $product->pivot->price);
                $discountedTotal = $total - ($total * ($discount / 100));
                $quotation->discount = $discount;
                $quotation->total = $discountedTotal;
                $quotation->save();
            }
        }

        return redirect()->back()->with('success', 'Preventivo e quantità aggiornati con successo.');
    }

    public function exportQuotationToPdf($quotation)
    {
        // Get the current user
        $user = Auth::user();

        $quotation = Quotation::where('id', $quotation)
            ///->where('status', 'pending')
            ->with(['products'])
            ->first();

        // Group the products by room ID (product_room_id)
        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');



        // Load the PDF view with data
        $pdf = PDF::loadView('quotations.pdf', compact('quotation', 'productsGroupedByRoom'));

        // Return the PDF as a download
        return $pdf->download('quotation.pdf');
    }

    public function index()
    {
        // Get the current user
        $user = Auth::user();

        // Retrieve all quotations for the user
        if($user->hasRole('Admin')){
            $quotations = Quotation::with('products')->get();
        }else{
            $quotations = Quotation::where('user_id', $user->id)->with('products')->get();
        }
        return view('quotations.index', compact('quotations'));
    }

    public function edit(Quotation $quotation)
    {
        // Ensure the user owns the quotation
        $user = Auth::user();

        if ($quotation->user_id !== Auth::id()&&!$user->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        $rooms = Room::all(); // Fetch all rooms for the Select2 dropdown
        // Load the products associated with the quotation
        $quotation->load('products');

        return view('quotations.edit', compact('quotation', 'rooms'));
    }

    public function update(Request $request, Quotation $quotation)
    {
        // Ensure the user owns the quotation
        $user = Auth::user();
        if ($quotation->user_id !== Auth::id()&&!$user->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        // Validate the request data
        $request->validate([
            'status' => 'required|string',
            'quantities' => 'required|array',
            'quantities.*' => 'required|integer|min:1',
        ]);

        // Update the quotation status
        $quotation->status = $request->status;
        //$quotation->pdf_path = $request->pdf_path;
        $quotation->save();

        // Update product quantities
        foreach ($request->quantities as $productId => $quantity) {
            $quotation->products()->updateExistingPivot($productId, ['quantity' => $quantity]);
        }

        return redirect()->route('quotations.index')->with('success', 'Quotation updated successfully.');
    }

    public function destroy(Quotation $quotation)
    {
        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()&&!Auth::user()->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        // Delete the quotation
        $quotation->delete();

        return redirect()->route('quotations.index')->with('success', 'Quotation deleted successfully.');
    }

    public function deleteProduct(Quotation $quotation, Product $product)
    {
        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()&&!Auth::user()->hasRole('Admin')) {
            abort(403); // Unauthorized
        }

        // Detach the product from the quotation (remove it from the pivot table)
        $quotation->products()->detach($product->id);

        return redirect()->back()->with('success', 'Prodotto rimosso correttamente.');
    }

    public function sendQuotationEmail(Quotation $quotation)
    {
        // Ensure the user owns the quotation


        /*if ($quotation->user_id !== Auth::id()) {
            abort(403); // Unauthorized
        }*/

        // Eager load products and rooms
        $quotation->load('products');

        // Group the products by product_room_id
        $productsGroupedByRoom = $quotation->products->groupBy('pivot.product_room_id');


        //$pdf = PDF::loadView('quotations.pdf', compact('quotation', 'productsGroupedByRoom'));

        // Send the email with the attached PDF
        //Mail::to($quotation->user->email)->send(new SendQuotation($quotation));
        try {
            Mail::to($quotation->user->email)
                ->cc('info@fanale.name')
                ->cc('preventivi@mydomotics.it')
                ->send(new SendQuotation($quotation, $productsGroupedByRoom));
            return redirect()->back()->with('success', 'Quotation email to '.$quotation->user->email.' sent successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Failed to send '.$quotation->user->email.' quotation email: ' . $e->getMessage());
        }


    }

    public function removeFromQuotation(Product $product, Quotation $quotation, Room $room)
    {
        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()) {
            abort(403); // Unauthorized
        }

        // Detach the product from the quotation (remove it from the pivot table)
        $quotation->products()->wherePivot('product_id', $product->id)->wherePivot('product_room_id', $room->id)->detach();

       // $quotation->products()->detach($id);

        return redirect()->back()->with('success', 'Prodotto rimosso correttamente..');
    }

    public function addProductToQuotation(Request $request)
    {
        // Validate the request data
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quotation_id' => 'required|integer|exists:quotations,id',
            'roomId' => 'required|integer|exists:rooms,id',
        ]);

        // Retrieve the product, quotation, and room
        $product = Product::findOrFail($request->product_id);
        $quotation = Quotation::findOrFail($request->quotation_id);
        $room = Room::findOrFail($request->roomId);

        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }

        // Get the product price based on the user's role
        $productPrice = $product->priceByRole(Auth::user());

        // Check if the product is already in the quotation for the selected room
        if ($quotation->products()
            ->wherePivot('product_id', $product->id)
            ->wherePivot('product_room_id', $room->id)
            ->exists()
        ) {
            // If the product is already in the quotation for the selected room, update the quantity
            $quotation->products()->updateExistingPivot($product->id, [
                'quantity' => 1,
                'price' => $productPrice,
                'product_room_id' => $room->id,
            ]);
        } else {
            // Otherwise, attach the product with the selected room and other details
            $quotation->products()->attach($product->id, [
                'quantity' => 1,
                'price' => $productPrice,
                'product_room_id' => $room->id,
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Prodotto aggiunto correttamente']);
    }

    public function uploadPdf(Request $request, Quotation $quotation)
    {
        // Validate the request
        $request->validate([
            'pdf' => 'required|mimes:pdf|max:22048',
        ]);

        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }
        // Store the uploaded PDF
        if ($request->hasFile('pdf')) {
            $pdfPath = $request->file('pdf')->store('pdfs', 'public');

            // Update the quotation with the PDF path
            $quotation->pdf_path = $pdfPath;
            $quotation->save();

            return redirect()->back()->with('success', 'PDF uploaded successfully');
        }

        return redirect()->back()->with('error', 'Failed to upload PDF');
    }

    public function removePdf(Quotation $quotation)
    {
        // Ensure the user owns the quotation
        if ($quotation->user_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Unauthorized');
        }

        // Delete the PDF file from storage
        if ($quotation->pdf_path) {
            Storage::disk('public')->delete($quotation->pdf_path);
            $quotation->pdf_path = null;
            $quotation->save();

            return redirect()->back()->with('success', 'PDF removed successfully');
        }

        return redirect()->back()->with('error', 'No PDF to remove');
    }

    public function create()
    {
        return view('quotations.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Create a new quotation
        $quotation = new Quotation();
        $quotation->title = $request->title;
        $quotation->status = "pending";
        $quotation->user_id = Auth::id();
        $quotation->save();

        return redirect()->route('quotations.index')->with('success', 'Quotation created successfully.');
    }

    public function titleChange(Quotation $quotation)
    {
        return view('quotations.titlechange', compact('quotation'));
    }

    public function updateTitle(Request $request, Quotation $quotation)
    {
        // Validate the request data
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        // Update the quotation title
        $quotation->title = $request->title;
        $quotation->save();

        return redirect()->route('quotations.index')->with('success', 'Quotation title updated successfully.');
    }


}

