<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Scopes\ReminderCurrentPeriodScope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Patient $patient
 */
class Reminder extends Model
{
    public $fillable = ['patient_id', 'reminder_text', 'reminder_time'];

    protected static function booted(): void
    {
        static::addGlobalScope(new ReminderCurrentPeriodScope());
    }

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
