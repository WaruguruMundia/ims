<?php

namespace App\Http\Controllers;

use App\Models\Evaluation;
use App\Models\Intern;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class ReportController extends Controller
{
    /**
     * Generate and download the PDF internship completion report.
     */
    public function download(Intern $intern)
    {
        // Security checks: Admin can access all, Supervisor can only access their supervisees
        if (Auth::user()->isSupervisor() && $intern->supervisor_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        if (Auth::user()->isIntern()) {
            // Interns cannot download their own completion report directly unless explicitly authorized
            abort(403, 'Unauthorized action.');
        }

        $intern->load(['user', 'department', 'tasks', 'logbookEntries', 'supervisor']);

        $evaluation = Evaluation::where('intern_id', $intern->id)
            ->where('status', 'submitted')
            ->with('evaluationScores.criteria')
            ->first();

        $onboardingProgress = $intern->onboardingProgressPercentage();

        $pdf = Pdf::loadView('reports.completion_report', compact('intern', 'evaluation', 'onboardingProgress'));

        $filename = 'completion_report_' . Str::slug($intern->user?->name ?? 'intern') . '_' . date('Ymd') . '.pdf';

        return $pdf->download($filename);
    }
}
