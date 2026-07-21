<?php

namespace App\Http\Controllers;

use App\Models\GuestToken;
use App\Models\LogbookEntry;
use Illuminate\View\View;

class GuestLogbookController extends Controller
{
    /**
     * Display the intern's logbook entries in read-only mode for external guest users.
     */
    public function show(string $token): View
    {
        // Find a valid (active, non-expired, non-revoked) token
        $guestToken = GuestToken::where('token', $token)
            ->active()
            ->first();

        if (!$guestToken) {
            abort(403, 'The guest access link is invalid, expired, or has been revoked.');
        }

        $intern = $guestToken->intern;
        $intern->load(['user', 'department']);

        $entries = LogbookEntry::where('intern_id', $intern->id)
            ->orderBy('entry_date', 'desc')
            ->get();

        return view('guest.logbook.show', compact('intern', 'entries', 'guestToken'));
    }
}
