<?php

namespace App\Services;

use App\Models\Admission;
use App\Models\Bed;
use App\Models\BedAssignment;
use App\Models\Patient;
use Illuminate\Support\Facades\DB;
use Exception;

class IpdService
{
    /**
     * Admit a patient.
     *
     * @param Patient $patient
     * @param int $doctorId
     * @param string $admissionDate
     * @param string|null $notes
     * @return Admission
     */
    public function admitPatient(Patient $patient, int $doctorId, string $admissionDate, ?string $notes = null)
    {
        return Admission::create([
            'clinic_id' => $patient->clinic_id,
            'patient_id' => $patient->id,
            'doctor_id' => $doctorId,
            'admission_date' => $admissionDate,
            'status' => 'admitted',
            'discharge_date' => null,
            'notes' => $notes,
        ]);
    }

    /**
     * Assign a bed to an admission.
     *
     * @param Admission $admission
     * @param int $bedId
     * @return BedAssignment
     * @throws Exception
     */
    public function assignBed(Admission $admission, int $bedId)
    {
        return DB::transaction(function () use ($admission, $bedId) {
            $bed = Bed::lockForUpdate()->find($bedId);

            if (!$bed) {
                throw new Exception("Bed not found.");
            }

            if ($bed->status !== 'available') {
                throw new Exception("Bed is not available.");
            }

            // End current active assignment if any
            $currentAssignment = $admission->bedAssignments()
                ->whereNull('end_date')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update(['end_date' => now()]);
                // Free up the old bed
                $oldBed = Bed::find($currentAssignment->bed_id);
                $oldBed->update(['status' => 'available']);
            }

            $assignment = BedAssignment::create([
                'admission_id' => $admission->id,
                'bed_id' => $bed->id,
                'start_date' => now(),
                'status' => 'active',
            ]);

            $bed->update(['status' => 'occupied']);

            return $assignment;
        });
    }

    /**
     * Discharge a patient.
     *
     * @param Admission $admission
     * @param string $dischargeDate
     * @return Admission
     */
    public function dischargePatient(Admission $admission, string $dischargeDate)
    {
        return DB::transaction(function () use ($admission, $dischargeDate) {
            $admission->update([
                'status' => 'discharged',
                'discharge_date' => $dischargeDate,
            ]);

            // Release bed
            $currentAssignment = $admission->bedAssignments()
                ->whereNull('end_date')
                ->first();

            if ($currentAssignment) {
                $currentAssignment->update(['end_date' => $dischargeDate]);
                $bed = Bed::find($currentAssignment->bed_id);
                $bed->update(['status' => 'available']);
            }

            return $admission;
        });
    }
}
