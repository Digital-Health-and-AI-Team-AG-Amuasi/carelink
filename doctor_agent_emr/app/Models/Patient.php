<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\Patient\PatientRecord;
use Carbon\Carbon;
use Database\Factories\PatientFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $first_name
 * @property string $last_name
 * @property string $phone
 * @property string $lhims_number
 * @property string|null $notes
 * @property string|null $address
 * @property \Illuminate\Support\Carbon|null $dob
 * @property \Illuminate\Support\Carbon|null $edd
 * @property string $gender
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static PatientFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereDob($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereFirstName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereGender($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereLastName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereLhimsNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereNotes($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient wherePhone($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Patient withoutTrashed()
 *
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Pregnancy> $pregnancies
 * @property-read int|null $pregnancies_count
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Visit> $visits
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Condition> $conditions
 * @property-read \Illuminate\Database\Eloquent\Collection<int, PatientRecord> $patientRecords
 * @property-read int|null $visits_count
 * @property-read int|null $conditions_count
 *
 * @mixin \Eloquent
 */
class Patient extends Model
{
    /** @use HasFactory<PatientFactory> */
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'first_name',
        'last_name',
        'phone',
        'lhims_number',
        'notes',
        'address',
        'dob',
        'gender',
        'religion',
        'marital_status',
        'nhis_status',
        'occupation',
        'medical_history',
        'drug_history',
        'obstetric_history',
        'social_history',
    ];

    protected $casts = [
        'dob' => 'date',
        'medical_history' => 'array',
        'drug_history' => 'array',
        'obstetric_history' => 'array',
        'social_history' => 'array',
    ];

    /**
     * @return HasMany<Pregnancy, $this>
     */
    public function pregnancies(): HasMany
    {
        return $this->hasMany(Pregnancy::class);
    }

    /**
     * @return HasManyThrough<Visit, Pregnancy, $this>
     */
    public function visits(): HasManyThrough
    {
        return $this->hasManyThrough(Visit::class, Pregnancy::class);
    }

    public function dateOfBirthForHumans(): string
    {
        return $this->dob?->format('d/m/Y') ?? '';
    }

    /**
     * @return HasMany<Condition, $this>
     */
    public function conditions(): HasMany
    {
        return $this->hasMany(Condition::class);
    }

    /**
     * @return HasMany<PatientRecord, $this>
     */
    public function patientRecords(): HasMany
    {
        return $this->hasMany(PatientRecord::class);
    }

    /**
     * @return HasMany<Reminder, $this>
     */
    public function reminders(): HasMany
    {
        return $this->hasMany(Reminder::class);
    }

    public function getEddAttribute(): Carbon|null
    {
        return $this->pregnancies()->latest()->first()?->edd;
    }

}
