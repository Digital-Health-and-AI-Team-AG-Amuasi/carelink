<?php

declare(strict_types=1);

namespace App\Models\Scopes;

use App\Enums\PeriodOfDay;
use App\Models\Reminder;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;

class ReminderCurrentPeriodScope implements Scope
{
    /**
     * Apply the scope to a given Eloquent query builder.
     *
     * @param Builder<Reminder> $builder
     * @param Model $model
     */
    public function apply(Builder $builder, Model $model): void
    {
        $periodOfDay = PeriodOfDay::fromTime(now());

        $builder
            ->select('id', 'patient_id', 'reminder_text')
            ->with(['patient:id,first_name,last_name,phone'])
            ->where('reminder_time', $periodOfDay->value);
    }
}
