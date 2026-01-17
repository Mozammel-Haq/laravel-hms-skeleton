<?php

namespace App\Http\Controllers\Extras;

use App\Http\Controllers\Controller;
use App\Models\DoctorAward;
use App\Models\DoctorCertification;
use App\Models\DoctorEducation;
use Illuminate\Http\Request;

class DoctorProfileController extends Controller
{
    protected function currentDoctor()
    {
        $user = auth()->user();

        if (!$user || !$user->hasRole('Doctor') || !$user->doctor) {
            abort(403);
        }

        return $user->doctor;
    }

    public function index()
    {
        $doctor = $this->currentDoctor()->load(['educations', 'awards', 'certifications']);

        return view('doctor.profile.index', compact('doctor'));
    }

    public function storeEducation(Request $request)
    {
        $doctor = $this->currentDoctor();

        $data = $request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        $doctor->educations()->create($data);

        return back()->with('success', 'Education added successfully.');
    }

    public function updateEducation(Request $request, DoctorEducation $education)
    {
        $doctor = $this->currentDoctor();

        if ($education->doctor_id !== $doctor->id) {
            abort(403);
        }

        $data = $request->validate([
            'degree' => 'required|string|max:255',
            'institution' => 'required|string|max:255',
            'country' => 'nullable|string|max:255',
            'start_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'end_year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
        ]);

        $education->update($data);

        return back()->with('success', 'Education updated successfully.');
    }

    public function destroyEducation(DoctorEducation $education)
    {
        $doctor = $this->currentDoctor();

        if ($education->doctor_id !== $doctor->id) {
            abort(403);
        }

        $education->delete();

        return back()->with('success', 'Education removed successfully.');
    }

    public function storeAward(Request $request)
    {
        $doctor = $this->currentDoctor();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string',
        ]);

        $doctor->awards()->create($data);

        return back()->with('success', 'Award added successfully.');
    }

    public function updateAward(Request $request, DoctorAward $award)
    {
        $doctor = $this->currentDoctor();

        if ($award->doctor_id !== $doctor->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'description' => 'nullable|string',
        ]);

        $award->update($data);

        return back()->with('success', 'Award updated successfully.');
    }

    public function destroyAward(DoctorAward $award)
    {
        $doctor = $this->currentDoctor();

        if ($award->doctor_id !== $doctor->id) {
            abort(403);
        }

        $award->delete();

        return back()->with('success', 'Award removed successfully.');
    }

    public function storeCertification(Request $request)
    {
        $doctor = $this->currentDoctor();

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'issued_by' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issued_date',
        ]);

        $doctor->certifications()->create($data);

        return back()->with('success', 'Certification added successfully.');
    }

    public function updateCertification(Request $request, DoctorCertification $certification)
    {
        $doctor = $this->currentDoctor();

        if ($certification->doctor_id !== $doctor->id) {
            abort(403);
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'issued_by' => 'nullable|string|max:255',
            'issued_date' => 'nullable|date',
            'expiry_date' => 'nullable|date|after_or_equal:issued_date',
        ]);

        $certification->update($data);

        return back()->with('success', 'Certification updated successfully.');
    }

    public function destroyCertification(DoctorCertification $certification)
    {
        $doctor = $this->currentDoctor();

        if ($certification->doctor_id !== $doctor->id) {
            abort(403);
        }

        $certification->delete();

        return back()->with('success', 'Certification removed successfully.');
    }
}

