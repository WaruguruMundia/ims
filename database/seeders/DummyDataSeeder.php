<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\Intern;
use App\Models\LogbookEntry;
use App\Models\Role;
use App\Models\Supervisor;
use App\Models\Task;
use App\Models\User;
use App\Services\OnboardingService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supervisorRole = Role::where('slug', 'supervisor')->firstOrFail();
        $internRole = Role::where('slug', 'intern')->firstOrFail();

        $departments = Department::all();

        // Kenyan Names pools
        $firstNames = ['James', 'David', 'Grace', 'Mercy', 'Peter', 'Sarah', 'Paul', 'Faith', 'Andrew', 'Esther', 'John', 'Ruth', 'Stephen', 'Jane'];
        $surnames = ['Mwangi', 'Onyango', 'Kamau', 'Kiprop', 'Wambui', 'Otieno', 'Njoroge', 'Chebet', 'Mutua', 'Nduta', 'Ochieng', 'Jemutai', 'Kilonzo', 'Wanjiku'];
        
        $kenyanUniversities = [
            'Jomo Kenyatta University of Agriculture and Technology',
            'University of Nairobi',
            'Strathmore University',
            'Kenyatta University',
            'Egerton University',
            'United States International University-Africa'
        ];

        $programmes = [
            'IT' => ['Bachelor of Science in Computer Science', 'Bachelor of Science in Information Technology'],
            'FIN' => ['Bachelor of Commerce (Finance option)', 'Bachelor of Science in Financial Economics'],
            'HR' => ['Bachelor of Human Resource Management', 'Bachelor of Business Administration (HR option)'],
            'OPS' => ['Bachelor of Business Administration (Operations)', 'Bachelor of Science in Project Management']
        ];

        $activitiesPool = [
            'IT' => [
                'Set up the local Laravel development environment and configured database migrations.',
                'Assisted with debugging user authentication endpoints and optimizing SQL queries.',
                'Refactored frontend Blade components to improve dashboard layout readability.',
                'Conducted code reviews with senior developers and wrote functional tests.',
                'Updated project documentation and automated deployment workflows.'
            ],
            'FIN' => [
                'Prepared department monthly expense balance sheets and verified receipt records.',
                'Analyzed departmental budget variance reports for the second quarter.',
                'Assisted with auditing daily transaction entries and reconciling bank statements.',
                'Generated invoice reports and documented payment approvals.',
                'Compiled financial projections and presented findings to the supervisor.'
            ],
            'HR' => [
                'Drafted orientation schedules and materials for new team members.',
                'Reviewed job descriptions and sorted applicant profile submissions.',
                'Updated employee database directories and filed performance review logs.',
                'Coordinated logistics for the upcoming team building workshop.',
                'Compiled training materials and conducted onboarding questionnaires.'
            ],
            'OPS' => [
                'Monitored project milestone logs and updated Gantt progress charts.',
                'Analyzed team supply chain workflow bottlenecks and recommended improvements.',
                'Coordinated daily inventory supply audits and filed status updates.',
                'Assisted with documenting standard operating procedures (SOPs).',
                'Compiled weekly project performance metrics and delivery notes.'
            ]
        ];

        $taskTemplates = [
            ['title' => 'Complete System Walkthrough', 'desc' => 'Review the primary workflows and architectural overview documents.'],
            ['title' => 'Configure Local Environment', 'desc' => 'Follow setup guidelines to establish your development or management workspace.'],
            ['title' => 'Submit Week 1 Progress Report', 'desc' => 'Record activities performed, challenges encountered, and goals for the next week.'],
            ['title' => 'Optimize Department File Shares', 'desc' => 'Review file directories and archive outdated department logs.']
        ];

        $nameIndex = 0;

        foreach ($departments as $dept) {
            $supervisors = [];

            // 1. Create 2 Supervisors for each department
            for ($i = 1; $i <= 2; $i++) {
                $firstName = $firstNames[$nameIndex % count($firstNames)];
                $surname = $surnames[($nameIndex + 3) % count($surnames)];
                $fullName = $firstName . ' ' . $surname;
                $email = strtolower($firstName . '.' . $surname) . $dept->id . '@ims.test';
                $nameIndex++;

                // Ensure unique name/email combinations
                $user = User::create([
                    'role_id' => $supervisorRole->id,
                    'name' => $fullName,
                    'email' => $email,
                    'password' => Hash::make('Super@1234'),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]);

                $supervisor = Supervisor::create([
                    'user_id' => $user->id,
                    'dept_id' => $dept->id,
                    'employee_number' => 'EMP-' . $dept->code . '-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                    'max_intern_capacity' => 5,
                ]);

                $supervisors[] = $supervisor;
            }

            // 2. Create 3 Interns per supervisor (total of 24 interns)
            foreach ($supervisors as $supervisor) {
                for ($k = 1; $k <= 3; $k++) {
                    $firstName = $firstNames[$nameIndex % count($firstNames)];
                    $surname = $surnames[($nameIndex + 7) % count($surnames)];
                    $fullName = $firstName . ' ' . $surname;
                    // Ensure unique email addresses by using supervisor ID and index
                    $email = strtolower($firstName . '.' . $surname) . '_' . $supervisor->id . '_' . $k . '@ims.test';
                    $nameIndex++;

                    $user = User::create([
                        'role_id' => $internRole->id,
                        'name' => $fullName,
                        'email' => $email,
                        'password' => Hash::make('Intern@1234'),
                        'is_active' => true,
                        'email_verified_at' => now(),
                    ]);

                    $deptProgrammes = $programmes[$dept->code] ?? ['Bachelor of Business Administration'];
                    $programme = $deptProgrammes[array_rand($deptProgrammes)];
                    $university = $kenyanUniversities[array_rand($kenyanUniversities)];

                    $intern = Intern::create([
                        'user_id' => $user->id,
                        'dept_id' => $dept->id,
                        'supervisor_id' => $supervisor->user_id,
                        'institution' => $university,
                        'programme' => $programme,
                        'student_number' => 'STD-' . $dept->code . '-' . rand(1000, 9999),
                        'start_date' => now()->subDays(20),
                        'end_date' => now()->addMonths(2),
                        'is_active' => true,
                    ]);

                    // Initialize checklist
                    app(OnboardingService::class)->initializeChecklist($intern);

                    // Complete a couple of checklist items to simulate progress
                    $intern->onboardingChecklists->take(2)->each(function ($item) {
                        $item->update([
                            'is_completed' => true,
                            'completed_at' => now()->subDays(15),
                        ]);
                    });

                    // 3. Create varying logbook entries to establish different streaks
                    $activities = $activitiesPool[$dept->code] ?? ['Performed daily operations.'];
                    
                    if ($k === 1) {
                        // Intern 1: 12-day streak (logs from subDays(11) to today)
                        for ($day = 11; $day >= 0; $day--) {
                            $entryDate = now()->subDays($day);
                            LogbookEntry::create([
                                'intern_id' => $intern->id,
                                'entry_date' => $entryDate,
                                'entry_type' => 'daily',
                                'activities_performed' => $activities[$day % count($activities)],
                            ]);
                        }
                    } elseif ($k === 2) {
                        // Intern 2: 6-day streak (logs from subDays(5) to today)
                        for ($day = 5; $day >= 0; $day--) {
                            $entryDate = now()->subDays($day);
                            LogbookEntry::create([
                                'intern_id' => $intern->id,
                                'entry_date' => $entryDate,
                                'entry_type' => 'daily',
                                'activities_performed' => $activities[$day % count($activities)],
                            ]);
                        }
                    } else {
                        // Intern 3: 0-day streak (logs recorded 5 and 4 days ago, leaving today & yesterday blank)
                        for ($day = 5; $day >= 4; $day--) {
                            $entryDate = now()->subDays($day);
                            LogbookEntry::create([
                                'intern_id' => $intern->id,
                                'entry_date' => $entryDate,
                                'entry_type' => 'daily',
                                'activities_performed' => $activities[$day % count($activities)],
                            ]);
                        }
                    }

                    // 4. Create tasks with various statuses
                    foreach ($taskTemplates as $idx => $tmpl) {
                        $status = 'pending';
                        $submittedAt = null;
                        $reviewedAt = null;
                        $notes = null;
                        $feedback = null;

                        if ($idx === 0) {
                            $status = 'approved'; // Closed / Resolved
                            $submittedAt = now()->subDays(12);
                            $reviewedAt = now()->subDays(11);
                            $notes = 'Walked through all modules and verified checklist templates.';
                            $feedback = 'Excellent job, confirmed correct understanding of systems.';
                        } elseif ($idx === 1) {
                            $status = 'submitted'; // Completed task pending review
                            $submittedAt = now()->subDays(4);
                            $notes = 'Environment fully configured, database tables populated successfully.';
                        } elseif ($idx === 2) {
                            $status = 'in_progress';
                        }

                        Task::create([
                            'intern_id' => $intern->id,
                            'created_by' => $supervisor->user_id,
                            'title' => $tmpl['title'],
                            'description' => $tmpl['desc'],
                            'priority' => ['high', 'medium', 'low'][$idx % 3],
                            'status' => $status,
                            'due_date' => now()->addDays(5 - $idx),
                            'submission_notes' => $notes,
                            'reviewer_feedback' => $feedback,
                            'submitted_at' => $submittedAt,
                            'reviewed_at' => $reviewedAt,
                        ]);
                    }
                }
            }
        }
    }
}
