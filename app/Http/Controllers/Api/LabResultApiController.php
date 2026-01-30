<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\LabTestOrder;
use App\Models\Patient;
use App\Support\TenantContext;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

/**
 * LabResultApiController
 *
 * Handles API requests for patient lab results.
 * Retrieves lab orders and their associated results.
 */
class LabResultApiController extends Controller
{
    /**
     * Display a listing of lab results.
     * Supports filtering by status and search.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
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
        $query = LabTestOrder::where('patient_id', $targetPatient->id)
            ->with(['test', 'results', 'doctor.user']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('test', function ($sub) use ($search) {
                    $sub->where('name', 'like', "%{$search}%");
                })
                    ->orWhereHas('doctor.user', function ($sub) use ($search) {
                        $sub->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('status') && $request->status !== 'All') {
            // Map frontend status to backend status if needed.
            // Frontend uses: Normal, Attention, Critical, Pending.
            // Backend LabTestOrder status: pending, completed, cancelled.
            // Backend LabTestResult doesn't have a status column per se, but 'result_value' or we infer it?
            // Actually, the frontend filters by result content often, but here it sends 'status'.
            // If status is 'Pending', we check order status.
            if ($request->status === 'Pending') {
                $query->where('status', 'pending');
            } elseif ($request->status === 'Completed') {
                $query->where('status', 'completed');
            }
            // 'Normal', 'Attention', 'Critical' are likely derived from result values which is hard to filter in DB without structured data.
            // We'll leave advanced result filtering to client-side or assume status maps to order status for now.
        }

        $labOrders = $query->orderBy('order_date', 'desc')->get();

        $results = $labOrders->map(function ($order) {
            $result = $order->results->first(); // Assuming one result per order for simplicity, or we can list all

            return [
                'id' => $order->id,
                'testName' => $order->test ? ($order->test->name ?? 'Unknown Test') : 'Unknown Test',
                'date' => $order->order_date ? $order->order_date->format('Y-m-d') : '',
                'status' => ucfirst($order->status),
                'doctor' => $order->doctor && $order->doctor->user ? $order->doctor->user->name : 'Unknown Doctor',
                'result' => $result ? ($result->result_value . ' ' . $result->unit) : 'Pending',
                'file' => $result && $result->pdf_path ? URL::signedRoute('patient.lab-results.download', ['result' => $result->id]) : null,
            ];
        });

        return response()->json([
            'results' => $results
        ]);
    }
}
