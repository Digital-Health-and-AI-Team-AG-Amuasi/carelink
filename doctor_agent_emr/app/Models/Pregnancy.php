<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\PregnancyFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $patient_id
 * @property \Illuminate\Support\Carbon|null $edd
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\PregnancyFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy whereEdd($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy wherePatientId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Pregnancy withoutTrashed()
 *
 * @property-read Patient $patient
 *
 * @mixin \Eloquent
 */
class Pregnancy extends Model
{
    use SoftDeletes;
    /** @use HasFactory<PregnancyFactory> */
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'edd',
    ];

    protected function casts(): array
    {
        return [
            'edd' => 'date',
        ];
    }

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
