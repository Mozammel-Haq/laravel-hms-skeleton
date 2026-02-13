<?php

use App\Models\Appointment;
use Carbon\Carbon;
use Tests\TestCase;

it('classifies passed when same-day start before now and no end', function () {
    Carbon::setTestNow(Carbon::parse('2026-02-12 13:58:00'));
    $a = new Appointment([
        'appointment_date' => '2026-02-12',
    ]);
    $a->setAttribute('start_time', '09:30 AM');
    expect($a->isPassedNow())->toBeTrue();
    expect($a->isUpcomingNow())->toBeFalse();
});

it('classifies ongoing when within start and end', function () {
    Carbon::setTestNow(Carbon::parse('2026-02-12 10:00:00'));
    $a = new Appointment([
        'appointment_date' => '2026-02-12',
    ]);
    $a->setAttribute('start_time', '09:30');
    $a->setAttribute('end_time', '10:30');
    expect($a->isOngoingNow())->toBeTrue();
    expect($a->isUpcomingNow())->toBeFalse();
    expect($a->isPassedNow())->toBeFalse();
});

it('classifies upcoming when same-day now before start', function () {
    Carbon::setTestNow(Carbon::parse('2026-02-12 08:00:00'));
    $a = new Appointment([
        'appointment_date' => '2026-02-12',
    ]);
    $a->setAttribute('start_time', '09:30');
    expect($a->isUpcomingNow())->toBeTrue();
    expect($a->isPassedNow())->toBeFalse();
});

it('classifies passed when date in the past', function () {
    Carbon::setTestNow(Carbon::parse('2026-02-12 08:00:00'));
    $a = new Appointment([
        'appointment_date' => '2026-02-11',
    ]);
    $a->setAttribute('start_time', '21:00');
    expect($a->isPassedNow())->toBeTrue();
    expect($a->isUpcomingNow())->toBeFalse();
});
