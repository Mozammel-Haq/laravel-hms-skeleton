<?php

namespace App\Http\Controllers;

use App\Models\LabTest;
use Illuminate\Http\Request;

class LabTestController extends Controller
{
    public function index()
    {
        $tests = LabTest::orderBy('name')->paginate(20);
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
