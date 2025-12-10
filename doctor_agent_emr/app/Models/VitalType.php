<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property array<array-key, mixed> $units_of_measurement
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Database\Factories\VitalTypeFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereUnitsOfMeasurement($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|VitalType withoutTrashed()
 *
 * @mixin \Eloquent
 */
class VitalType extends Model
{
    use HasFactory; // @phpstan-ignore-line
    use SoftDeletes;
}
