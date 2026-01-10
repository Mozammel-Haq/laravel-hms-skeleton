<?php

namespace App\Http\Controllers;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\Room;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Ward;
use App\Services\IpdService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

class IpdController extends Controller
{
    protected $ipdService;

    public function __construct(IpdService $ipdService)
    {
        $this->ipdService = $ipdService;
    }

    public function index()
    {
        Gate::authorize('viewAny', Admission::class);
        $admissions = Admission::with(['patient', 'doctor', 'bedAssignments.bed'])
            ->where('status', 'admitted')
            ->latest()
            ->paginate(20);
        $admissionsCount = Admission::where('status', 'admitted')->count();
        
        $clinicId = \App\Support\TenantContext::getClinicId() ?? auth()->user()->clinic_id;

        $bedsAvailable = \App\Models\Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', $clinicId)
            ->where('beds.status', 'available')
            ->count();
        $bedsOccupied = \App\Models\Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', $clinicId)
            ->where('beds.status', 'occupied')
            ->count();
        $totalWards = Ward::where('clinic_id', $clinicId)->count();
        $totalRooms = Room::withoutTenant()
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', $clinicId)
            ->count();
        return view('ipd.index', compact('admissions', 'admissionsCount', 'bedsAvailable', 'bedsOccupied', 'totalWards', 'totalRooms'));
    }

    public function create()
    {
        Gate::authorize('create', Admission::class);
        $patients = Patient::all();
        $doctors = Doctor::where('status', 'active')->get();
        return view('ipd.create', compact('patients', 'doctors'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Admission::class);

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'admission_date' => 'required|date',
            'notes' => 'nullable|string',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $admission = $this->ipdService->admitPatient(
            $patient,
            $request->doctor_id,
            $request->admission_date,
            $request->notes
        );

        return redirect()->route('ipd.show', $admission)
            ->with('success', 'Patient admitted successfully.');
    }

    public function show(Admission $admission)
    {
        Gate::authorize('view', $admission);
        $admission->load(['patient', 'bedAssignments.bed']);
        return view('ipd.show', compact('admission'));
    }

    public function assignBed(Admission $admission)
    {
        Gate::authorize('update', $admission);
        $beds = Bed::where('status', 'available')->get();
        return view('ipd.assign-bed', compact('admission', 'beds'));
    }

    public function storeBedAssignment(Request $request, Admission $admission)
    {
        Gate::authorize('update', $admission);

        $request->validate([
            'bed_id' => 'required|exists:beds,id',
        ]);

        try {
            $this->ipdService->assignBed($admission, $request->bed_id);
            return redirect()->route('ipd.show', $admission)
                ->with('success', 'Bed assigned successfully.');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function discharge(Admission $admission)
    {
        Gate::authorize('update', $admission);
        return view('ipd.discharge', compact('admission'));
    }

    public function storeDischarge(Request $request, Admission $admission)
    {
        Gate::authorize('update', $admission);

        $request->validate([
            'discharge_date' => 'required|date|after_or_equal:admission_date',
        ]);

        $this->ipdService->dischargePatient($admission, $request->discharge_date);

        return redirect()->route('ipd.index')
            ->with('success', 'Patient discharged successfully.');
    }

    public function roundsIndex()
    {
        Gate::authorize('viewAny', Admission::class);
        $admissions = Admission::with(['patient', 'doctor'])
            ->where('status', 'admitted')
            ->latest()
            ->paginate(20);
        return view('ipd.rounds.index', compact('admissions'));
    }

    public function bedStatus()
    {
        Gate::authorize('viewAny', Admission::class);
        $wards = Ward::with(['rooms.beds'])->orderBy('name')->get();
        $bedsAvailable = Bed::where('status', 'available')->count();
        $bedsOccupied = Bed::where('status', 'occupied')->count();
        return view('ipd.bed_status', compact('wards', 'bedsAvailable', 'bedsOccupied'));
    }
}
