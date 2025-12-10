<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\ConditionFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property-read Patient|null $patient
 *
 * @method static ConditionFactory factory($count = null, $state = [])
 * @method static Builder<static>|Condition newModelQuery()
 * @method static Builder<static>|Condition newQuery()
 * @method static Builder<static>|Condition query()
 *
 * @mixin \Eloquent
 */
class Condition extends Model
{
    /** @use HasFactory<ConditionFactory> */
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'visit_id',
        'diagnosis',
        'description',
        'started_at',
        'ended_at',
        'is_active',
        'notes',
    ];

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
