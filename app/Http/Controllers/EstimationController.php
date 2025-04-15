<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendEstimation;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class EstimationController extends Controller
{
    public function index(Request $request)
    {
        $estimations = DB::table('estimations')->orderby('id')->latest()->paginate(25);
        return view('estimation.index', compact('estimations'));
    }
    public function create()
    {
        return view('estimation.create');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'roomsData' => 'required|string',
            'sensorsData' => 'required|string',
            'totalPrice' => 'required|numeric',
            'floorName' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:png,jpg,jpeg|max:2048',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/estimations'), $imageName);
            $imagePath = 'uploads/estimations/' . $imageName;
        }

        // Get authenticated user and role
        $user = Auth::user();
        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();

        // Create estimation entry
        $estimationId = DB::table('estimations')->insertGetId([
            'user_id' => $role->id,
            'image' => $imagePath,
            'total' => $validatedData['totalPrice'],
            'floor_name' => $validatedData['floorName'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Store room coordinates
        $rooms = json_decode($validatedData['roomsData'], true);
        $roomData = [];

        foreach ($rooms as $room) {
            foreach ($room['coordinates'] as $coordinate) {
                $roomData[] = [
                    'room_id' => $room['id'],
                    'estimation_id' => $estimationId,
                    'x_position' => $coordinate['x'],
                    'y_position' => $coordinate['y'],
                    'room_name' => $room['roomName'],
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        }

        DB::table('estimation_room')->insert($roomData);

        // Store sensors
        $sensors = json_decode($validatedData['sensorsData'], true);
        $productData = [];

        foreach ($sensors as $sensor) {
            $productData[] = [
                'estimation_id' => $estimationId,
                'product_id' => $sensor['sensorId'] ?? null,
                'room_id' => $sensor['roomId'],
                'x_position' => $sensor['sensorCoordinates']['x'],
                'y_position' => $sensor['sensorCoordinates']['y'],
                'name' => $sensor['sensorName'],
                'price' => $sensor['sensorPrice'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $productData = DB::table('estimation_products')->insert($productData);

        if ($productData) {
            Mail::to($user->email)
            ->cc('dott.izzo@mydomotics.it')
            ->cc('preventivi@mydomotics.it')
            ->send(new SendEstimation($validatedData['roomsData'], $validatedData['sensorsData'], $validatedData['totalPrice'], $validatedData['floorName'], $validatedData['image']));
            
            return response()->json([
                'success' => true,
                'type' => 'success',
                'message' => 'Stima creata con successo.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'Errore durante la creazione della stima.',
            ]);
        }
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

    public function fetch(Request $request)
    {
        $id = $request->estimate; 
        $estimation = DB::table('estimations')->where('id', $id)->first();
        if (!$estimation) {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'Estimation not found.',
            ], 404);
        }
    
        // Fetch room coordinates grouped by room name
        $roomsRaw = DB::table('estimation_room')
            ->where('estimation_id', $id)
            ->get();

        $roomsGrouped = [];
        foreach ($roomsRaw as $room) {
            $key = $room->room_name;
            if (!isset($roomsGrouped[$key])) {
                $roomsGrouped[$key] = [
                    'roomName' => $room->room_name,
                    'roomId' => $room->room_id,
                    'coordinates' => [],
                ];
            }
            $roomsGrouped[$key]['coordinates'][] = [
                'x' => $room->x_position,
                'y' => $room->y_position,
            ];
        }
    
        $roomsData = array_values($roomsGrouped);
    
        // Fetch sensors
        $sensorsRaw = DB::table('estimation_products')
            ->where('estimation_id', $id)
            ->get();
    
        $sensorsData = [];
        foreach ($sensorsRaw as $sensor) {
            $sensorsData[] = [
                'sensorId' => $sensor->product_id,
                'sensorName' => $sensor->name,
                'sensorPrice' => $sensor->price,
                'roomId' => $sensor->room_id,
                'productId' => $sensor->product_id,
                'sensorCoordinates' => [
                    'x' => $sensor->x_position,
                    'y' => $sensor->y_position,
                ],
            ];
        }
    
        // Prepare the response
        return response()->json([
            'success' => true,
            'type' => 'success',
            'message' => 'Estimation fetched successfully.',
            'data' => [
                'roomsData' => ($roomsData),
                'sensorsData' => ($sensorsData),
                'totalPrice' => $estimation->total,
                'floorName' => $estimation->floor_name,
                'image' => $estimation->image ? asset($estimation->image) : null,
            ],
        ]);
    }
    public function destroy($estimate)
    {
        DB::table('estimations')->where('id', $estimate)->delete();
        DB::table('estimation_products')->where('estimation_id', $estimate)->delete();

        return redirect()->route('estimations.index')
            ->withSuccess('Stime cancellato con successo.');
    }

    
}
