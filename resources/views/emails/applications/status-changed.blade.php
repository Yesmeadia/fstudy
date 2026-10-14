@extends('emails.layout', ['subject' => 'Application Status Update — YES INDIA FOUNDATION'])

@section('content')
    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
        <!-- Title & Greeting -->
        <tr>
            <td style="padding-bottom: 20px;">
                <h1 style="margin: 0 0 6px 0; font-size: 20px; font-weight: 800; color: #0f172a; letter-spacing: -0.02em;">
                    Application Status Update
                </h1>
                <p style="margin: 0; font-size: 14px; font-weight: 600; color: #4338ca;">
                    Dear {{ $user->name ?? 'Applicant / Reviewer' }},
                </p>
            </td>
        </tr>

        <!-- Main Body -->
        <tr>
            <td style="font-size: 13px; line-height: 1.65; color: #334155; padding-bottom: 20px;">
                <p style="margin: 0 0 14px 0;">
                    The Feasibility Study Application for <strong>{{ $application->school->name }}</strong> has been officially updated to a new evaluation stage.
                </p>
            </td>
        </tr>

        <!-- Application Summary Card -->
        <tr>
            <td style="padding-bottom: 24px;">
                <div style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 14px; padding: 18px 22px;">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: 4px;">
                                Application Reference
                            </td>
                            <td style="font-size: 11px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; padding-bottom: 4px; text-align: right;">
                                Updated Status
                            </td>
                        </tr>
                        <tr>
                            <td style="font-size: 14px; font-weight: 800; color: #0f172a; font-family: monospace;">
                                {{ $application->application_number }}
                            </td>
                            <td style="text-align: right;">
                                <span style="display: inline-block; background-color: #dbeafe; color: #1e40af; font-size: 11px; font-weight: 700; padding: 4px 12px; border-radius: 9999px;">
                                    {{ $newStatus->label() }}
                                </span>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="border-top: 1px solid #e2e8f0; margin-top: 10px; padding-top: 10px; font-size: 11px; color: #64748b;">
                                <strong>Previous Stage:</strong> {{ $previousStatus->label() }}
                            </td>
                        </tr>
                    </table>
                </div>
            </td>
        </tr>

        <!-- Official Remarks / Feedback (If present) -->
        @if (!empty($remarks))
            <tr>
                <td style="padding-bottom: 24px;">
                    <div style="background-color: #f1f5f9; border-left: 3px solid #6366f1; border-radius: 0 12px 12px 0; padding: 14px 18px;">
                        <span style="display: block; font-size: 10px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 0.05em; margin-bottom: 4px;">
                            Official Reviewer Remarks:
                        </span>
                        <p style="margin: 0; font-size: 12px; font-style: italic; color: #1e293b; line-height: 1.5;">
                            &ldquo;{{ $remarks }}&rdquo;
                        </p>
                    </div>
                </td>
            </tr>
        @endif

        <!-- View Application CTA Button -->
        <tr>
            <td align="center" style="padding: 6px 0 24px 0;">
                <table role="presentation" border="0" cellpadding="0" cellspacing="0">
                    <tr>
                        <td align="center" style="border-radius: 12px; background: #4f46e5;">
                            <a href="{{ route('applications.show', $application) }}" target="_blank" class="btn-primary" style="background: #4f46e5; border: 1px solid #4338ca; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; font-size: 13px; color: #ffffff; text-decoration: none; padding: 14px 32px; border-radius: 12px; display: inline-block; font-weight: 700; letter-spacing: 0.02em;">
                                View Application in Portal &rarr;
                            </a>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <!-- Explanatory note -->
        <tr>
            <td style="font-size: 11px; line-height: 1.5; color: #64748b; text-align: center;">
                You can monitor real-time review progress, inspection observations, and uploaded evidence anytime through your portal dashboard.
            </td>
        </tr>
    </table>
@endsection
