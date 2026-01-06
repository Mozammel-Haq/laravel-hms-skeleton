<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Support\Facades\Gate;

class ConsultationsIndexController extends Controller
{
    public function index()
    {
        Gate::authorize('view_consultations');
        $consultations = Consultation::with(['patient','doctor'])->latest()->take(50)->get();
        return view('clinical.consultations.index', compact('consultations'));
    }
}
