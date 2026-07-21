<?php

namespace App\Http\Controllers\Intern;

use App\Http\Controllers\Controller;
use App\Models\GuestToken;
use App\Models\Intern;
use App\Models\LogbookEntry;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\View\View;

class LogbookController extends Controller
{
    /**
     * Display the logbook entries and any generated guest access links.
     */
    public function index(): View
    {
        $intern = Intern::where('user_id', Auth::id())->firstOrFail();
        $entries = LogbookEntry::where('intern_id', $intern->id)
            ->orderBy('entry_date', 'desc')
            ->get();

        $activeToken = GuestToken::where('intern_id', $intern->id)
            ->active()
            ->latest()
            ->first();

        return view('intern.logbook.index', compact('entries', 'intern', 'activeToken'));
    }

    /**
     * Show the form for creating a new logbook entry.
     */
    public function create(): View
    {
        return view('intern.logbook.create');
    }

    /**
     * Store a newly created logbook entry.
     */
    public function store(Request $request): RedirectResponse
    {
        $intern = Intern::where('user_id', Auth::id())->firstOrFail();

        $validated = $request->validate([
            'entry_date' => ['required', 'date', 'before_or_equal:today'],
            'entry_type' => ['required', 'in:daily,weekly'],
            'activities_performed' => ['required', 'string'],
            'challenges_encountered' => ['nullable', 'string'],
            'skills_developed' => ['nullable', 'string'],
        ]);

        LogbookEntry::create([
            'intern_id' => $intern->id,
            'entry_date' => $validated['entry_date'],
            'entry_type' => $validated['entry_type'],
            'activities_performed' => $validated['activities_performed'],
            'challenges_encountered' => $validated['challenges_encountered'] ?? null,
            'skills_developed' => $validated['skills_developed'] ?? null,
        ]);

        return redirect()->route('intern.logbook.index')
            ->with('status', 'Logbook entry recorded successfully.');
    }

    /**
     * Generate a guest access token link for external supervisors.
     */
    public function generateToken(): RedirectResponse
    {
        $intern = Intern::where('user_id', Auth::id())->firstOrFail();

        // Revoke any existing active tokens first to ensure single active token
        GuestToken::where('intern_id', $intern->id)->update(['is_revoked' => true]);

        // Generate a new secure token (valid for 7 days)
        $token = Str::random(40);
        GuestToken::create([
            'intern_id' => $intern->id,
            'generated_by' => Auth::id(),
            'token' => $token,
            'expires_at' => now()->addDays(7),
            'is_revoked' => false,
        ]);

        return redirect()->route('intern.logbook.index')
            ->with('status', 'New guest access link generated.');
    }
}
