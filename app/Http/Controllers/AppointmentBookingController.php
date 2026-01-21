<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
use App\Support\TenantContext;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;

class AppointmentBookingController extends Controller
{
    protected $appointmentService;

    public function __construct(AppointmentService $appointmentService)
    {
        $this->appointmentService = $appointmentService;
    }

    /**
     * Display list of doctors for booking.
     */
    public function index(Request $request)
    {
        $query = Doctor::with(['user', 'department', 'clinics']);

        if ($request->filled('status')) {
            if ($request->input('status') !== 'all') {
                $query->where('status', $request->input('status'));
            }
        } else {
            $query->where('status', 'active');
        }

        if (TenantContext::hasClinic()) {
            $currentClinicId = TenantContext::getClinicId();
            $query->whereHas('clinics', function ($q) use ($currentClinicId) {
                $q->where('clinics.id', $currentClinicId);
            });
        }

        if ($request->filled('clinic_id')) {
            $selectedClinicId = $request->input('clinic_id');
            $query->whereHas('clinics', function ($q) use ($selectedClinicId) {
                $q->where('clinics.id', $selectedClinicId);
            });
        }

        if ($request->filled('department_id')) {
            $query->where('primary_department_id', $request->input('department_id'));
        }

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->whereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%");
            });
        }

        $doctors = $query->latest()->paginate(12)->withQueryString();

        if (TenantContext::hasClinic()) {
            $currentClinicId = TenantContext::getClinicId();
            $clinics = Clinic::whereKey($currentClinicId)->get();
            $departments = \App\Models\Department::where('clinic_id', $currentClinicId)
                ->orderBy('name')
                ->get();
        } else {
            $clinics = Clinic::orderBy('name')->get();
            $departments = \App\Models\Department::orderBy('name')->get();
        }

        return view('appointments.booking.index', compact('doctors', 'clinics', 'departments'));
    }

    /**
     * Show booking calendar for a specific doctor.
     */
    public function show(Doctor $doctor, Request $request)
    {
        $doctor->load(['user', 'department', 'clinics']);

        // Fetch patients for selection (Limit to 50 for performance, or use AJAX search in real impl)
        // ideally we should use a search API, but for this prototype we'll load some.
        $patients = collect(); // Use AJAX search
        if ($request->has('patient_id') || old('patient_id')) {
            $patientId = $request->input('patient_id') ?? old('patient_id');
            $patient = Patient::find($patientId);
            if ($patient) {
                $patients->push($patient);
            }
        }


        return view('appointments.booking.show', compact('doctor', 'patients'));
    }

    /**
     * Get available slots for a date (AJAX).
     */
    public function getSlots(Request $request, Doctor $doctor)
    {
        $request->validate([
            'date' => 'required|date|after_or_equal:today',
            'clinic_id' => 'nullable|exists:clinics,id'
        ]);

        $clinic_id = $request->clinic_id;
        $slots = $this->appointmentService->getAvailableSlots($doctor, $request->date, $clinic_id);
        return response()->json(['slots' => $slots]);
    }

    /**
     * Get consultation fee (AJAX).
     */
    public function getFee(Request $request, Doctor $doctor)
    {
        $request->validate(['patient_id' => 'required|exists:patients,id']);
        $feeInfo = $this->appointmentService->calculateFee($doctor, $request->patient_id);
        return response()->json($feeInfo);
    }

    /**
     * Store appointment.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'doctor_id'        => 'required|exists:doctors,id',
            'patient_id'       => 'required|exists:patients,id',
            'clinic_id'        => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time'       => 'required|date_format:H:i',
            'end_time'         => 'required|date_format:H:i|after:start_time',
        ]);

        $appointmentDate = Carbon::parse($validated['appointment_date'])->toDateString();

        return DB::transaction(function () use ($validated, $appointmentDate) {
            $doctor = Doctor::where('id', $validated['doctor_id'])
                ->lockForUpdate()
                ->firstOrFail();

            $slotTaken = Appointment::where('doctor_id', $doctor->id)
                ->where('appointment_date', $appointmentDate)
                ->whereIn('status', ['pending', 'confirmed'])
                ->where(function ($q) use ($validated) {
                    $q->where('start_time', '<', $validated['end_time'])
                        ->where('end_time', '>', $validated['start_time']);
                })
                ->exists();

            if ($slotTaken) {
                return back()->withErrors([
                    'start_time' => 'This slot is no longer available. Please choose another time.',
                ]);
            }

            $feeInfo = $this->appointmentService
                ->calculateFee($doctor, $validated['patient_id']);

            $clinicId = TenantContext::hasClinic()
                ? TenantContext::getClinicId()
                : $validated['clinic_id'];

            $appointment = Appointment::create([
                'clinic_id'        => $clinicId,
                'doctor_id'        => $doctor->id,
                'patient_id'       => $validated['patient_id'],
                'department_id'    => $doctor->primary_department_id,
                'appointment_date' => $appointmentDate,
                'start_time'       => $validated['start_time'],
                'end_time'         => $validated['end_time'],
                'appointment_type' => 'in_person',
                'status'           => 'pending',
                'reason'           => 'Standard Appointment',
                'consultation_fee' => $feeInfo['consultation_fee'],
            ]);

            // Notify Doctor
            if ($doctor->user) {
                $doctor->user->notify(new AppointmentBookedNotification($appointment));
            }

            // Notify Patient (if they have a user account)
            $patient = Patient::find($validated['patient_id']);
            if ($patient && $patient->user) {
                $patient->user->notify(new AppointmentBookedNotification($appointment));
            }

            return redirect()->route('appointments.booking.show', $doctor)
                ->with('success', 'Appointment booked successfully! (ID: ' . $appointment->id . ')');
        });
    }
}
