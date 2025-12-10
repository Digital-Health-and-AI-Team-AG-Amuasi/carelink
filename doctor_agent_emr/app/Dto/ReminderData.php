<?php

declare(strict_types=1);

namespace App\Dto;

class ReminderData
{
    public function __construct(
        public string $reminderTime,
        public string $reminderText,
    ) {
    }

    //    public function toArray(): array
    //    {
    //        return [
    //            $this->reminder_time,
    //            $this->reminder_text
    //        ];
    //    }

}
