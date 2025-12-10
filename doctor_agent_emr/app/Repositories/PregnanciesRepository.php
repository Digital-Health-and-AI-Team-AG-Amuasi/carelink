<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Patient;
use App\Models\Pregnancy;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class PregnanciesRepository
{
    public function createPregnancy(Patient $patient, Carbon $edd): Pregnancy
    {
        return Pregnancy::create([
            'patient_id' => $patient->id,
            'edd' => $edd,
        ]);
    }

    public function updatePregnancy(Patient $patient, Carbon $edd): Pregnancy
    {
        $pregnancy = Pregnancy::wherePatientId($patient->id)->latest()->first();

        if ($pregnancy) {
            $pregnancy->update([
                'edd' => $edd,
            ]);
        } else {
            $pregnancy = $this->createPregnancy($patient, $edd);
        }

        return $pregnancy;
    }

    /**
     * @param int|null|null $limit
     *
     * @return Collection<int, Pregnancy>
     */
    public function listWithoutPagination(int|null $limit = null): Collection
    {
        return Pregnancy::query()
            ->when($limit, static function (Builder $query) use ($limit) {
                $query->limit((int) $limit);
            })
            ->get();
    }

    public function find(int $id): Pregnancy|null
    {
        return Pregnancy::find($id);
    }
}
