<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Support\TenantContext;
use Illuminate\Http\Request;

class LabResultApiController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $requestedClinicId = $request->header('X-Clinic-ID');
        $targetPatient = $user;

        // Dynamic patient resolution
        if ($requestedClinicId && $requestedClinicId != $user->clinic_id) {
            $foundPatient = Patient::withoutTenant()
                ->where('clinic_id', $requestedClinicId)
                ->where(function ($q) use ($user) {
                    if ($user->email) $q->where('email', $user->email);
                    if ($user->phone) $q->orWhere('phone', $user->phone);
                })
                ->first();

            if ($foundPatient) {
                $targetPatient = $foundPatient;
            }
        }

        $clinicId = $targetPatient->clinic_id;
        TenantContext::setClinicId($clinicId);

        // Fetch Lab Orders with Results
        // LabTestOrder is BaseTenantModel, so it respects TenantContext
        $labOrders = LabTestOrder::where('patient_id', $targetPatient->id)
            ->with(['test', 'results', 'doctor.user'])
            ->orderBy('order_date', 'desc')
            ->get();

        $results = $labOrders->map(function ($order) {
            $result = $order->results->first(); // Assuming one result per order for simplicity, or we can list all

            return [
                'id' => $order->id,
                'testName' => $order->test ? ($order->test->name ?? 'Unknown Test') : 'Unknown Test',
                'date' => $order->order_date ? $order->order_date->format('Y-m-d') : '',
                'status' => ucfirst($order->status),
                'doctor' => $order->doctor && $order->doctor->user ? $order->doctor->user->name : 'Unknown Doctor',
                'result' => $result ? ($result->result_value . ' ' . $result->unit) : 'Pending',
                'file' => $result && $result->pdf_path ? asset('storage/' . $result->pdf_path) : null,
            ];
        });

        return response()->json([
            'results' => $results
        ]);
    }
}
