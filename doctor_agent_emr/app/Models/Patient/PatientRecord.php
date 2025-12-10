<?php

declare(strict_types=1);

namespace App\Models\Patient;

use App\Models\Patient;
use App\Models\User;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientRecord extends Model
{
    protected $fillable = [
        'patient_id',
        'user_id',
        'visit_id',
        'current_complains',
        'on_direct_questions',
        'issues',
        'updates',
        'on_examinations',
        'vitals',
        'history_presenting_complains',
        'investigations',
        'impression',
        'plan',
    ];

    protected $casts = [
        'vitals' => 'array',
    ];

    /**
     * @return BelongsTo<Patient, $this>
     */
    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    /**
     * @return BelongsTo<Visit, $this>
     */
    public function visit(): BelongsTo
    {
        return $this->belongsTo(Visit::class);
    }

    /**
     * @return BelongsTo<User, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
