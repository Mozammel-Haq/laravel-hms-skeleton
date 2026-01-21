<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    public function index()
    {
        $query = LabTest::query();

        if (request()->filled('search')) {
            $query->where('name', 'like', '%' . request('search') . '%')
                ->orWhere('category', 'like', '%' . request('search') . '%');
        }

        if (request()->filled('status')) {
            $query->where('status', request('status'));
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $tests = $query->latest()->paginate(20)->withQueryString();
        return view('lab.catalog.index', compact('tests'));
    }

    public function create()
    {
        return view('lab.catalog.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
            'normal_range' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'status' => 'required|in:active,inactive',
        ]);
        LabTest::create($request->only('name', 'category', 'description', 'normal_range', 'price', 'status'));
        return redirect()->route('lab.catalog.index')->with('success', 'Lab test added');
    }
}
