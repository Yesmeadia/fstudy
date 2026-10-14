@extends('emails.layout', ['subject' => '[Security] Reset Your Account Password — YES INDIA FOUNDATION'])

@section('content')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- Title & Greeting -->
        <tr>
            <td style="padding-bottom: 20px;">
                <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
                    Password Reset Request
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
                    You are receiving this notification because we received an official password reset request for your account on the <strong>YES INDIA FOUNDATION School Feasibility Study & Takeover Portal</strong>.
                </p>
                <p style="margin: 0;">
                    Click the secure button below to choose a new password for your account:
                </p>
            </td>
        </tr>

        <!-- Reset Password Button -->
        <tr>
            <td align="center" style="padding: 6px 0 28px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="border-radius: 12px; background: #4f46e5;">
                            <a href="{{ $actionUrl }}" target="_blank" class="btn-primary" style="background: #4f46e5; border: 1px solid #4338ca; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; display: inline-block; font-weight: 700; letter-spacing: 0.02em;">
                                Reset Account Password &rarr;
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Expiration Advisory -->
        <tr>
            <td style="padding-bottom: 20px;">
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 18px; font-size: 12px; line-height: 1.55; color: #475569;">
                    <p style="margin: 0 0 6px 0;">
                        <strong>Validity Notice:</strong> This password reset link is single-use and will expire in <strong>{{ $count }} minutes</strong>.
                    </p>
                    <p style="margin: 0; color: #64748b; font-size: 11px;">
                        If you did not initiate this request, no further action is required. Your current password remains active and your account is secure.
                    </p>
                </div>
            </td>
        </tr>

        <!-- Fallback Plaintext URL -->
        <tr>
            <td style="border-top: 1px solid #e2e8f0; padding-top: 18px;">
                <p style="margin: 0 0 6px 0; font-size: 11px; color: #64748b; line-height: 1.5;">
                    If you are having trouble clicking the "Reset Account Password" button, copy and paste the following URL into your browser:
                </p>
                <p style="margin: 0; font-size: 11px; word-break: break-all; color: #4f46e5; font-family: monospace;">
                    <a href="{{ $actionUrl }}" style="color: #4f46e5; text-decoration: underline;">{{ $actionUrl }}</a>
                </p>
            </td>
        </tr>
    </table>
@endsection
