<?php

namespace Tests\Feature;

use App\Models\Department;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Supervisor;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class InternLogEditAndStreakTest extends TestCase
{
    use RefreshDatabase;

    protected User $supervisorUser;
    protected User $internUser;
    protected Intern $intern;

    protected function setUp(): void
    {
        parent::setUp();

        $department = Department::factory()->create();
        $this->supervisorUser = User::factory()->supervisor()->create();
        $this->internUser = User::factory()->intern()->create();

        $this->intern = Intern::create([
            'user_id' => $this->internUser->id,
            'dept_id' => $department->id,
            'supervisor_id' => $this->supervisorUser->id,
            'institution' => 'Test Uni',
            'programme' => 'Test Course',
            'start_date' => now()->subMonth(),
            'end_date' => now()->addMonths(2),
        ]);
    }

    public function test_intern_can_only_edit_log_recorded_for_current_day(): void
    {
        // 1. Create a log entry dated today
        $todayEntry = LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now(),
            'entry_type' => 'daily',
            'activities_performed' => 'Coding some cool feature today.',
        ]);

        // Intern can visit the edit page for today's log
        $this->actingAs($this->internUser)
            ->get(route('intern.logbook.edit', $todayEntry))
            ->assertOk()
            ->assertSee('Edit Logbook Entry');

        // Intern can update today's log
        $this->actingAs($this->internUser)
            ->put(route('intern.logbook.update', $todayEntry), [
                'entry_type' => 'daily',
                'activities_performed' => 'Updated coding today.',
            ])
            ->assertRedirect(route('intern.logbook.index'));

        $this->assertEquals('Updated coding today.', $todayEntry->fresh()->activities_performed);

        // 2. Create a log entry dated yesterday
        $yesterdayEntry = LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now()->subDay(),
            'entry_type' => 'daily',
            'activities_performed' => 'Coding yesterday.',
        ]);

        // Intern cannot edit yesterday's log (returns 403)
        $this->actingAs($this->internUser)
            ->get(route('intern.logbook.edit', $yesterdayEntry))
            ->assertForbidden();

        // Intern cannot update yesterday's log (returns 403)
        $this->actingAs($this->internUser)
            ->put(route('intern.logbook.update', $yesterdayEntry), [
                'entry_type' => 'daily',
                'activities_performed' => 'Trying to update yesterday.',
            ])
            ->assertForbidden();
    }

    public function test_logbook_streak_calculation_rules(): void
    {
        // 1. No entries -> streak is 0
        $this->assertEquals(0, $this->intern->logbookStreak());

        // 2. Log entry today only -> streak is 1
        LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now(),
            'entry_type' => 'daily',
            'activities_performed' => 'Day 1',
        ]);
        $this->assertEquals(1, $this->intern->fresh()->logbookStreak());

        // 3. Log entry today and yesterday -> streak is 2
        LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now()->subDay(),
            'entry_type' => 'daily',
            'activities_performed' => 'Day 2',
        ]);
        $this->assertEquals(2, $this->intern->fresh()->logbookStreak());

        // 4. Log entry today, yesterday, and two days ago -> streak is 3
        LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now()->subDays(2),
            'entry_type' => 'daily',
            'activities_performed' => 'Day 3',
        ]);
        $this->assertEquals(3, $this->intern->fresh()->logbookStreak());

        // 5. Gap in streak: logs today, yesterday, 2 days ago, and 4 days ago -> streak is still 3
        LogbookEntry::create([
            'intern_id' => $this->intern->id,
            'entry_date' => now()->subDays(4),
            'entry_type' => 'daily',
            'activities_performed' => 'Gap day',
        ]);
        $this->assertEquals(3, $this->intern->fresh()->logbookStreak());
    }
}
