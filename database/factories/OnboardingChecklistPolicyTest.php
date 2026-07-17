<?php

use App\Models\Intern;
use App\Models\OnboardingChecklist;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows an administrator to complete any intern\'s checklist item', function () {
    $admin = User::factory()->admin()->create();
    $intern = Intern::factory()->create();
    $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $intern->id]);

    expect($admin->can('complete', $checklistItem))->toBeTrue();
});

it('allows an administrator to complete any intern\'s checklist item', function () {
    $admin = User::factory()->admin()->create();
    $checklistItem = OnboardingChecklist::factory()->create();

    expect($admin->can('complete', $checklistItem))->toBeTrue();
});

it('allows a supervisor to complete their own supervisee\'s checklist item', function () {
    $supervisor = User::factory()->supervisor()->create();
    $intern = Intern::factory()->create(['supervisor_id' => $supervisor->id]);
    $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $intern->id]);

    expect($supervisor->can('complete', $checklistItem))->toBeTrue();
});

it('denies a supervisor completing a checklist item for an intern they do not supervise', function () {
    $supervisor = User::factory()->supervisor()->create();
    $otherIntern = Intern::factory()->create(); // random, unrelated supervisor
    $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $otherIntern->id]);

    expect($supervisor->can('complete', $checklistItem))->toBeFalse();
});

it('allows an intern to complete their own checklist item', function () {
    $internUser = User::factory()->intern()->create();
    $intern = Intern::factory()->create(['user_id' => $internUser->id]);
    $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $intern->id]);

    expect($internUser->can('complete', $checklistItem))->toBeTrue();
});

it('denies an intern completing another intern\'s checklist item', function () {
    $internUser = User::factory()->intern()->create();
    $otherIntern = Intern::factory()->create(); // different user_id entirely

    $checklistItem = OnboardingChecklist::factory()->create(['intern_id' => $otherIntern->id]);

    expect($internUser->can('complete', $checklistItem))->toBeFalse();
});
