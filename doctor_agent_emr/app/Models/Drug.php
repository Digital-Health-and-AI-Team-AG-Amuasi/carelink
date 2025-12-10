<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property \Illuminate\Support\Carbon|null $deleted_at
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug whereDeletedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug withTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|Drug withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Drug extends Model
{
    use SoftDeletes;
}
