<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Http\Request;

/**
 * WardController
 *
 * Manages hospital wards (collections of rooms/beds).
 * Defines ward types (General, ICU, etc.).
 */
class WardController extends Controller
{
    /**
     * Display a listing of wards.
     *
     * Supports filtering by:
     * - Status: 'active', 'inactive'
     * - Search: Name
     * - Date Range: Creation date
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $query = Ward::query();

        if (request()->filled('search')) {
            $query->where('name', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status') && request('status') !== 'all') {
            $query->where('status', request('status'));
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $wards = $query->latest()->paginate(20)->withQueryString();
        return view('ipd.wards.index', compact('wards'));
    }

    /**
     * Show the form for creating a new ward.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('ipd.wards.create');
    }

    /**
     * Store a newly created ward in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:general,icu,cabin',
            'floor' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        Ward::create($request->only('name', 'type', 'floor', 'description', 'status'));
        return redirect()->route('ipd.wards.index')->with('success', 'Ward created');
    }

    /**
     * Show the form for editing the specified ward.
     *
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\View\View
     */
    public function edit(Ward $ward)
    {
        return view('ipd.wards.edit', compact('ward'));
    }

    /**
     * Update the specified ward in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ward  $ward
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Ward $ward)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|in:general,icu,cabin',
            'floor' => 'nullable|integer',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
        ]);
        $ward->update($request->only('name', 'type', 'floor', 'description', 'status'));
        return redirect()->route('ipd.wards.index')->with('success', 'Ward updated');
    }
}
