<?php

use App\Models\Appointment;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Schedule::call(function () {
    $today = now()->toDateString();
    $nowTime = now()->format('H:i:s');

    Appointment::query()
        ->where('appointment_type', 'online')
        ->whereIn('status', ['confirmed', 'arrived'])
        ->whereNotNull('end_time')
        ->where(function ($q) use ($today, $nowTime) {
            $q->whereDate('appointment_date', '<', $today)
                ->orWhere(function ($q2) use ($today, $nowTime) {
                    $q2->whereDate('appointment_date', $today)
                        ->whereTime('end_time', '<=', $nowTime);
                });
        })
        ->whereExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('invoices')
                ->whereColumn('invoices.appointment_id', 'appointments.id')
                ->whereNull('invoices.deleted_at')
                ->where('invoices.invoice_type', 'consultation')
                ->where('invoices.state', 'finalized')
                ->where('invoices.status', 'paid');
        })
        ->select('appointments.*')
        ->orderBy('appointments.id')
        ->chunkById(200, function ($appointments) {
            foreach ($appointments as $appointment) {
                $appointment->update(['status' => 'completed']);

                if ($appointment->visit) {
                    $appointment->visit->update(['visit_status' => 'completed']);
                }
            }
        });

    Appointment::query()
        ->where('appointment_type', 'online')
        ->whereIn('status', ['pending', 'confirmed', 'arrived'])
        ->whereNotNull('end_time')
        ->where(function ($q) use ($today, $nowTime) {
            $q->whereDate('appointment_date', '<', $today)
                ->orWhere(function ($q2) use ($today, $nowTime) {
                    $q2->whereDate('appointment_date', $today)
                        ->whereTime('end_time', '<=', $nowTime);
                });
        })
        ->whereExists(function ($q) {
            $q->select(DB::raw(1))
                ->from('invoices')
                ->whereColumn('invoices.appointment_id', 'appointments.id')
                ->whereNull('invoices.deleted_at')
                ->where('invoices.invoice_type', 'consultation')
                ->where('invoices.state', 'finalized')
                ->where('invoices.status', 'unpaid');
        })
        ->select('appointments.*')
        ->orderBy('appointments.id')
        ->chunkById(200, function ($appointments) {
            foreach ($appointments as $appointment) {
                $appointment->update(['status' => 'cancelled']);

                DB::table('invoices')
                    ->whereNull('deleted_at')
                    ->where('appointment_id', $appointment->id)
                    ->where('invoice_type', 'consultation')
                    ->where('state', 'finalized')
                    ->where('status', 'unpaid')
                    ->update([
                        'status' => 'cancelled',
                        'updated_at' => now(),
                    ]);

                if ($appointment->visit) {
                    $appointment->visit->update(['visit_status' => 'cancelled']);
                }
            }
        });
})->everyFiveMinutes()->name('appointments:auto-complete-online');

Schedule::call(function () {
    $nowDT = now()->toDateTimeString();

    Appointment::query()
        ->whereIn('status', ['pending', 'confirmed'])
        ->where(function ($q) use ($nowDT) {
            $q->where(function ($q1) use ($nowDT) {
                $q1->whereNotNull('end_time')
                   ->whereRaw('TIMESTAMP(appointment_date, end_time) < ?', [$nowDT]);
            })->orWhere(function ($q2) use ($nowDT) {
                $q2->whereNull('end_time')
                   ->whereNotNull('start_time')
                   ->whereRaw('TIMESTAMP(appointment_date, start_time) < ?', [$nowDT]);
            });
        })
        ->select('appointments.*')
        ->orderBy('appointments.id')
        ->chunkById(200, function ($appointments) {
            foreach ($appointments as $appointment) {
                $appointment->update(['status' => 'noshow']);
            }
        });
})->everyFiveMinutes()->name('appointments:auto-mark-noshow');
