<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\MedicationFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $visit_id
 * @property int $drug_id
 * @property string $frequency
 * @property string $duration
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\MedicationFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereDrugId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereDuration($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereFrequency($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication whereVisitId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Medication withoutTrashed()
 *
 * @property-read Drug|null $drug
 * @property-read Visit|null $visit
 *
 * @mixin \Eloquent
 */
class Medication extends Model
{
    use SoftDeletes;
    /** @use HasFactory<MedicationFactory> */
    use HasFactory;

    protected $fillable = [
        'visit_id',
        'drug_id',
        'frequency',
        'duration',
    ];

    /**
     * @return BelongsTo<Visit, $this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * @return BelongsTo<Drug, $this>
     */
    public function drug(): BelongsTo
    {
        return $this->belongsTo(Drug::class);
    }
}
