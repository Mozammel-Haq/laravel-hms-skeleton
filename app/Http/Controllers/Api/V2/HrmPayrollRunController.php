<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPayrollRun;
use App\Models\HrmPayslip;
use App\Models\Expense;
use App\Services\PayrollService;
use Illuminate\Http\Request;

class HrmPayrollRunController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = HrmPayrollRun::query()
            ->where('clinic_id', $user->clinic_id)
            ->with('processor')
            ->orderByDesc('period_end');

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($from = $request->date('from_date')) {
            $query->whereDate('period_start', '>=', $from);
        }

        if ($to = $request->date('to_date')) {
            $query->whereDate('period_end', '<=', $to);
        }

        $perPage = (int) $request->input('per_page', 20);
        $runs = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $runs,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'status' => 'nullable|in:draft,processing,completed,cancelled',
        ]);

        $run = HrmPayrollRun::create([
            'clinic_id' => $user->clinic_id,
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'status' => $validated['status'] ?? 'draft',
            'processed_by' => null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $run,
        ], 201);
    }

    public function update(Request $request, HrmPayrollRun $run, PayrollService $payrollService)
    {
        $user = $request->user();

        if ($run->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date',
            'status' => 'nullable|in:draft,processing,completed,cancelled',
            'total_gross' => 'nullable|numeric|min:0',
            'total_net' => 'nullable|numeric|min:0',
        ]);

        if (isset($validated['period_start']) && isset($validated['period_end'])) {
            if ($validated['period_end'] < $validated['period_start']) {
                return response()->json(['message' => 'Invalid period range'], 422);
            }
        }

        $shouldAutoProcess = false;

        if (isset($validated['status']) && in_array($validated['status'], ['processing', 'completed'], true)) {
            $hasManualTotals = false;

            if (array_key_exists('total_gross', $validated) || array_key_exists('total_net', $validated)) {
                $grossVal = $validated['total_gross'] ?? null;
                $netVal = $validated['total_net'] ?? null;

                $grossIsManual = $grossVal !== null && $grossVal !== '' && (float) $grossVal > 0;
                $netIsManual = $netVal !== null && $netVal !== '' && (float) $netVal > 0;

                $hasManualTotals = $grossIsManual || $netIsManual;
            }

            $shouldAutoProcess = ! $hasManualTotals;
        }

        if ($shouldAutoProcess) {
            $updateData = $validated;
            $updateData['status'] = 'processing';
            unset($updateData['total_gross'], $updateData['total_net']);

            $run->update($updateData);
            $run = $payrollService->processRun($run->fresh(), $user);
        } else {
            if (isset($validated['status']) && $validated['status'] === 'completed') {
                $validated['processed_by'] = $user->id;
            }

            $run->update($validated);
            $run->refresh();
        }

        return response()->json([
            'status' => 'success',
            'data' => $run,
        ]);
    }

    public function destroy(Request $request, HrmPayrollRun $run)
    {
        $user = $request->user();

        if ($run->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($run->status === 'completed') {
            return response()->json(['message' => 'Completed runs cannot be deleted'], 422);
        }

        HrmPayslip::query()
            ->where('clinic_id', $user->clinic_id)
            ->where('payroll_run_id', $run->id)
            ->delete();

        Expense::query()
            ->where('clinic_id', $user->clinic_id)
            ->where('reference_type', HrmPayrollRun::class)
            ->where('reference_id', $run->id)
            ->delete();

        $run->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }

    public function show(Request $request, HrmPayrollRun $run)
    {
        $user = $request->user();

        if ($run->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json([
            'status' => 'success',
            'data' => $run->load('processor'),
        ]);
    }
}
