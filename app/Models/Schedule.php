<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property-read Collection<int, TimeSlot> $TimeSlot
 * @property-read int|null $time_slot_count
 * @property-read User|null $User
 * @property-read Collection<int, TimeSlot> $timeSlot
 * @property-read User|null $user
 */
class Schedule extends Model
{
    use HasFactory;

    protected $fillable = [
        "id",
        'user_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeSlot(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
