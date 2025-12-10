<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Condition;
use App\Models\Patient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Str;

class ConditionsRepository
{
    /**
     * @param Patient $patient
     * @param int|null|null $limit
     *
     * @return LengthAwarePaginator<int, Condition>
     */
    public function getConditions(Patient $patient, int|null $limit = null): LengthAwarePaginator
    {
        return $patient->conditions()
            ->latest()
            ->when($limit, static fn (Builder $query, int $limit) => $query->limit($limit))
            ->paginate()
            ->through(static function ($condition) {
                $condition->short_diagnosis = Str::limit($condition->diagnosis, 50);

                return $condition;
            });
    }
}
