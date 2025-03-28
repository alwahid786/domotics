<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class EstimationController extends Controller
{
    public function index(Request $request)
    {
        $estimations = DB::table('estimations')->get();
        return view('estimation.index', compact('estimations'));
    }
    public function create()
    {
        return view('estimation.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'productsData' => 'required|string',
            'totalPrice' => 'required|numeric',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/estimations'), $imageName);
            $imagePath = 'uploads/estimations/' . $imageName;
        }

        $user = Auth::user();
        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();

        $estimationId = DB::table('estimations')->insertGetId([
            'user_id' => $role->id,
            'image' => $imagePath,
            'total' => $validatedData['totalPrice'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $products = json_decode($validatedData['productsData'], true);

        $productData = [];
        foreach ($products as $product) {
            $productData[] = [
                'estimation_id' => $estimationId,
                'product_id' => $product['sensorId'],
                'x_position' => $product['x'],
                'y_position' => $product['y'],
                'name' => $product['name'],
                'price' => $product['price'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('estimation_products')->insert($productData);

        return redirect()->route('estimations.index')->with('success', 'Estimation saved successfully.');
    }

    public function sensors()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();

        if (!$role) {
            return response()->json(['error' => 'User has no assigned role'], 403);
        }

        $products = DB::table('products')
            ->join('product_role', 'products.id', '=', 'product_role.product_id')
            ->where('product_role.role_id', $role->id)
            ->select('products.*', 'product_role.price')
            ->orderBy('products.id', 'asc')
            ->get();

        return response()->json([
            'sensors' => $products
        ]);
    }

    public function show($estimate)
    {
        $estimate;
        return view('estimation.view', compact('estimate'));
    }

    public function fetch()
    {
        $estimations = DB::table('estimations')
            ->leftJoin('estimation_products', 'estimations.id', '=', 'estimation_products.estimation_id')
            ->select(
                'estimations.id as estimation_id',
                'estimations.user_id',
                'estimations.image',
                'estimations.total',
                'estimations.created_at',
                'estimation_products.id as product_id',
                'estimation_products.name as product_name',
                'estimation_products.price as product_price',
                'estimation_products.x_position',
                'estimation_products.y_position'
            )
            ->get();

        return response()->json($estimations);
    }
}
