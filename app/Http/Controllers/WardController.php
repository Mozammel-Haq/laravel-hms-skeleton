<?php

namespace App\Http\Controllers;

use App\Models\Ward;
use Illuminate\Http\Request;

class WardController extends Controller
{
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

    public function create()
    {
        return view('ipd.wards.create');
    }

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

    public function edit(Ward $ward)
    {
        return view('ipd.wards.edit', compact('ward'));
    }

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
