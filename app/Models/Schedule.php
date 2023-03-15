<?php

namespace App\Models;


use Carbon\Traits\Date;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 *
 * @property-read Collection<int, TimeSlot> $TimeSlot
 * @property-read int|null $time_slot_count
 * @property User|null $User
 * @property-read Collection<int, TimeSlot> $timeSlot
 * @property User|null $user
 * @property Date $date
 */
class Schedule extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        "id",
        "user_id",
        "date",
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function timeSlots(): HasMany
    {
        return $this->hasMany(TimeSlot::class);
    }
}
