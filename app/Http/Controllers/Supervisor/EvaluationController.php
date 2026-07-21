<?php

namespace App\Http\Controllers\Supervisor;

use App\Http\Controllers\Controller;
use App\Models\CompetencyCriteria;
use App\Models\Evaluation;
use App\Models\EvaluationScore;
use App\Models\Intern;
use App\Models\Supervisor;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class EvaluationController extends Controller
{
    /**
     * Show the evaluation page or form for a specific intern.
     */
    public function create(Request $request): View|RedirectResponse
    {
        $internId = $request->query('intern_id');
        $intern = Intern::where('id', $internId)
            ->where('supervisor_id', Auth::id())
            ->with('user')
            ->firstOrFail();

        // Retrieve the supervisor record from t_supervisors
        $supervisor = Supervisor::where('user_id', Auth::id())->firstOrFail();

        // Check if an evaluation already exists
        $evaluation = Evaluation::where('intern_id', $intern->id)
            ->where('supervisor_id', $supervisor->id)
            ->with('evaluationScores')
            ->first();

        // If it's already submitted, redirect to show page (read-only)
        if ($evaluation && $evaluation->status === 'submitted') {
            return redirect()->route('supervisor.evaluations.show', $evaluation);
        }

        $criteria = CompetencyCriteria::where('is_active', true)->get();

        // Build a keyed array of existing scores if editing a draft
        $existingScores = [];
        if ($evaluation) {
            foreach ($evaluation->evaluationScores as $score) {
                $existingScores[$score->criteria_id] = [
                    'score' => $score->score,
                    'comment' => $score->comment,
                ];
            }
        }

        return view('supervisor.evaluations.create', compact('intern', 'criteria', 'evaluation', 'existingScores'));
    }

    /**
     * Store or update the intern evaluation (draft or submitted).
     */
    public function store(Request $request): RedirectResponse
    {
        // Validate basic inputs
        $request->validate([
            'intern_id' => ['required', 'exists:t_interns,id'],
            'overall_feedback' => ['required', 'string'],
            'status' => ['required', 'in:draft,submitted'],
            'scores' => ['required', 'array'],
        ]);

        $intern = Intern::where('id', $request->intern_id)
            ->where('supervisor_id', Auth::id())
            ->firstOrFail();

        $supervisor = Supervisor::where('user_id', Auth::id())->firstOrFail();

        $criteriaList = CompetencyCriteria::where('is_active', true)->get()->keyBy('id');

        // Dynamic validation of scores based on competency criteria limits
        $validatedScores = [];
        foreach ($request->scores as $criteriaId => $scoreData) {
            if (!$criteriaList->has($criteriaId)) {
                return back()->withErrors(["scores.{$criteriaId}" => "Invalid competency criterion selection."])->withInput();
            }

            $criterion = $criteriaList->get($criteriaId);
            $maxScore = $criterion->max_score;

            $validatedScore = filter_var($scoreData['score'] ?? null, FILTER_VALIDATE_INT);
            if ($validatedScore === false || $validatedScore < 0 || $validatedScore > $maxScore) {
                return back()->withErrors(["scores.{$criteriaId}.score" => "The score for '{$criterion->name}' must be an integer between 0 and {$maxScore}."])->withInput();
            }

            $validatedScores[] = [
                'criteria_id' => $criteriaId,
                'score' => $validatedScore,
                'comment' => $scoreData['comment'] ?? null,
            ];
        }

        // Use DB Transaction to persist evaluation and evaluation scores
        DB::transaction(function () use ($intern, $supervisor, $request, $validatedScores) {
            $evaluation = Evaluation::updateOrCreate(
                [
                    'intern_id' => $intern->id,
                    'supervisor_id' => $supervisor->id,
                ],
                [
                    'overall_feedback' => $request->overall_feedback,
                    'status' => $request->status,
                    'submitted_at' => $request->status === 'submitted' ? now() : null,
                ]
            );

            // Update or create criteria scores
            foreach ($validatedScores as $scoreData) {
                EvaluationScore::updateOrCreate(
                    [
                        'evaluation_id' => $evaluation->id,
                        'criteria_id' => $scoreData['criteria_id'],
                    ],
                    [
                        'score' => $scoreData['score'],
                        'comment' => $scoreData['comment'],
                    ]
                );
            }
        });

        $message = $request->status === 'submitted' 
            ? 'Evaluation submitted successfully.' 
            : 'Evaluation draft saved successfully.';

        return redirect()->route('supervisor.dashboard')->with('status', $message);
    }

    /**
     * Display a completed evaluation.
     */
    public function show(Evaluation $evaluation): View
    {
        // Authorize supervisor access
        $supervisor = Supervisor::where('user_id', Auth::id())->firstOrFail();
        if ($evaluation->supervisor_id !== $supervisor->id) {
            abort(403, 'Unauthorized action.');
        }

        $evaluation->load(['intern.user', 'evaluationScores.criteria']);

        return view('supervisor.evaluations.show', compact('evaluation'));
    }
}
