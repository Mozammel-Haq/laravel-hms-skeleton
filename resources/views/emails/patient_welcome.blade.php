<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to {{ optional($patient->clinic)->name ?? 'CityCare Hospital' }}</title>
</head>

<body style="margin:0; padding:0; background-color:#f2f4f7;">
    <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
        style="background-color:#f2f4f7; padding:40px 16px;">
        <tr>
            <td align="center">

                <!-- Main Card -->
                <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                    style="max-width:600px; background-color:#ffffff; border-radius:8px; overflow:hidden; box-shadow:0 6px 20px rgba(0,0,0,0.06);">

                    <!-- Header -->
                    <tr>
                        <td style="background:#0b5ed7; padding:36px 40px;">
                            <h1
                                style="margin:0; color:#ffffff; font-size:22px; font-weight:600; letter-spacing:-0.2px;">
                                {{ optional($patient->clinic)->name ?? 'CityCare Hospital' }}
                            </h1>
                            <p style="margin:8px 0 0; color:#dbe7ff; font-size:14px;">
                                Patient Portal Access Information
                            </p>
                        </td>
                    </tr>

                    <!-- Body -->
                    <tr>
                        <td
                            style="padding:40px 40px 32px; font-family:-apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Arial, sans-serif;">

                            <p style="margin:0 0 20px; font-size:16px; color:#212529;">
                                Dear <strong>{{ $patient->name }}</strong>,
                            </p>

                            <p style="margin:0 0 24px; font-size:15px; line-height:1.7; color:#495057;">
                                We are pleased to welcome you to
                                <strong>{{ optional($patient->clinic)->name ?? 'our healthcare facility' }}</strong>.
                                Your patient profile has been successfully created in our system.
                            </p>

                            <p style="margin:0 0 12px; font-size:15px; color:#495057;">
                                Below are your temporary login credentials for the patient portal:
                            </p>

                            <!-- Credentials Box -->
                            <table width="100%" cellpadding="0" cellspacing="0" role="presentation"
                                style="background:#f8f9fa; border:1px solid #dee2e6; border-radius:6px; margin:16px 0 28px;">
                                <tr>
                                    <td style="padding:20px 24px;">
                                        <p
                                            style="margin:0 0 6px; font-size:12px; color:#6c757d; text-transform:uppercase; letter-spacing:0.6px;">
                                            Temporary Password
                                        </p>
                                        <p
                                            style="margin:0; font-size:20px; font-weight:700; color:#212529; font-family:'Courier New', monospace;">
                                            {{ $temporaryPassword }}
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:0 0 28px; font-size:14.5px; line-height:1.7; color:#495057;">
                                For your security, we strongly recommend changing this password immediately after your
                                first login.
                            </p>

                            <!-- CTA -->
                            <table cellpadding="0" cellspacing="0" role="presentation" align="center">
                                <tr>
                                    <td>
                                        <a href="http://mozammel.intelsofts.com/citycare/portal/login"
                                            style="display:inline-block; background:#0b5ed7; color:#ffffff; text-decoration:none; padding:14px 36px; border-radius:4px; font-size:15px; font-weight:600;">
                                            Log in to Patient Portal
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <p style="margin:36px 0 0; font-size:15px; color:#212529;">
                                Sincerely,<br>
                                <strong>{{ optional($patient->clinic)->name ?? 'Hospital Administration' }}</strong>
                            </p>

                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td
                            style="background:#f8f9fa; border-top:1px solid #e9ecef; padding:24px 40px; text-align:center;">
                            <p style="margin:0 0 6px; font-size:13px; color:#6c757d;">
                                © {{ date('Y') }} {{ optional($patient->clinic)->name ?? 'Our Hospital' }}
                            </p>
                            <p style="margin:0; font-size:12px; color:#adb5bd;">
                                This is a system-generated message. Please do not reply.
                            </p>
                        </td>
                    </tr>

                </table>

            </td>
        </tr>
    </table>
</body>

</html>
