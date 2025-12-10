<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Patient;
use App\Models\Visit;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;

class VisitsRepository
{
    /**
     * @param Patient $patient
     * @param int|null|null $limit
     *
     * @return LengthAwarePaginator<int, Visit>
     */
    public function getVisits(Patient $patient, int|null $limit = null): LengthAwarePaginator
    {
        return $patient->visits()
            ->latest()
            ->when($limit, static fn (Builder $query, int $limit) => $query->limit($limit))
            ->paginate();
    }
}
