<?php

namespace App\Http\Controllers;

use App\Models\Bed;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * BedController
 *
 * Manages hospital beds within rooms and wards.
 * Handles bed availability, creation, and reordering.
 */
class BedController extends Controller
{
    /**
     * Display a listing of beds.
     *
     * Supports filtering by:
     * - Status: 'available', 'occupied', 'maintenance'
     * - Search: Bed number
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->select('beds.*');

        if (request()->filled('search')) {
            $query->where('bed_number', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status') && request('status') !== 'all') {
            $query->where('beds.status', request('status'));
        }

        if (request()->filled('from')) {
            $query->whereDate('beds.created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('beds.created_at', '<=', request('to'));
        }

        $beds = $query->latest('beds.created_at')->paginate(20)->withQueryString();
        return view('ipd.beds.index', compact('beds'));
    }

    /**
     * Show the form for creating a new bed.
     *
     * @return \Illuminate\View\View
     */
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

    /**
     * Store a newly created bed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Show the form for editing the specified bed.
     *
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\View\View
     */
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

    /**
     * Update the specified bed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Bed  $bed
     * @return \Illuminate\Http\RedirectResponse
     */
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

    /**
     * Reorder beds within a room.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
