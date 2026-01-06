<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Patient;

class VitalsController extends Controller
{
    public function record()
    {
        $patients = Patient::orderBy('name')->take(100)->get();
        return view('vitals.record', compact('patients'));
    }

    public function history()
    {
        return view('vitals.history');
    }
}
