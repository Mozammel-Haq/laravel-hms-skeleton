<p>Dear {{ $patient->name }},</p>

<p>Welcome to {{ optional($patient->clinic)->name ?? 'our hospital' }}. Your patient account has been created.</p>

<p>Use the following temporary password to log in to the patient portal:</p>
<p><strong>Password:</strong> {{ $temporaryPassword }}</p>

<p>For security, please change this password after your first login.</p>

<p>Regards,<br/>Hospital Team</p>
