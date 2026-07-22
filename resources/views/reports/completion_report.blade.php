<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Internship Completion Report - {{ $intern->user?->name }}</title>
    <style>
        body {
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            color: #333;
            line-height: 1.5;
            font-size: 14px;
            margin: 0;
            padding: 0;
        }
        .header {
            text-align: center;
            border-bottom: 2px solid #3b82f6;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #1e3a8a;
        }
        .header p {
            margin: 5px 0 0 0;
            font-size: 14px;
            color: #4b5563;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1e3a8a;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 5px;
            margin-bottom: 15px;
            text-transform: uppercase;
        }
        .meta-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        .meta-table td {
            padding: 6px 10px;
            vertical-align: top;
        }
        .meta-table td.label {
            font-weight: bold;
            color: #4b5563;
            width: 25%;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        .data-table th, .data-table td {
            border: 1px solid #e5e7eb;
            padding: 8px 10px;
            text-align: left;
            font-size: 12px;
        }
        .data-table th {
            background-color: #f3f4f6;
            color: #374151;
            font-weight: bold;
        }
        .progress-bar {
            background-color: #e5e7eb;
            border-radius: 4px;
            height: 12px;
            width: 200px;
            display: inline-block;
            vertical-align: middle;
            margin-right: 10px;
        }
        .progress-fill {
            background-color: #10b981;
            height: 12px;
            border-radius: 4px;
        }
        .badge {
            display: inline-block;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-gray { background-color: #f3f4f6; color: #374151; }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>

    <div class="header">
        <h1>INTERNSHIP COMPLETION REPORT</h1>
        <p>Intern Management System (IMS)</p>
    </div>

    <!-- Placement Details Section -->
    <div class="section">
        <div class="section-title">1. Placement Details</div>
        <table class="meta-table">
            <tr>
                <td class="label">Intern Name:</td>
                <td>{{ $intern->user?->name }}</td>
                <td class="label">Department:</td>
                <td>{{ $intern->department?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Institution:</td>
                <td>{{ $intern->institution }}</td>
                <td class="label">Supervisor:</td>
                <td>{{ $intern->supervisor?->name ?? 'N/A' }}</td>
            </tr>
            <tr>
                <td class="label">Programme:</td>
                <td>{{ $intern->programme }}</td>
                <td class="label">Placement Dates:</td>
                <td>{{ $intern->start_date->format('Y-m-d') }} to {{ $intern->end_date->format('Y-m-d') }}</td>
            </tr>
        </table>
    </div>

    <!-- Onboarding Checklist Status -->
    <div class="section">
        <div class="section-title">2. Onboarding Status</div>
        <p style="margin: 0 0 10px 0;">
            Checklist Completion: <strong>{{ $onboardingProgress }}%</strong>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $onboardingProgress }}%;"></div>
            </div>
            (Onboarding formally completed: <strong>{{ $intern->hasCompletedRequiredOnboarding() ? 'Yes' : 'No' }}</strong>)
        </p>

        <table class="data-table">
            <thead>
                <tr>
                    <th>Onboarding Item</th>
                    <th style="width: 15%;">Required</th>
                    <th style="width: 20%;">Status</th>
                    <th style="width: 25%;">Completed At</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($intern->onboardingChecklists as $item)
                    <tr>
                        <td>{{ $item->item }}</td>
                        <td>{{ $item->is_required ? 'Yes' : 'No' }}</td>
                        <td>
                            <span class="badge {{ $item->is_completed ? 'badge-green' : 'badge-yellow' }}">
                                {{ $item->is_completed ? 'Completed' : 'Pending' }}
                            </span>
                        </td>
                        <td>{{ $item->completed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="page-break"></div>

    <!-- Tasks Summary Section -->
    <div class="section">
        <div class="section-title">3. Task Completion Record</div>
        <table class="data-table">
            <thead>
                <tr>
                    <th>Task Title</th>
                    <th style="width: 15%;">Priority</th>
                    <th style="width: 15%;">Status</th>
                    <th style="width: 20%;">Due Date</th>
                    <th style="width: 20%;">Completed At</th>
                </tr>
            </thead>
            <tbody>
                @if ($intern->tasks->isEmpty())
                    <tr>
                        <td colspan="5" style="text-align: center; color: #6b7280;">No tasks assigned.</td>
                    </tr>
                @else
                    @foreach ($intern->tasks as $task)
                        <tr>
                            <td>{{ $task->title }}</td>
                            <td>{{ ucfirst($task->priority) }}</td>
                            <td>
                                <span class="badge 
                                    @if($task->status === 'approved') badge-green
                                    @elseif($task->status === 'submitted') badge-blue
                                    @elseif($task->status === 'in_progress') badge-gray
                                    @elseif($task->status === 'rejected') badge-red
                                    @else badge-yellow @endif">
                                    {{ $task->status }}
                                </span>
                            </td>
                            <td>{{ $task->due_date->format('Y-m-d') }}</td>
                            <td>{{ $task->reviewed_at?->format('Y-m-d H:i') ?? '-' }}</td>
                        </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

    <!-- Logbook Summary -->
    <div class="section">
        <div class="section-title">4. Digital Logbook Activities</div>
        <p style="margin: 0 0 10px 0;">
            Total diary submissions logged: <strong>{{ $intern->logbookEntries->count() }} entries</strong>.
        </p>
        <table class="data-table">
            <thead>
                <tr>
                    <th style="width: 20%;">Date</th>
                    <th style="width: 15%;">Type</th>
                    <th>Activities Performed</th>
                </tr>
            </thead>
            <tbody>
                @if ($intern->logbookEntries->isEmpty())
                    <tr>
                        <td colspan="3" style="text-align: center; color: #6b7280;">No logbook entries recorded.</td>
                    </tr>
                @else
                    @foreach ($intern->logbookEntries->take(10) as $entry)
                        <tr>
                            <td>{{ $entry->entry_date->format('Y-m-d') }}</td>
                            <td style="text-transform: uppercase;">{{ $entry->entry_type }}</td>
                            <td>{{ substr($entry->activities_performed, 0, 100) }}...</td>
                        </tr>
                    @endforeach
                    @if ($intern->logbookEntries->count() > 10)
                        <tr>
                            <td colspan="3" style="text-align: center; color: #6b7280; font-style: italic;">
                                ... showing first 10 of {{ $intern->logbookEntries->count() }} entries ...
                            </td>
                        </tr>
                    @endif
                @endif
            </tbody>
        </table>
    </div>

    <!-- Performance Evaluation Section -->
    @if ($evaluation)
        <div class="section" style="page-break-inside: avoid;">
            <div class="section-title">5. Performance Evaluation Ratings</div>
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Competency Area</th>
                        <th style="width: 20%;">Score</th>
                        <th>Specific Feedback</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($evaluation->evaluationScores as $score)
                        <tr>
                            <td>
                                <strong>{{ $score->criteria?->name }}</strong><br>
                                <span style="font-size: 10px; color: #6b7280;">{{ $score->criteria?->description }}</span>
                            </td>
                            <td>
                                <strong>{{ $score->score }}</strong> / {{ $score->criteria?->max_score }}
                            </td>
                            <td>{{ $score->comment ?? '-' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div style="margin-top: 15px; background-color: #f9fafb; border: 1px solid #e5e7eb; padding: 12px; border-radius: 4px;">
                <strong>Overall Feedback Summary:</strong>
                <p style="margin: 5px 0 0 0; font-size: 12px; color: #374151; whitespace: pre-wrap;">
                    {{ $evaluation->overall_feedback }}
                </p>
            </div>
        </div>
    @endif

    <!-- Signatures -->
    <div class="section" style="margin-top: 50px; page-break-inside: avoid;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 45%; border-top: 1px solid #9ca3af; text-align: center; padding-top: 5px;">
                    <strong>{{ $intern->supervisor?->name ?? 'Supervisor Signature' }}</strong><br>
                    <span style="font-size: 11px; color: #6b7280;">Company Supervisor</span>
                </td>
                <td style="width: 10%;"></td>
                <td style="width: 45%; border-top: 1px solid #9ca3af; text-align: center; padding-top: 5px;">
                    <strong>Neema Wacuka</strong><br>
                    <span style="font-size: 11px; color: #6b7280;">HR Administrator</span>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
