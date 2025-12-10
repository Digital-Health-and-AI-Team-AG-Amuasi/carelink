<?php

declare(strict_types=1);

namespace App\Models;

use Database\Factories\VisitFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property int $pregnancy_id
 * @property int $staff_id The staff who made the entry
 * @property string|null $reason
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\VisitFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit wherePregnancyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereReason($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereStaffId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Visit withoutTrashed()
 *
 * @property-read User $capturedBy
 * @property-read Pregnancy $pregnancy
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Medication> $medications
 * @property-read int|null $medications_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Vital> $vitals
 * @property-read int|null $vitals_count
 *
 * @mixin \Eloquent
 */
class Visit extends Model
{
    use SoftDeletes;
    /** @use HasFactory<VisitFactory> */
    use HasFactory;

    protected $fillable = [
        'pregnancy_id',
        'staff_id',
        'reason',
    ];

    /**
     * @return Patient
     */
    public function patient(): Patient
    {
        return $this->pregnancy->patient;
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function capturedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'staff_id');
    }

    /**
     * @return BelongsTo<Pregnancy, $this>
     */
    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    /**
     * @return HasMany<Medication, $this>
     */
    public function medications(): HasMany
    {
        return $this->hasMany(Medication::class);
    }

    /**
     * @return HasMany<Vital, $this>
     */
    public function vitals(): HasMany
    {
        return $this->hasMany(Vital::class);
    }

    /**
     * @return HasMany<Condition, $this>
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }
}
