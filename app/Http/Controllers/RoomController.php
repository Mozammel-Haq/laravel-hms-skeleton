<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\Ward;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $query = Room::withoutTenant()
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->select('rooms.*');

        if (request()->filled('search')) {
            $query->where('room_number', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status') && request('status') !== 'all') {
            $query->where('rooms.status', request('status'));
        }

        if (request()->filled('from')) {
            $query->whereDate('rooms.created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('rooms.created_at', '<=', request('to'));
        }

        $rooms = $query->latest('rooms.created_at')->paginate(20)->withQueryString();
        return view('ipd.rooms.index', compact('rooms'));
    }

    public function create()
    {
        $wards = Ward::orderBy('name')->get();
        return view('ipd.rooms.create', compact('wards'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'room_number' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        Room::create($request->only('ward_id', 'room_number', 'room_type', 'daily_rate', 'status'));
        return redirect()->route('ipd.rooms.index')->with('success', 'Room created');
    }

    public function edit(Room $room)
    {
        $wards = Ward::orderBy('name')->get();
        return view('ipd.rooms.edit', compact('room', 'wards'));
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'ward_id' => 'required|exists:wards,id',
            'room_number' => 'required|string|max:255',
            'room_type' => 'required|string|max:255',
            'daily_rate' => 'required|numeric|min:0',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        $room->update($request->only('ward_id', 'room_number', 'room_type', 'daily_rate', 'status'));
        return redirect()->route('ipd.rooms.index')->with('success', 'Room updated');
    }
}
