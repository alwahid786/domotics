<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderby('code')->get();
        return view('room.index', compact('rooms'));
    }
    public function create()
    {
        return view('room.create');
    }

    public function store(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $room->image;
        if ($request->file('image')) {
            $directory = 'rooms';
            /*if (!Storage::disk('public')->exists($directory)) {
                Storage::disk('public')->makeDirectory($directory);
            }*/

            $imagePath = $request->file('image')->store($directory, 'public');


        }
        Room::create([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'slug' => Str::slug($request->name),
            'image' => $imagePath,
        ]);

        return redirect()->route('room.index')->with('success', 'Room created successfully.');
    }

    public function edit(Room $room)
    {
        return view('room.edit', compact('room'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'code' => 'nullable|int',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $request->file('image') ? $request->file('image')->store('rooms', 'public') : $room->image;
//dd($request->code);
        $room->update([
            'name' => $request->name,
            'code' => $request->code,
            'description' => $request->description,
            'image' => $imagePath,
        ]);

        return redirect()->route('room.index')->with('success', 'Room updated successfully.');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('room.index')->with('success', 'Room deleted successfully.');
    }
}
