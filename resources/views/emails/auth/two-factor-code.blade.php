@extends('emails.layout', ['subject' => 'Your Two-Factor Authentication Code — YES INDIA FOUNDATION'])

@section('content')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- Title & Greeting -->
        <tr>
            <td style="padding-bottom: 20px;">
                <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
                    Security Verification Code
                </h1>
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #4338ca;">
                    Hello {{ $user->name ?? 'Portal User' }},
                </p>
            </td>
        </tr>

        <!-- Main Body -->
        <tr>
            <td style="font-size: 13px; line-height: 1.65; color: #334155; padding-bottom: 24px;">
                <p style="margin: 0 0 14px 0;">
                    You recently attempted to sign in to the <strong>YES INDIA FOUNDATION School Feasibility Portal</strong> with Two-Factor Authentication enabled.
                </p>
                <p style="margin: 0;">
                    Use the following single-use verification code to complete your secure sign-in:
                </p>
            </td>
        </tr>

        <!-- OTP Display Box -->
        <tr>
            <td align="center" style="padding: 6px 0 28px 0;">
                <div style="background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 16px; padding: 22px 32px; display: inline-block; text-align: center;">
                    <span style="font-family: 'Courier New', Courier, monospace, monospace; font-size: 34px; font-weight: 900; letter-spacing: 0.28em; color: #1e1b4b; display: block;">
                        {{ $code }}
                    </span>
                    <span style="display: block; margin-top: 8px; font-size: 11px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em;">
                        Expires in 10 minutes &bull; Do not share
                    </span>
                </div>
            </td>
        </tr>

        <!-- Security Advisory Box -->
        <tr>
            <td style="padding-bottom: 12px;">
                <div style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px; padding: 14px 16px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td width="24" style="vertical-align: top; padding-right: 10px;">
                                <span style="font-size: 16px; color: #15803d;">&#10004;</span>
                            </td>
                            <td style="font-size: 11px; line-height: 1.5; color: #166534;">
                                <strong>Account Protection Active:</strong> If you did not initiate this sign-in attempt, please reset your portal password immediately to prevent unauthorized access.
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>
    </table>
@endsection
