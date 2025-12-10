<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\VitalType;
use Illuminate\Database\Eloquent\Collection;

class VitalTypesRepository
{
    /**
     * @return Collection<int, VitalType>
     */
    public function listWithoutPagination(): Collection
    {
        return VitalType::all();
    }
}
