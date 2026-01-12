<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Clinic;
use App\Models\Doctor;
use App\Models\Patient;
use App\Services\AppointmentService;
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

        if ($request->has('clinic_id')) {
            $query->whereHas('clinics', function ($q) use ($request) {
                $q->where('clinics.id', $request->clinic_id);
            });
        }

        if ($request->has('department_id')) {
            $query->where('primary_department_id', $request->department_id);
        }

        $doctors = $query->paginate(12);
        $clinics = Clinic::all();
        $departments = \App\Models\Department::all();

        return view('appointments.booking.index', compact('doctors', 'clinics', 'departments'));
    }

    /**
     * Show booking calendar for a specific doctor.
     */
    public function show(Doctor $doctor)
    {
        $doctor->load(['user', 'department', 'clinics']);

        // Fetch patients for selection (Limit to 50 for performance, or use AJAX search in real impl)
        // ideally we should use a search API, but for this prototype we'll load some.
        $patients = Patient::limit(100)->get();

        return view('appointments.booking.show', compact('doctor', 'patients'));
    }

    /**
     * Get available slots for a date (AJAX).
     */
    public function getSlots(Request $request, Doctor $doctor)
    {
        $request->validate([
            'date' => 'required|date',
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
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'patient_id' => 'required|exists:patients,id',
            'clinic_id' => 'required|exists:clinics,id',
            'appointment_date' => 'required|date|after_or_equal:today',
            'start_time' => 'required',
            'end_time' => 'required',
        ]);

        // Double check availability
        // In a real app, use a lock or transaction with check
        $appointmentDate = Carbon::createFromFormat(
            'd-m-Y',
            $request->appointment_date
        )->format('Y-m-d');
        $exists = Appointment::where('doctor_id', $request->doctor_id)
            ->where('appointment_date', $appointmentDate)
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_time', [$request->start_time, $request->end_time])
                    ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->whereIn('status', ['pending', 'confirmed'])
            ->exists();

        if ($exists) {
            return back()->withErrors(['start_time' => 'This slot is no longer available.']);
        }

        // Calculate Fee
        $doctor = Doctor::find($request->doctor_id);
        $feeInfo = $this->appointmentService->calculateFee($doctor, $request->patient_id);

        $appointment = Appointment::create([
            'clinic_id' => $request->clinic_id,
            'doctor_id' => $request->doctor_id,
            'patient_id' => $request->patient_id,
            'department_id' => $doctor->primary_department_id,
            'appointment_date' => $appointmentDate,
            'start_time' => $request->start_time,
            'end_time' => $request->end_time,
            'appointment_type' => 'in_person', // Default
            'booking_source' => 'reception',
            'status' => 'pending',
            'created_by' => auth()->id(),
            'fee' => $feeInfo['fee'],
            'visit_type' => $feeInfo['type'],
        ]);

        return redirect()->route('appointments.index')->with('success', 'Appointment booked successfully.');
    }
}
