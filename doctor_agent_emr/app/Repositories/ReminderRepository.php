<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Dto\CreateReminderDto;
use App\Models\Patient;
use App\Models\Reminder;
use Illuminate\Database\Eloquent\Collection;

class ReminderRepository
{
    /**
     * @param CreateReminderDto $dto
     * @param Patient $patient
     *
     * @return Collection<int, Reminder>
     */
    public function create(CreateReminderDto $dto, Patient $patient): Collection
    {
        return $patient->reminders()->createMany($dto->toArray());
    }

}
