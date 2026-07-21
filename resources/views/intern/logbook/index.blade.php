<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Digital Logbook') }}
            </h2>
            <a href="{{ route('intern.logbook.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                Record New Entry
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            @if (session('status'))
                <div class="bg-green-100 border border-green-300 text-green-800 px-4 py-3 rounded">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Guest Share Link Card -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg border-l-4 border-indigo-500">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">
                        Share Logbook with University Supervisor
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Generate a secure, time-limited link that allows your external university supervisor to view your digital logbook entries without needing an account.
                    </p>

                    @if ($activeToken)
                        <div class="bg-gray-50 p-4 rounded border border-gray-200 mb-4 flex flex-col md:flex-row md:items-center justify-between gap-4">
                            <div>
                                <span class="text-xs uppercase font-bold text-gray-500 block">Your Active Guest Access Link (Expires {{ $activeToken->expires_at->format('Y-m-d H:i') }})</span>
                                <input type="text" readonly value="{{ route('guest.logbooks.show', $activeToken->token) }}" class="mt-1 w-full md:w-96 text-sm bg-gray-150 border-gray-300 rounded shadow-sm font-mono text-gray-700" onclick="this.select();" id="guestLinkInput">
                            </div>
                            <div>
                                <button onclick="copyGuestLink()" class="bg-white hover:bg-gray-50 text-gray-700 font-semibold py-2 px-4 border border-gray-300 rounded shadow-sm text-sm transition">
                                    Copy Link
                                </button>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('intern.logbook.generate-token') }}">
                        @csrf
                        <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded text-sm transition">
                            {{ $activeToken ? 'Regenerate Access Link' : 'Generate Guest Share Link' }}
                        </button>
                    </form>
                </div>
            </div>

            <!-- Logbook Entries list -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        My Logbook Entries
                    </h3>

                    @if ($entries->isEmpty())
                        <p class="text-gray-600">
                            No logbook entries have been recorded yet. Click "Record New Entry" above to add your first daily or weekly log.
                        </p>
                    @else
                        <div class="space-y-6">
                            @foreach ($entries as $entry)
                                <div class="border border-gray-200 rounded p-4 shadow-sm hover:shadow transition bg-white">
                                    <div class="flex justify-between items-start border-b border-gray-100 pb-2 mb-3">
                                        <div>
                                            <span class="text-sm font-semibold text-gray-950">{{ $entry->entry_date->format('l, Y-m-d') }}</span>
                                        </div>
                                        <div>
                                            <span class="px-2 py-0.5 rounded text-xs font-semibold uppercase
                                                @if($entry->entry_type === 'weekly') bg-purple-100 text-purple-800
                                                @else bg-green-100 text-green-800 @endif">
                                                {{ $entry->entry_type }} Log
                                            </span>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm mt-2">
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Activities Performed:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->activities_performed }}</p>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Challenges Encountered:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->challenges_encountered ?? '-' }}</p>
                                        </div>
                                        <div>
                                            <strong class="text-gray-700 block mb-1">Skills Developed:</strong>
                                            <p class="text-gray-600 whitespace-pre-wrap">{{ $entry->skills_developed ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

        </div>
    </div>

    <script>
        function copyGuestLink() {
            var copyText = document.getElementById("guestLinkInput");
            copyText.select();
            copyText.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(copyText.value);
            alert("Guest link copied to clipboard!");
        }
    </script>
</x-app-layout>
