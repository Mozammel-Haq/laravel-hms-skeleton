<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Admission;
use App\Models\Ward;
use Illuminate\Support\Facades\Gate;

class IpdExtrasController extends Controller
{
    public function rounds()
    {
        Gate::authorize('view_ipd');
        $admissions = Admission::with(['patient', 'doctor'])->where('status', 'admitted')->latest()->get();
        return view('ipd.rounds.index', compact('admissions'));
    }

    public function bedStatus()
    {
        Gate::authorize('view_ipd');
        $wards = Ward::with(['rooms.beds'])->orderBy('name')->get();
        return view('ipd.bed_status', compact('wards'));
    }
}
