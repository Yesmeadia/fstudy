<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>FEASIBILITY CHECKLIST — {{ $application->application_number }}</title>
    <style>
        @page {
            margin: 10mm 10mm 12mm 10mm;
            size: a4 portrait;
        }

        body {
            font-family: 'DejaVu Sans', 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #111827;
            font-size: 7.5pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-after: always;
            break-after: page;
        }

        .text-center {
            text-align: center;
        }

        .font-bold {
            font-weight: bold;
        }

        /* Header Styling */
        .logo-box {
            text-align: center;
            margin-bottom: 4px;
        }

        .logo-img {
            height: 40px;
            width: auto;
        }

        .main-title {
            font-size: 11.5pt;
            font-weight: 800;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
            color: #0f172a;
        }

        .format-tag {
            font-size: 7.5pt;
            font-style: italic;
            color: #475569;
            margin-bottom: 6px;
        }

        /* Tables & Section Spacing */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 16px;
        }

        thead {
            display: table-header-group;
        }

        tfoot {
            display: table-footer-group;
        }

        tr {
            page-break-inside: avoid;
            break-inside: avoid;
        }

        .meta-table {
            margin-bottom: 22px;
        }

        .meta-table td {
            border: 1px solid #1e293b;
            padding: 5.5px 8px;
            font-size: 8pt;
        }

        .meta-label {
            font-weight: bold;
            width: 38%;
            background-color: #f8fafc;
        }

        .section-heading {
            font-size: 8.5pt;
            font-weight: 800;
            color: #1e40af;
            margin-top: 14px;
            margin-bottom: 5px;
            text-transform: uppercase;
            page-break-after: avoid;
            break-after: avoid;
        }

        .section-heading-first {
            margin-top: 18px;
        }

        .checklist-table th {
            background-color: #0f172a;
            color: #ffffff;
            font-weight: bold;
            font-size: 7pt;
            padding: 3px 5px;
            border: 1px solid #0f172a;
            text-transform: uppercase;
        }

        .checklist-table td {
            border: 1px solid #334155;
            padding: 3px 5px;
            font-size: 7pt;
            vertical-align: middle;
        }

        .checklist-table tr:nth-child(even) {
            background-color: #fafafa;
        }

        .points-col {
            width: 42%;
        }

        .remarks-col {
            width: 42%;
        }

        .check-col {
            text-align: center;
            width: 26px;
            font-weight: bold;
        }

        .no-col {
            text-align: center;
            width: 22px;
            font-weight: bold;
        }

        .signature-block {
            page-break-inside: avoid;
            break-inside: avoid;
            margin-top: 6px;
        }

        /* Footer */
        .page-footer {
            position: fixed;
            bottom: -8mm;
            left: 0;
            right: 0;
            text-align: center;
            font-size: 6.5pt;
            color: #64748b;
            border-top: 0.5px solid #cbd5e1;
            padding-top: 2px;
        }
    </style>
</head>

<body>

    <div class="page-footer">
        YES India Foundation | Running School Takeover Feasibility Checklist | Confidential Working Document
    </div>

    <!-- HEADER & INSTITUTION DETAILS -->
    <div class="logo-box">
        @if (!empty($logoBase64))
            <img src="{{ $logoBase64 }}" class="logo-img" alt="YES INDIA FOUNDATION">
        @else
            <div style="font-weight:900; font-size:13pt; color:#1e3a8a;">YES INDIA FOUNDATION</div>
        @endif
    </div>

    <div class="text-center">
        <div class="main-title">RUNNING SCHOOL TAKEOVER / FEASIBILITY ASSESSMENT CHECKLIST</div>
        <div class="format-tag">Pre-Takeover Inspection, Feasibility & Transition Control Format</div>
    </div>

    <!-- Header Meta Table -->
    <table class="meta-table">
        <tr>
            <td class="meta-label">Proposed Institution / School</td>
            <td>{{ $checklist->institution_name ?? $school->name }}</td>
        </tr>
        <tr>
            <td class="meta-label">Location</td>
            <td>{{ $checklist->location ?? ($school->city . ', ' . $school->state) }}</td>
        </tr>
        <tr>
            <td class="meta-label">Present Owner / Management</td>
            <td>{{ $checklist->present_management ?? $school->management_organization }}</td>
        </tr>
        <tr>
            <td class="meta-label">Date(s) of feasibility inspection</td>
            <td>{{ $checklist->inspection_dates ?: '—' }}</td>
        </tr>
        <tr>
            <td class="meta-label">Team members of inspection</td>
            <td>{{ $checklist->inspection_team ?? ($application->assignedOfficer?->name ?? 'Inspector') }}</td>
        </tr>
    </table>

    <!-- Section 1 -->
    <div class="section-heading section-heading-first">1. LEGAL IDENTITY, OWNERSHIP & STATUTORY RECOGNITION</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section1_legal ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 2 -->
    <div class="section-heading">2. ROOMS; DIMENSIONS & CAPACITY</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th>TYPE (whether classroom / office size/ hall size)</th>
                <th style="width: 55px; text-align:center;">Number of Rooms</th>
                <th style="width: 60px;">SIZE (ft)</th>
                <th>CURRENT USE</th>
                <th>FURNITURE</th>
                <th>Remarks (ventilation, lighting, ...)</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($checklist->section3_rooms ?? [] as $room)
                <tr>
                    <td class="font-bold">{{ $room['type'] ?: '—' }}</td>
                    <td style="text-align: center;">{{ $room['count'] ?: '—' }}</td>
                    <td>{{ $room['size_feet'] ?: '—' }}</td>
                    <td>{{ $room['current_use'] ?: '—' }}</td>
                    <td>{{ $room['furniture'] ?: '—' }}</td>
                    <td>{{ $room['remarks'] ?: '—' }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center italic">No rooms recorded.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Section 3 -->
    <div class="section-heading">3. PHYSICAL INFRASTRUCTURE & CAMPUS CONDITION</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section2_infrastructure ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 4 -->
    <div class="section-heading">4. STUDENTS ENROLMENT STATEMENT</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th>CLASS & DIVISION</th>
                <th style="width: 48px; text-align:center;">BOYS</th>
                <th style="width: 48px; text-align:center;">GIRLS</th>
                <th style="width: 48px; text-align:center;">TOTAL</th>
                <th style="width: 65px; text-align:center;">LAST YEAR</th>
                <th style="width: 65px; text-align:center;">TREND</th>
            </tr>
        </thead>
        <tbody>
            @php
                $totB = 0;
                $totG = 0;
                $totT = 0;
                $totL = 0;
            @endphp
            @forelse ($checklist->section5_enrolment ?? [] as $row)
                @php
                    $b = (int) ($row['boys'] ?? 0);
                    $g = (int) ($row['girls'] ?? 0);
                    $t = (int) ($row['total'] ?? ($b + $g));
                    $l = (int) ($row['last_year_total'] ?? 0);
                    $totB += $b;
                    $totG += $g;
                    $totT += $t;
                    $totL += $l;

                    $rowTrend = $row['trend'] ?? '';
                    if (empty($rowTrend) && ($t > 0 || $l > 0)) {
                        if ($t > $l) {
                            $rowTrend = 'Increased';
                        } elseif ($t < $l) {
                            $rowTrend = 'Decreased';
                        } else {
                            $rowTrend = 'Stayed same';
                        }
                    }
                @endphp
                <tr>
                    <td class="font-bold">{{ $row['class_division'] ?: '—' }}</td>
                    <td style="text-align: center;">{{ $b ?: '—' }}</td>
                    <td style="text-align: center;">{{ $g ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold;">{{ $t ?: '—' }}</td>
                    <td style="text-align: center;">{{ $l ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; font-size: 6.5pt; color: {{ $rowTrend === 'Increased' ? '#166534' : ($rowTrend === 'Decreased' ? '#991b1b' : '#334155') }};">
                        {{ $rowTrend ?: '—' }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center italic">No enrolment statement recorded.</td>
                </tr>
            @endforelse
            @php
                $overallTrend = '—';
                if ($totT > 0 || $totL > 0) {
                    if ($totT > $totL) {
                        $overallTrend = 'Increased';
                    } elseif ($totT < $totL) {
                        $overallTrend = 'Decreased';
                    } else {
                        $overallTrend = 'Stayed same';
                    }
                }
            @endphp
            <tr style="background-color: #e2e8f0; font-weight: bold;">
                <td>TOTAL</td>
                <td style="text-align: center;">{{ $totB }}</td>
                <td style="text-align: center;">{{ $totG }}</td>
                <td style="text-align: center;">{{ $totT }}</td>
                <td style="text-align: center;">{{ $totL }}</td>
                <td style="text-align: center; font-size: 7pt; color: {{ $overallTrend === 'Increased' ? '#166534' : ($overallTrend === 'Decreased' ? '#991b1b' : '#334155') }};">
                    {{ $overallTrend }}
                </td>
            </tr>
        </tbody>
    </table>

    <!-- Section 5 -->
    <div class="section-heading">5. STUDENT POPULATION & ADMISSIONS</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section4_students ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 6: Curriculum -->
    <div class="section-heading">6. CURRICULUM</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section6_academic ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 7 -->
    <div class="section-heading">7. STAFF, HR & EMPLOYMENT LIABILITIES</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section7_staff ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 8: Financial Assessment -->
    <div class="section-heading">8. FINANCIAL ASSESSMENT – INCOME & EXPENDITURE</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section8_financial ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 9 -->
    <div class="section-heading">9. BRANDING</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th class="no-col">No.</th>
                <th class="points-col">Verification / Point to Check</th>
                <th class="check-col">Yes</th>
                <th class="check-col">No</th>
                <th class="remarks-col">Remarks / Evidence / Action Required</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($checklist->section9_brand ?? [] as $item)
                <tr>
                    <td class="no-col">{{ $item['no'] }}</td>
                    <td>{{ $item['point'] }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'yes' ? '✓' : '' }}</td>
                    <td class="check-col font-bold">{{ ($item['compliance'] ?? '') === 'no' ? '✗' : '' }}</td>
                    <td>{{ $item['remarks'] ?? '' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 10 -->
    <div class="section-heading">10. PRIMARY QUALITY</div>
    @php
        $sec10 = $checklist->section10_primary_assessment;
        $sec10Classes = $sec10['classes'] ?? ['Class 1', 'Class 2', 'Class 3', 'Class 4', 'Class 5'];
        $sec10Rows = $sec10['rows'] ?? [];
    @endphp
    <table class="checklist-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 170px;">PARTICULARS</th>
                <th colspan="5" style="text-align: center;">CLASS</th>
                <th rowspan="2" class="remarks-col">REMARKS / EVIDENCE / OBSERVATIONS</th>
            </tr>
            <tr style="background-color: #1e293b; color: #fff;">
                @for ($c = 0; $c < 5; $c++)
                    <th style="width: 34px; text-align: center; font-size: 7pt;">
                        {{ $sec10Classes[$c] ?? ('Class ' . ($c + 1)) }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($sec10Rows as $item)
                <tr>
                    <td class="font-bold">{{ $item['particular'] ?? '' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c1'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c2'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c3'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c4'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c5'] ?? '') ?: '—' }}</td>
                    <td>{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 11 -->
    <div class="section-heading">11. SECONDARY QUALITY</div>
    <table class="checklist-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 170px;">PARTICULARS</th>
                <th colspan="5" style="text-align: center;">CLASS</th>
                <th rowspan="2" class="remarks-col">REMARKS / EVIDENCE / OBSERVATIONS</th>
            </tr>
            <tr style="background-color: #1e293b; color: #fff;">
                <th style="width: 34px; text-align: center; font-size: 7pt;">Class 6</th>
                <th style="width: 34px; text-align: center; font-size: 7pt;">Class 7</th>
                <th style="width: 34px; text-align: center; font-size: 7pt;">Class 8</th>
                <th style="width: 34px; text-align: center; font-size: 7pt;">Class 9</th>
                <th style="width: 34px; text-align: center; font-size: 7pt;">Class 10</th>
            </tr>
        </thead>
        <tbody>
            @php
                $sec11 = $checklist->section11_secondary_assessment;
                $sec11Rows = isset($sec11['rows']) && is_array($sec11['rows']) ? $sec11['rows'] : (is_array($sec11) ? $sec11 : []);
            @endphp
            @foreach ($sec11Rows as $item)
                <tr>
                    <td class="font-bold">{{ $item['particular'] ?? '' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c1'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c2'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c3'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c4'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center; font-weight: bold; color: #4338ca;">{{ ($item['c5'] ?? '') ?: '—' }}</td>
                    <td>{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- Section 12 -->
    <div class="section-heading">12. TEACHERS’ ASSESSMENT</div>
    @php
        $sec12 = $checklist->section12_teachers_assessment;
        $sec12Teachers = $sec12['teachers'] ?? ['Teacher 1', 'Teacher 2', 'Teacher 3', 'Teacher 4', 'Teacher 5', 'Teacher 6', 'Teacher 7'];
        $sec12Rows = $sec12['rows'] ?? [];
    @endphp
    <table class="checklist-table">
        <thead>
            <tr>
                <th rowspan="2" style="width: 170px;">PARTICULARS</th>
                <th colspan="7" style="text-align: center;">TEACHER NAME</th>
                <th rowspan="2" class="remarks-col">REMARKS / EVIDENCE / OBSERVATIONS</th>
            </tr>
            <tr style="background-color: #1e293b; color: #fff;">
                @for ($t = 0; $t < 7; $t++)
                    <th style="width: 25px; text-align: center; font-size: 7pt;">
                        {{ $sec12Teachers[$t] ?? ('T' . ($t + 1)) }}</th>
                @endfor
            </tr>
        </thead>
        <tbody>
            @foreach ($sec12Rows as $item)
                <tr>
                    <td class="font-bold">{{ $item['particular'] ?? '' }}</td>
                    <td style="text-align: center;">{{ ($item['t1'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t2'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t3'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t4'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t5'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t6'] ?? '') ?: '—' }}</td>
                    <td style="text-align: center;">{{ ($item['t7'] ?? '') ?: '—' }}</td>
                    <td>{{ ($item['remarks'] ?? '') ?: '—' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <!-- SECTION 13: EVIDENCE PHOTOS -->
    @php
        $evidenceRecords = $checklist->getAllRemarksWithEvidence();
        $photosOnly = array_filter($evidenceRecords, fn($r) => $r['has_photo']);
    @endphp

    @if(count($photosOnly) > 0)
        <div class="section-heading">13. EVIDENCE PHOTOS / FIELD EVIDENCE & AUDIT OBSERVATIONS</div>
        <p style="font-size: 7pt; color: #475569; margin-bottom: 4px;">Supporting photographic evidence attached by inspection officers during on-site institutional audit.</p>

        <table style="width: 100%; border-collapse: collapse; margin-bottom: 12px;">
            @foreach(array_chunk($photosOnly, 2) as $rowPhotos)
                <tr style="page-break-inside: avoid;">
                    @foreach($rowPhotos as $photoItem)
                        @php
                            $fullPath = storage_path('app/public/' . $photoItem['evidence_photo']);
                            $hasImageFile = file_exists($fullPath);
                        @endphp
                        <td
                            style="width: 50%; vertical-align: top; padding: 4px; border: 1px solid #cbd5e1; background-color: #f8fafc;">
                            <div style="font-weight: bold; font-size: 7pt; color: #1e40af; text-transform: uppercase;">
                                {{ $photoItem['section_title'] }}
                            </div>
                            <div style="font-weight: 600; font-size: 7.5pt; color: #0f172a; margin-top: 1px; margin-bottom: 3px;">
                                {{ $photoItem['item_label'] }}
                            </div>

                            @if($hasImageFile)
                                <div style="text-align: center; margin: 3px 0;">
                                    <img src="{{ $fullPath }}"
                                        style="max-width: 100%; max-height: 120px; border: 1px solid #cbd5e1; border-radius: 4px;">
                                </div>
                            @endif

                            @if(!empty($photoItem['remarks']))
                                <div
                                    style="font-size: 6.5pt; color: #334155; font-style: italic; background-color: #ffffff; border: 1px solid #e2e8f0; padding: 3px 4px; margin-top: 2px; border-radius: 3px;">
                                    "{{ $photoItem['remarks'] }}"
                                </div>
                            @endif
                        </td>
                    @endforeach
                    @if(count($rowPhotos) < 2)
                        <td style="width: 50%; border: none;"></td>
                    @endif
                </tr>
            @endforeach
        </table>
    @endif

    <!-- Section 14: Final Assessment Remarks (Standalone Last Page) -->
    <div class="page-break"></div>
    <div class="section-heading" style="font-size: 10pt; margin-top: 10px; margin-bottom: 8px;">14. FINAL ASSESSMENT REMARKS</div>
    @php
        $final = $checklist->section13_final_remarks ?? [];
    @endphp
    <table style="border: 1px solid #1e293b; margin-bottom: 16px;">
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Academic:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['academic'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Financial:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['financial'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Infrastructural:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['infrastructural'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Legal:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['legal'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Administration Staff:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['administration_staff'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Teaching Staff:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['teaching_staff'] ?? '' }}</td>
        </tr>
        <tr>
            <td
                style="width: 30%; font-weight: bold; font-size: 8pt; border: 1px solid #1e293b; padding: 7px 10px; vertical-align: top; background-color: #f8fafc;">
                Safety:</td>
            <td style="border: 1px solid #1e293b; padding: 7px 10px; font-size: 8pt; line-height: 1.4;">
                {{ $final['safety'] ?? '' }}</td>
        </tr>
    </table>

    <div class="signature-block" style="font-weight: bold; font-size: 9pt; margin-top: 14px; color: #0f172a;">
        SIGNED BY;
        <div style="margin-top: 5px; font-size: 8.5pt; font-weight: normal;">
            <strong>Name / Designation:</strong>
            {{ ($final['signed_by'] ?? '') ?: ($checklist->inspector_name ?: ($application->assignedOfficer?->name ?? 'Lead Inspector')) }}
        </div>
        <div style="margin-top: 3px; font-size: 8pt; color: #475569; font-weight: normal;">
            <strong>Date:</strong>
            {{ $checklist->signed_at ? $checklist->signed_at->format('d/m/Y') : now()->format('d/m/Y') }}
        </div>
    </div>

</body>

</html>