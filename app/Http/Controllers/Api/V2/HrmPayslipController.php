<?php

namespace App\Http\Controllers\Api\V2;

use App\Http\Controllers\Controller;
use App\Models\HrmPayslip;
use App\Models\HrmPayrollRun;
use App\Models\User;
use Illuminate\Http\Request;

class HrmPayslipController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $query = HrmPayslip::query()
            ->where('clinic_id', $user->clinic_id)
            ->with(['user', 'run'])
            ->orderByDesc('period_end');

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            $query->where('user_id', $user->id);
        }

        if ($status = $request->string('status')->toString()) {
            $query->where('status', $status);
        }

        if ($from = $request->date('from_date')) {
            $query->whereDate('period_start', '>=', $from);
        }

        if ($to = $request->date('to_date')) {
            $query->whereDate('period_end', '<=', $to);
        }

        if ($request->filled('payroll_run_id')) {
            $query->where('payroll_run_id', $request->input('payroll_run_id'));
        }

        if ($request->filled('user_id') && ($user->can('view_reports') || $user->can('view_financial_reports'))) {
            $query->where('user_id', $request->input('user_id'));
        }

        $perPage = (int) $request->input('per_page', 20);
        $payslips = $query->paginate($perPage);

        return response()->json([
            'status' => 'success',
            'data' => $payslips,
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'payroll_run_id' => 'nullable|exists:hrm_payroll_runs,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'basic' => 'nullable|numeric|min:0',
            'total_allowances' => 'nullable|numeric|min:0',
            'total_deductions' => 'nullable|numeric|min:0',
            'gross' => 'nullable|numeric|min:0',
            'net' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,confirmed,paid',
            'meta' => 'nullable|array',
        ]);

        $targetUser = User::whereKey($validated['user_id'])
            ->where('clinic_id', $user->clinic_id)
            ->first();

        if (! $targetUser) {
            return response()->json(['message' => 'Invalid user for this clinic'], 422);
        }

        if (! empty($validated['payroll_run_id'])) {
            $run = HrmPayrollRun::whereKey($validated['payroll_run_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $run) {
                return response()->json(['message' => 'Invalid payroll run for this clinic'], 422);
            }
        }

        // Prevent duplicate finalized payslips for the same employee and period
        $finalizingStatus = $validated['status'] ?? 'draft';
        if (in_array($finalizingStatus, ['confirmed', 'paid'], true)) {
            $exists = HrmPayslip::query()
                ->where('clinic_id', $user->clinic_id)
                ->where('user_id', $validated['user_id'])
                ->whereDate('period_start', $validated['period_start'])
                ->whereDate('period_end', $validated['period_end'])
                ->whereIn('status', ['confirmed', 'paid'])
                ->exists();
            if ($exists) {
                return response()->json(['message' => 'A finalized payslip already exists for this employee and period'], 422);
            }
        }

        $payslip = HrmPayslip::create([
            'clinic_id' => $user->clinic_id,
            'payroll_run_id' => $validated['payroll_run_id'] ?? null,
            'user_id' => $validated['user_id'],
            'period_start' => $validated['period_start'],
            'period_end' => $validated['period_end'],
            'basic' => $validated['basic'] ?? 0,
            'total_allowances' => $validated['total_allowances'] ?? 0,
            'total_deductions' => $validated['total_deductions'] ?? 0,
            'gross' => $validated['gross'] ?? 0,
            'net' => $validated['net'] ?? 0,
            'status' => $validated['status'] ?? 'draft',
            'meta' => $validated['meta'] ?? null,
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $payslip->fresh(['user', 'run']),
        ], 201);
    }

    public function update(Request $request, HrmPayslip $payslip)
    {
        $user = $request->user();

        if ($payslip->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $validated = $request->validate([
            'payroll_run_id' => 'nullable|exists:hrm_payroll_runs,id',
            'period_start' => 'nullable|date',
            'period_end' => 'nullable|date',
            'basic' => 'nullable|numeric|min:0',
            'total_allowances' => 'nullable|numeric|min:0',
            'total_deductions' => 'nullable|numeric|min:0',
            'gross' => 'nullable|numeric|min:0',
            'net' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:draft,confirmed,paid',
            'meta' => 'nullable|array',
        ]);

        if (isset($validated['period_start']) && isset($validated['period_end'])) {
            if ($validated['period_end'] < $validated['period_start']) {
                return response()->json(['message' => 'Invalid period range'], 422);
            }
        }

        if (array_key_exists('payroll_run_id', $validated) && ! empty($validated['payroll_run_id'])) {
            $run = HrmPayrollRun::whereKey($validated['payroll_run_id'])
                ->where('clinic_id', $user->clinic_id)
                ->first();

            if (! $run) {
                return response()->json(['message' => 'Invalid payroll run for this clinic'], 422);
            }
        }

        $updateData = $validated;

        if (array_key_exists('period_start', $validated) && $validated['period_start'] === null) {
            unset($updateData['period_start']);
        }

        if (array_key_exists('period_end', $validated) && $validated['period_end'] === null) {
            unset($updateData['period_end']);
        }

        // Prevent duplicate finalized payslips when setting status to confirmed/paid
        if (isset($updateData['status']) && in_array($updateData['status'], ['confirmed', 'paid'], true)) {
            $targetPeriodStart = $updateData['period_start'] ?? $payslip->period_start;
            $targetPeriodEnd = $updateData['period_end'] ?? $payslip->period_end;
            $exists = HrmPayslip::query()
                ->where('clinic_id', $user->clinic_id)
                ->where('user_id', $payslip->user_id)
                ->whereDate('period_start', $targetPeriodStart)
                ->whereDate('period_end', $targetPeriodEnd)
                ->whereIn('status', ['confirmed', 'paid'])
                ->where('id', '!=', $payslip->id)
                ->exists();
            if ($exists) {
                return response()->json(['message' => 'A finalized payslip already exists for this employee and period'], 422);
            }
        }

        $payslip->update($updateData);

        return response()->json([
            'status' => 'success',
            'data' => $payslip->fresh(['user', 'run']),
        ]);
    }

    public function destroy(Request $request, HrmPayslip $payslip)
    {
        $user = $request->user();

        if ($payslip->clinic_id !== $user->clinic_id) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (! $user->can('view_reports') && ! $user->can('view_financial_reports')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        if ($payslip->status === 'paid') {
            return response()->json(['message' => 'Paid payslips cannot be deleted'], 422);
        }

        $payslip->delete();

        return response()->json([
            'status' => 'success',
        ]);
    }
}
