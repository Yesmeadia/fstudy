<!DOCTYPE html>
<html lang="en" xmlns="http://www.w3.org/1999/xhtml" xmlns:v="urn:schemas-microsoft-com:vml" xmlns:o="urn:schemas-microsoft-com:office:office">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="x-apple-disable-message-reformatting">
    <title>{{ $subject ?? config('app.name', 'YES INDIA FOUNDATION') }}</title>
    <!--[if mso]>
    <style>
        * { font-family: sans-serif !important; }
    </style>
    <![endif]-->
    <style>
        html, body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
            background-color: #f1f5f9;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            color: #1e293b;
            -webkit-font-smoothing: antialiased;
        }
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }
        table, td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }
        img {
            -ms-interpolation-mode: bicubic;
            border: 0;
            outline: none;
            text-decoration: none;
            display: block;
        }
        a {
            text-decoration: none;
            color: #4f46e5;
        }
        .btn-primary {
            display: inline-block;
            background: linear-gradient(135deg, #4f46e5 0%, #4338ca 100%);
            background-color: #4f46e5;
            color: #ffffff !important;
            font-size: 13px;
            font-weight: 700;
            line-height: 1;
            padding: 14px 28px;
            text-align: center;
            border-radius: 12px;
            text-decoration: none;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }
        .btn-primary:hover {
            background-color: #4338ca !important;
        }
        @media screen and (max-width: 600px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
                border-radius: 0 !important;
            }
            .content-padding {
                padding: 24px 20px !important;
            }
            .header-padding {
                padding: 24px 20px !important;
            }
        }
    </style>
</head>
<body width="100%" style="margin: 0; padding: 0 !important; mso-line-height-rule: exactly; background-color: #f1f5f9;">
    <center style="width: 100%; background-color: #f1f5f9; padding: 32px 0;">
        <!-- Container Wrapper -->
        <table align="center" role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" class="email-container" style="max-width: 600px; width: 100%; margin: 0 auto; background-color: #ffffff; border-radius: 20px; overflow: hidden; border: 1px solid #e2e8f0; box-shadow: 0 10px 25px -5px rgba(0, 0, 0, 0.05), 0 8px 10px -6px rgba(0, 0, 0, 0.01);">
            
            <!-- Email Header -->
            <tr>
                <td style="background-color: #0f172a; padding: 28px 36px;" class="header-padding">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td align="left">
                                <div style="display: inline-block; vertical-align: middle;">
                                    <span style="font-size: 15px; font-weight: 900; letter-spacing: 0.08em; color: #ffffff; text-transform: uppercase; display: block; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;">
                                        YES INDIA FOUNDATION
                                    </span>
                                    <span style="font-size: 11px; font-weight: 600; color: #94a3b8; letter-spacing: 0.04em; text-transform: uppercase; display: block; margin-top: 2px;">
                                        School Feasibility & Takeover Portal
                                    </span>
                                </div>
                            </td>
                            <td align="right" style="vertical-align: middle;">
                                <span style="display: inline-block; background-color: rgba(99, 102, 241, 0.15); border: 1px solid rgba(99, 102, 241, 0.35); color: #818cf8; font-size: 10px; font-weight: 700; text-transform: uppercase; letter-spacing: 0.08em; padding: 4px 10px; border-radius: 9999px;">
                                    Official Notice
                                </span>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

            <!-- Email Body Content -->
            <tr>
                <td style="padding: 36px;" class="content-padding">
                    @yield('content')
                </td>
            </tr>

            <!-- Institutional Footer -->
            <tr>
                <td style="background-color: #f8fafc; border-top: 1px solid #e2e8f0; padding: 24px 36px;" class="content-padding">
                    <table role="presentation" border="0" cellpadding="0" cellspacing="0" width="100%">
                        <tr>
                            <td style="font-size: 11px; line-height: 1.6; color: #64748b; text-align: center;">
                                <p style="margin: 0 0 6px 0; font-weight: 600; color: #475569;">
                                    &copy; {{ date('Y') }} YES INDIA FOUNDATION. All Rights Reserved.
                                </p>
                                <p style="margin: 0 0 6px 0;">
                                    This is an official automated communication from the School Feasibility Assessment & Evaluation Directorate (no-reply). Please do not reply directly to this email.
                                </p>
                                <p style="margin: 0; font-size: 10px; color: #94a3b8;">
                                    If you require assistance or have inquiries, please contact our administrative desk at
                                    <a href="mailto:info@yesindiafoundation.com" style="color: #6366f1; text-decoration: underline;">info@yesindiafoundation.com</a>.
                                </p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>

        </table>
    </center>
</body>
</html>
