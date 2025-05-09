<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Mail\SendEstimation;
use App\Models\Product;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class EstimationController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();

        if ($role->id == 1 || $role->id == 2) {
            $estimations = DB::table('estimations')
                ->join('users', 'users.id', '=', 'estimations.user_id')
                ->select('estimations.*', 'users.name as user_name')
                ->orderBy('estimations.id', 'desc')
                ->paginate(25);
        } else {
            $estimations = DB::table('estimations')
                ->join('users', 'users.id', '=', 'estimations.user_id')
                ->select('estimations.*', 'users.name as user_name')
                ->where('estimations.user_id', '=', $user->id)
                ->orderBy('estimations.id', 'desc')
                ->paginate(25);
        }

        $roleId = $role->id;
        return view('estimation.index', compact('estimations', 'roleId'));
    }
    public function create()
    {
        $user = Auth::user();
        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();
        $roleId = $role->id;
        $users = DB::table('users')->where('id', '!=', Auth::user()->id)->get();
        return view('estimation.create', compact('users', 'roleId'));
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'roomsData' => 'required|string',
            'sensorsData' => 'required|string',
            'totalPrice' => 'required|numeric',
            'floorName' => 'required|string|max:255',
        ]);

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/estimations'), $imageName);
            $imagePath = 'uploads/estimations/' . $imageName;
        }

        $cleanimagePath = null;
        if ($request->hasFile('image_clean')) {
            $cleanimage = $request->file('image_clean');
            $cleanimageName = 'clean_' . time() . '_' . $cleanimage->getClientOriginalName();
            $cleanimage->move(public_path('uploads/estimations'), $cleanimageName);
            $cleanimagePath = 'uploads/estimations/' . $cleanimageName;
        }

        $user = Auth::user();
        // Create estimation entry
        $estimationId = DB::table('estimations')->insertGetId([
            'user_id' => $request->user_id ? $request->user_id : $user->id,
            'image' => $imagePath,
            'clean_image' => $cleanimagePath,
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
                'note' => $sensor['sensorDescription'],
                'price' => $sensor['sensorPrice'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $productData = DB::table('estimation_products')->insert($productData);

        // Fetch for PDF generation
        $estimation = DB::table('estimations')->where('id', $estimationId)->first();
        $roomsRaw = DB::table('estimation_room')->where('estimation_id', $estimationId)->get();
        $sensors = DB::table('estimation_products')
            ->join('products', 'estimation_products.product_id', '=', 'products.id')
            ->where('estimation_products.estimation_id', $estimationId)
            ->select('estimation_products.*', 'products.image as product_image')
            ->get();


        // Group rooms
        $rooms = [];
        foreach ($roomsRaw as $row) {
            $rooms[$row->room_id]['roomName'] = $row->room_name;
            $rooms[$row->room_id]['roomId'] = $row->room_id;
            $rooms[$row->room_id]['coordinates'][] = ['x' => $row->x_position, 'y' => $row->y_position];
        }
        $rooms = array_values($rooms);

        // Generate PDF
        $pdf = Pdf::loadView('estimation.pdf', [
            'imagePath' => $estimation->image,
            'user_name' => $this->userName($estimation->user_id),
            'totalPrice' => $estimation->total,
            'floorName' => $estimation->floor_name,
            'roomsData' => $rooms,
            'sensorsData' => $sensors,
        ]);

        $pdfFileName = "estimation_{$estimationId}.pdf";
        $pdfPath = storage_path("app/private/estimations/{$pdfFileName}");
        file_put_contents($pdfPath, $pdf->output());

        // $user_id = $request->user_id ? $request->user_id : $user->id;
        // $user = DB::table('users')->where('id', $user_id)->first();
        // Mail::to($user->email)
        //     ->cc('dott.izzo@mydomotics.it')
        //     ->cc('preventivi@mydomotics.it')
        //     ->send(new SendEstimation($pdfFileName));

        if ($productData) {

            $downloadUrl = route('estimations.download', ['file' => $pdfFileName]);
            return response()->json([
                'success' => true,
                'type' => 'success',
                'message' => 'Stima creata con successo.',
                'download_url' => $downloadUrl,
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

    public function rooms()
    {
        $rooms = DB::table('rooms')
            ->select('*')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'rooms' => $rooms
        ]);
    }

    public function show($estimate)
    {
        $estimate;
        $estimation = DB::table('estimations')->where('id', $estimate)->first();
        $user_name = $this->userName($estimation->user_id);
        return view('estimation.view', compact('estimate', 'user_name'));
    }

    protected function userName($user_id)
    {
        $user = DB::table('users')->where('id', $user_id)->first();
        return $user->name;
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
                'sensorImage' => $this->productImage($sensor->product_id),
                'productName' => $this->productName($sensor->product_id),
                'productCode' => $this->productCode($sensor->product_id),
                'sensorName' => $sensor->name,
                'sensorDescription' => $sensor->note,
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
                'clean_image' => $estimation->clean_image ? asset($estimation->clean_image) : null,
            ],
        ]);
    }
    public function destroy($estimateId)
    {
        $estimation = DB::table('estimations')->where('id', $estimateId)->first();

        if ($estimation) {
            if ($estimation->image && file_exists(public_path($estimation->image))) {
                unlink(public_path($estimation->image));
            }
            if ($estimation->clean_image && file_exists(public_path($estimation->clean_image))) {
                unlink(public_path($estimation->clean_image));
            }
            DB::table('estimations')->where('id', $estimateId)->delete();
            DB::table('estimation_products')->where('estimation_id', $estimateId)->delete();
            DB::table('estimation_room')->where('estimation_id', $estimateId)->delete();

            return redirect()->route('estimations.index')
                ->withSuccess('Stime cancellato con successo.');
        }

        return redirect()->route('estimations.index')
            ->withErrors('Estimation not found.');
    }


    protected function productImage($id)
    {
        $image =  DB::table('products')->select('image')->where('id', $id)->first();
        return $image;
    }

    protected function productName($id)
    {
        $data =  DB::table('products')->select('name')->where('id', $id)->first();
        return $data->name;
    }
    protected function productCode($id)
    {
        $data =  DB::table('products')->select('code')->where('id', $id)->first();
        return $data->code;
    }


    public function Edit($estimate)
    {

        $id = $estimate;
        $estimation = DB::table('estimations')->select('id', 'user_id', 'total', 'floor_name', 'clean_image')->where('id', $id)->first();


        $user = Auth::user();
        $role = DB::table('roles')
            ->join('model_has_roles', 'roles.id', '=', 'model_has_roles.role_id')
            ->where('model_has_roles.model_id', $user->id)
            ->select('roles.id')
            ->first();
        $roleId = $role->id;
        $users = DB::table('users')->where('id', '!=', Auth::user()->id)->get();


        if (!$estimation) {
            return redirect()->route('estimations.index')->with('error', 'Estimation not found.');
        }

        return view('estimation.edit', compact('estimation', 'users', 'roleId'));
    }


    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'roomsData' => 'required|string',
            'sensorsData' => 'required|string',
            'totalPrice' => 'required|numeric',
            'floorName' => 'required|string|max:255',
        ]);

        $estimationId = $request->input('id');

        $estimation = DB::table('estimations')->where('id', $estimationId)->first();
        if ($estimation) {
            if ($estimation->image && file_exists(public_path($estimation->image))) {
                unlink(public_path($estimation->image));
            }
            if ($estimation->clean_image && file_exists(public_path($estimation->clean_image))) {
                unlink(public_path($estimation->clean_image));
            }
            DB::table('estimation_products')->where('estimation_id', $estimationId)->delete();
            DB::table('estimation_room')->where('estimation_id', $estimationId)->delete();
        }

        // Handle image upload
        $imagePath = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '_' . $image->getClientOriginalName();
            $image->move(public_path('uploads/estimations'), $imageName);
            $imagePath = 'uploads/estimations/' . $imageName;
        }

        $cleanimagePath = null;
        if ($request->hasFile('image_clean')) {
            $cleanimage = $request->file('image_clean');
            $cleanimageName = 'clean_' . time() . '_' . $cleanimage->getClientOriginalName();
            $cleanimage->move(public_path('uploads/estimations'), $cleanimageName);
            $cleanimagePath = 'uploads/estimations/' . $cleanimageName;
        }

        if ($request->user_id) {
            DB::table('estimations')
                ->where('id', $estimationId)
                ->update([
                    'user_id' => $request->user_id,
                    'image' => $imagePath,
                    'clean_image' => $cleanimagePath,
                    'total' => $validatedData['totalPrice'],
                    'floor_name' => $validatedData['floorName'],
                    'updated_at' => now(),
                ]);
        } else {
            DB::table('estimations')
                ->where('id', $estimationId)
                ->update([
                    'image' => $imagePath,
                    'clean_image' => $cleanimagePath,
                    'total' => $validatedData['totalPrice'],
                    'floor_name' => $validatedData['floorName'],
                    'updated_at' => now(),
                ]);
        }

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
                'note' => $sensor['sensorDescription'],
                'price' => $sensor['sensorPrice'],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        $productData = DB::table('estimation_products')->insert($productData);

        // Fetch for PDF generation
        $estimation = DB::table('estimations')->where('id', $estimationId)->first();
        $roomsRaw = DB::table('estimation_room')->where('estimation_id', $estimationId)->get();
        $sensors = DB::table('estimation_products')
            ->join('products', 'estimation_products.product_id', '=', 'products.id')
            ->where('estimation_products.estimation_id', $estimationId)
            ->select('estimation_products.*', 'products.image as product_image')
            ->get();


        // Group rooms
        $rooms = [];
        foreach ($roomsRaw as $row) {
            $rooms[$row->room_id]['roomName'] = $row->room_name;
            $rooms[$row->room_id]['roomId'] = $row->room_id;
            $rooms[$row->room_id]['coordinates'][] = ['x' => $row->x_position, 'y' => $row->y_position];
        }
        $rooms = array_values($rooms);

        // Generate PDF
        $pdf = Pdf::loadView('estimation.pdf', [
            'imagePath' => $estimation->image,
            'user_name' => $this->userName($estimation->user_id),
            'totalPrice' => $estimation->total,
            'floorName' => $estimation->floor_name,
            'roomsData' => $rooms,
            'sensorsData' => $sensors,
        ]);

        $pdfFileName = "estimation_{$estimationId}.pdf";
        $pdfPath = storage_path("app/private/estimations/{$pdfFileName}");
        file_put_contents($pdfPath, $pdf->output());

        // $user = Auth::user();
        // $user_id = $request->user_id ? $request->user_id : $user->id;
        // $user = DB::table('users')->where('id', $user_id)->first();
        // Mail::to($user->email)
        //     ->cc('dott.izzo@mydomotics.it')
        //     ->cc('preventivi@mydomotics.it')
        //     ->send(new SendEstimation($pdfFileName));

        if ($productData) {

            $downloadUrl = route('estimations.download', ['file' => $pdfFileName]);
            return response()->json([
                'success' => true,
                'type' => 'success',
                'message' => 'Stima creata con successo.',
                'download_url' => $downloadUrl,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'type' => 'error',
                'message' => 'Errore durante la creazione della stima.',
            ]);
        }
    }
}
