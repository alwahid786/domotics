<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class EstimationController extends Controller
{
    public function index(Request $request)
    {   
        return view('estimation.index');
    }
    public function create()
    {   
        return view('estimation.create');
    }
    public function store(Request $request)
    {   
        dd($request->all());
        return view('estimation.create');
    }
    public function sensors()
    {
        $products = Product::orderBy('id', 'asc')->get();
        return response()->json([
            'sensors' => $products
        ]);
    }
}
