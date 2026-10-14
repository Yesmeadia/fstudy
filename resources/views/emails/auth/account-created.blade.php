@extends('emails.layout', ['subject' => 'Your Account Has Been Created — YES INDIA FOUNDATION'])

@section('content')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- Title & Greeting -->
        <tr>
            <td style="padding-bottom: 20px;">
                <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
                    Welcome to the Portal
                </h1>
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #4338ca;">
                    Hello {{ $user->name }},
                </p>
            </td>
        </tr>

        <!-- Main Body -->
        <tr>
            <td style="font-size: 13px; line-height: 1.65; color: #334155; padding-bottom: 20px;">
                <p style="margin: 0 0 14px 0;">
                    Your user account has been successfully provisioned by the Administrator on the <strong>YES INDIA FOUNDATION School Feasibility Study & Takeover Portal</strong>.
                </p>
                <p style="margin: 0;">
                    You have been assigned access to participate in institutional inspections, feasibility assessments, and verification workflows.
                </p>
            </td>
        </tr>

        <!-- Credentials Card -->
        <tr>
            <td style="padding-bottom: 24px;">
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 22px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: 6px;">
                                Authorized Email / Username
                            </td>
                            <td style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: 6px; text-align: right;">
                                Assigned System Role
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 13px; font-weight: 700; color: #0f172a; font-family: monospace;">
                                {{ $user->email }}
                            </td>
                            <td style="text-align: right;">
                                <span style="display: inline-block; background-color: #e0e7ff; color: #3730a3; font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 9999px;">
                                    {{ $roleName ?? 'Inspector' }}
                                </span>
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

        <!-- Sign In Button -->
        <tr>
            <td align="center" style="padding: 6px 0 24px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="border-radius: 12px; background: #4f46e5;">
                            <a href="{{ route('login') }}" target="_blank" class="btn-primary" style="background: #4f46e5; border: 1px solid #4338ca; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; display: inline-block; font-weight: 700; letter-spacing: 0.02em;">
                                Sign In to Inspection Portal &rarr;
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Security Best Practices Notice -->
        <tr>
            <td>
                <div style="background-color: #f1f5f9; border-left: 3px solid #6366f1; border-radius: 0 10px 10px 0; padding: 12px 16px; font-size: 11px; line-height: 1.5; color: #475569;">
                    <strong>Next Steps:</strong> Please sign in using the password provided securely by your Super Administrator. We recommend navigating to your profile settings to enable <strong>Two-Factor Authentication (2FA)</strong> for enhanced account protection.
                </div>
            </td>
        </tr>
    </table>
@endsection
