<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Enums\PeriodOfDay;
use App\Models\Reminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SendReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $reminders = Reminder::all(); // Uses scopes to retrieve reminders for particular time of day

        if ($reminders->isEmpty()) {
            $periodOfDay = PeriodOfDay::fromTime(now());

            Log::debug('No reminders found ' . $periodOfDay->value);
            return;
        }

        try {

            foreach ($reminders as $reminder) {
                Log::debug('sending reminder ' . json_encode($reminder));
                SendSingleReminderJob::dispatch($reminder);
            }

        } catch (\Exception $exception) {
            // TODO: Implement retries or something like that
            Log::debug('Exception in SendReminderJob ' . $exception);

            return;
        }

    }
}
