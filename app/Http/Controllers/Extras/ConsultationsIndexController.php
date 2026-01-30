<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\Consultation;
use Illuminate\Support\Facades\Gate;

/**
 * Class ConsultationsIndexController
 *
 * Manages the display of consultations.
 *
 * @package App\Http\Controllers\Extras
 */
class ConsultationsIndexController extends Controller
{
    /**
     * Display a listing of recent consultations.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        Gate::authorize('view_consultations');
        $consultations = Consultation::with(['patient','doctor'])->latest()->take(50)->get();
        return view('clinical.consultations.index', compact('consultations'));
    }
}
