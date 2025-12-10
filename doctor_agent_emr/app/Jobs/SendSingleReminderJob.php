<?php

declare(strict_types=1);

namespace App\Jobs;

use App\Actions\MessageRequestAction;
use App\Models\Reminder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Http\Client\RequestException;
use Illuminate\Support\Facades\Log;

class SendSingleReminderJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     *
     * @param Reminder $reminder
     */
    public function __construct(
        public Reminder $reminder
    ) {
    }

    /**
     * @throws RequestException
     */
    public function handle(): void
    {
        $payload = [
            'recipient_phone_number' => $this->reminder->patient->phone,
            'reminder' => [
                'body' => $this->reminder->reminder_text,
            ],
        ];
        try {

            Log::debug('payload ' . json_encode($payload));
            (new MessageRequestAction())->request($payload);
        } catch (\Exception $exception) {
            // TODO: Implement retries or something like that
            Log::debug('Exception in SendSingleReminderJob ' . $exception);

            throw $exception;
        }

    }
}
