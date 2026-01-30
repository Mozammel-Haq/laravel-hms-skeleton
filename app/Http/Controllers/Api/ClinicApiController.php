<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clinic;
use Illuminate\Http\Request;

/**
 * ClinicApiController
 *
 * Handles API requests related to clinics.
 * Allows retrieving a list of clinics and their details.
 */
class ClinicApiController extends Controller
{
    /**
     * Display a listing of all clinics.
     * Includes associated images.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        $clinics = Clinic::with('images')->get();
        return response()->json(compact('clinics'));
    }
    /**
     * Display the specified clinic details.
     * Includes associated images.
     *
     * @param  \App\Models\Clinic  $clinic
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Clinic $clinic)
    {
        $clinic->load('images');
        return response()->json(compact('clinic'));
    }
}
