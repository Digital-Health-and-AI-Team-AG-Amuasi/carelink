<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Drug;
use Illuminate\Database\Eloquent\Collection;

class DrugsRepository
{
    /**
     * @return Collection<int, Drug>
     */
    public function listWithoutPagination(): Collection
    {
        return Drug::get();
    }
}
