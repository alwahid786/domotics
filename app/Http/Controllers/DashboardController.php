<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Room;


class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $rooms = Room::orderByRaw('CAST(code AS UNSIGNED) ASC')->get();
        return view('dashboard', compact('rooms'));
    }
}
