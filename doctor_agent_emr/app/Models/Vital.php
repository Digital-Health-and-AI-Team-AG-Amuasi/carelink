<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property int $visit_id
 * @property int $vital_type_id
 * @property string $value
 * @property string $unit_of_measurement
 * @property string|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereUnitOfMeasurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereValue($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereVisitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Vital whereVitalTypeId($value)
 *
 * @property-read Visit $visit
 * @property-read VitalType $vitalType
 *
 * @mixin \Eloquent
 */
class Vital extends Model
{
    protected $fillable = [
        'visit_id',
        'vital_type_id',
        'value',
        'unit_of_measurement',
    ];

    protected $casts = [
        'value' => 'string',
        'unit_of_measurement' => 'string',
    ];

    protected function casts(): array
    {
        return [
            'value' => 'string',
            'unit_of_measurement' => 'string',
        ];
    }

    /**
     * @return BelongsTo<Visit, $this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * @return BelongsTo<VitalType, $this>
     */
    public function vitalType(): BelongsTo
    {
        return $this->belongsTo(VitalType::class);
    }
}
