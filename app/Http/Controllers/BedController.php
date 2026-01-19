<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BedController extends Controller
{
    public function index()
    {
        $beds = Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->orderBy('beds.bed_number')
            ->select('beds.*')
            ->paginate(20);
        return view('ipd.beds.index', compact('beds'));
    }

    public function create()
    {
        $rooms = Room::withoutTenant()
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->select('rooms.*')
            ->orderBy('rooms.room_number')
            ->get();
        return view('ipd.beds.create', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_number' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        Bed::create($request->only('room_id', 'bed_number', 'status') + ['clinic_id' => auth()->user()->clinic_id]);
        return redirect()->route('ipd.beds.index')->with('success', 'Bed created');
    }

    public function edit(Bed $bed)
    {
        $rooms = Room::withoutTenant()
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->select('rooms.*')
            ->orderBy('rooms.room_number')
            ->get();
        return view('ipd.beds.edit', compact('bed', 'rooms'));
    }

    public function update(Request $request, Bed $bed)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'bed_number' => 'required|string|max:255',
            'status' => 'required|in:available,occupied,maintenance',
        ]);
        $bed->update($request->only('room_id', 'bed_number', 'status'));
        return redirect()->route('ipd.beds.index')->with('success', 'Bed updated');
    }

    public function reorder(Request $request)
    {
        $request->validate([
            'room_id' => 'required|integer|exists:rooms,id',
            'order' => 'required|array',
            'order.*' => 'integer|exists:beds,id',
        ]);

        $clinicId = auth()->user()->clinic_id;
        $roomId = $request->room_id;
        $bedIds = $request->order;

        $validBedIds = Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', $clinicId)
            ->where('rooms.id', $roomId)
            ->whereIn('beds.id', $bedIds)
            ->pluck('beds.id')
            ->all();

        if (count($validBedIds) !== count($bedIds)) {
            return response()->json(['status' => 'error'], 422);
        }

        DB::transaction(function () use ($bedIds) {
            foreach ($bedIds as $index => $id) {
                Bed::where('id', $id)->update(['position' => $index + 1]);
            }
        });

        return response()->json(['status' => 'ok']);
    }
}
