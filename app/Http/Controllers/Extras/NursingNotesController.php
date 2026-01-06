<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Admission;

class NursingNotesController extends Controller
{
    public function index()
    {
        $admissions = Admission::with(['patient'])->where('status','admitted')->latest()->take(20)->get();
        return view('nursing.notes.index', compact('admissions'));
    }
}
