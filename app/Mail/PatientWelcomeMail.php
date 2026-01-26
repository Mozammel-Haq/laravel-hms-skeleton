<?php

namespace App\Mail;

use App\Models\Patient;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PatientWelcomeMail extends Mailable
{
    use Queueable, SerializesModels;

    public Patient $patient;
    public string $temporaryPassword;

    public function __construct(Patient $patient, string $temporaryPassword)
    {
        $this->patient = $patient;
        $this->temporaryPassword = $temporaryPassword;
    }

    public function build()
    {
        return $this->subject('Welcome to the Hospital Portal')
            ->view('emails.patient_welcome')
            ->with([
                'patient' => $this->patient,
                'temporaryPassword' => $this->temporaryPassword,
            ]);
    }
}
