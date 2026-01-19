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
use Illuminate\Support\Facades\DB;

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
        $query = Admission::with(['patient', 'doctor', 'bedAssignments.bed']);

        if (request('status') === 'trashed') {
            $query->onlyTrashed();
        } else {
            $query->where('status', 'admitted')->latest();
        }

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->where('status', 'like', '%' . $search . '%')
                    ->orWhereHas('patient', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%')
                            ->orWhere('patient_code', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', '%' . $search . '%');
                    });
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $admissions = $query->paginate(20);
        $admissionsCount = Admission::where('status', 'admitted')->count();
        $bedsAvailable = \App\Models\Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->where('beds.status', 'available')
            ->count();
        $bedsOccupied = \App\Models\Bed::withoutTenant()
            ->join('rooms', 'beds.room_id', '=', 'rooms.id')
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->where('beds.status', 'occupied')
            ->count();
        $totalWards = Ward::where('clinic_id', auth()->user()->clinic_id)->count();
        $totalRooms = Room::withoutTenant()
            ->join('wards', 'rooms.ward_id', '=', 'wards.id')
            ->where('wards.clinic_id', auth()->user()->clinic_id)
            ->count();
        return view('ipd.index', compact('admissions', 'admissionsCount', 'bedsAvailable', 'bedsOccupied', 'totalWards', 'totalRooms'));
    }

    public function destroy(Admission $admission)
    {
        Gate::authorize('delete', $admission);
        $admission->delete();
        return redirect()->route('ipd.index')->with('success', 'Admission record deleted successfully.');
    }

    public function restore($id)
    {
        $admission = Admission::withTrashed()->findOrFail($id);
        Gate::authorize('delete', $admission);
        $admission->restore();
        return redirect()->route('ipd.index')->with('success', 'Admission record restored successfully.');
    }

    public function create(Request $request)
    {
        Gate::authorize('create', Admission::class);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can admit patients.');
        }
        $patients = collect();
        if ($request->has('patient_id') || old('patient_id')) {
            $patientId = $request->input('patient_id') ?? old('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $patients = collect([$patient]);
            }
        }

        $doctors = Doctor::where('status', 'active')->get();
        $wards = Ward::where('clinic_id', auth()->user()->clinic_id)
            ->with(['rooms.beds' => function ($query) {
                $query->orderBy('position')->orderBy('bed_number');
            }])
            ->orderBy('name')
            ->get();
        return view('ipd.create', compact('patients', 'doctors', 'wards'));
    }

    public function store(Request $request)
    {
        Gate::authorize('create', Admission::class);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can admit patients.');
        }

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'admission_date' => 'required|date',
            'admission_reason' => 'required|string',
            'admission_fee' => 'nullable|numeric|min:0',
            'deposit_amount' => 'nullable|numeric|min:0',
            'bed_id' => 'required|exists:beds,id',
            'discount' => 'nullable|numeric|min:0',
            'tax' => 'nullable|numeric|min:0',
        ]);

        $patient = Patient::findOrFail($request->patient_id);

        $admission = $this->ipdService->admitPatient(
            $patient,
            $request->doctor_id,
            $request->admission_date,
            $request->admission_reason
        );

        try {
            $this->ipdService->assignBed($admission, $request->bed_id);
        } catch (\Exception $e) {
            $admission->delete();
            return back()
                ->withInput()
                ->withErrors(['bed_id' => $e->getMessage()]);
        }

        // Admission fee invoice (optional)
        $fee = (float)($request->admission_fee ?? 0);
        if ($fee > 0) {
            $items = [[
                'item_type' => 'service',
                'reference_id' => null,
                'description' => 'IPD Admission Fee',
                'quantity' => 1,
                'unit_price' => $fee,
            ]];
            app(\App\Services\BillingService::class)->createInvoice(
                $patient,
                $items,
                appointmentId: null,
                discount: (float)($request->discount ?? 0),
                tax: (float)($request->tax ?? 0),
                visitId: null,
                invoiceType: 'ipd_admission_fee',
                createdBy: auth()->id(),
                finalize: true
            );
        }

        // Record deposit (optional)
        $deposit = (float)($request->deposit_amount ?? 0);
        if ($deposit > 0) {
            \App\Models\AdmissionDeposit::create([
                'clinic_id' => $admission->clinic_id,
                'admission_id' => $admission->id,
                'amount' => $deposit,
                'payment_method' => 'cash',
                'transaction_reference' => 'DEP-' . \Illuminate\Support\Str::upper(\Illuminate\Support\Str::random(8)),
                'received_at' => now(),
                'received_by' => auth()->id(),
            ]);
        }

        return redirect()->route('ipd.show', $admission)
            ->with('success', 'Patient admitted successfully.');
    }

    public function show(Admission $admission)
    {
        Gate::authorize('view', $admission);
        $admission->load(['patient', 'bedAssignments.bed', 'rounds.doctor', 'vitals']);
        return view('ipd.show', compact('admission'));
    }

    public function assignBed(Admission $admission)
    {
        Gate::authorize('update', $admission);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can assign beds.');
        }
        $admission->load(['bedAssignments.bed.room.ward']);
        $wards = Ward::where('clinic_id', auth()->user()->clinic_id)
            ->with(['rooms.beds' => function ($query) {
                $query->orderBy('position')->orderBy('bed_number');
            }])
            ->orderBy('name')
            ->get();
        return view('ipd.assign-bed', compact('admission', 'wards'));
    }

    public function storeBedAssignment(Request $request, Admission $admission)
    {
        Gate::authorize('update', $admission);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can assign beds.');
        }

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

    public function recommendDischarge(Admission $admission)
    {
        Gate::authorize('update', $admission);
        if (!auth()->user()->hasRole('Doctor')) {
            abort(403, 'Only Doctors can recommend discharge.');
        }

        $admission->update([
            'discharge_recommended' => true,
            'discharge_recommended_by' => auth()->id(),
        ]);

        return back()->with('success', 'Discharge recommended successfully.');
    }

    public function discharge(Admission $admission)
    {
        Gate::authorize('update', $admission);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can discharge patients.');
        }

        // Task 4: Check bills.
        $unpaidInvoices = \App\Models\Invoice::where('patient_id', $admission->patient_id)
            ->where('status', '!=', 'paid')
            ->exists();

        return view('ipd.discharge', compact('admission', 'unpaidInvoices'));
    }

    public function storeDischarge(Request $request, Admission $admission)
    {
        Gate::authorize('update', $admission);
        if (!auth()->user()->hasAnyRole(['Receptionist', 'Clinic Admin', 'Super Admin'])) {
            abort(403, 'Only Receptionist and Admin can discharge patients.');
        }

        // Task 4: Check bills.
        $unpaidInvoices = \App\Models\Invoice::where('patient_id', $admission->patient_id)
            ->where('status', '!=', 'paid')
            ->exists();

        if ($unpaidInvoices) {
            return back()->with('error', 'Patient has unpaid invoices. Please settle dues before discharge.');
        }

        $request->validate([
            'discharge_date' => ['required', 'date', 'after_or_equal:' . $admission->admission_date],
            'discount'       => ['nullable', 'numeric', 'min:0'],
            'tax'            => ['nullable', 'numeric', 'min:0'],
        ]);

        DB::transaction(function () use ($request, $admission) {
            $this->ipdService->dischargePatient($admission, $request->discharge_date);

            $this->ipdService->generateDischargeInvoice(
                $admission,
                $request->discharge_date,
                (float)$request->input('discount', 0),
                (float)$request->input('tax', 0)
            );
        });

        return redirect()->route('ipd.index')
            ->with('success', 'Patient discharged and invoice generated successfully.');
    }

    public function roundsIndex()
    {
        Gate::authorize('viewAny', Admission::class);
        $query = Admission::with(['patient', 'doctor'])
            ->where('status', 'admitted')
            ->latest();

        if (request()->filled('search')) {
            $search = request('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%')
                        ->orWhere('patient_code', 'like', '%' . $search . '%');
                })->orWhereHas('doctor.user', function ($sub) use ($search) {
                    $sub->where('name', 'like', '%' . $search . '%');
                })->orWhere('status', 'like', '%' . $search . '%');
            });
        }

        if (request()->filled('from')) {
            $query->whereDate('created_at', '>=', request('from'));
        }

        if (request()->filled('to')) {
            $query->whereDate('created_at', '<=', request('to'));
        }

        $admissions = $query->paginate(20);
        return view('ipd.rounds.index', compact('admissions'));
    }

    public function bedStatus()
    {
        Gate::authorize('viewAny', Admission::class);
        $wards = Ward::where('clinic_id', auth()->user()->clinic_id)
            ->with(['rooms.beds' => function ($query) {
                $query->orderBy('position')->orderBy('bed_number');
            }])
            ->orderBy('name')
            ->get();
        $bedsAvailable = Bed::where('status', 'available')->count();
        $bedsOccupied = Bed::where('status', 'occupied')->count();
        $bedAdmissions = Admission::with('patient')
            ->where('status', 'admitted')
            ->whereNotNull('current_bed_id')
            ->get()
            ->mapWithKeys(function ($admission) {
                return [
                    $admission->current_bed_id => [
                        'id' => $admission->id,
                        'patient_name' => $admission->patient->name ?? '',
                        'patient_code' => $admission->patient->patient_code ?? '',
                        'status' => $admission->status,
                        'admission_date' => $admission->admission_date,
                        'ipd_show_url' => route('ipd.show', $admission),
                    ],
                ];
            });
        return view('ipd.bed_status', compact('wards', 'bedsAvailable', 'bedsOccupied', 'bedAdmissions'));
    }

    public function createRound(Admission $admission)
    {
        Gate::authorize('update', $admission);

        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (!$doctor || $doctor->id !== $admission->doctor_id) {
            abort(403, 'Only the assigned doctor can create a round for this patient.');
        }

        return view('ipd.rounds.create', compact('admission'));
    }

    public function storeRound(Request $request, Admission $admission)
    {
        Gate::authorize('update', $admission);

        $doctor = Doctor::where('user_id', auth()->id())->first();
        if (!$doctor || $doctor->id !== $admission->doctor_id) {
            abort(403, 'Only the assigned doctor can create a round for this patient.');
        }

        $request->validate([
            'notes' => 'required|string',
            'round_date' => 'required|date',
        ]);

        \App\Models\InpatientRound::create([
            'clinic_id' => $admission->clinic_id,
            'admission_id' => $admission->id,
            'doctor_id' => $doctor->id,
            'notes' => $request->notes,
            'round_date' => $request->round_date,
        ]);

        return redirect()->route('ipd.show', $admission)->with('success', 'Round note added.');
    }
}
