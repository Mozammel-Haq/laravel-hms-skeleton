<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

class ClinicApiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clinics = Clinic::with('images')->get();
        return response()->json(compact('clinics'));
    }
    public function show(Clinic $clinic)
    {
        $clinic->load('images');
        return response()->json(compact('clinic'));
    }
}
