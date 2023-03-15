<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;

/**
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property Carbon $date
 * @property string $start_time
 * @property string $title
 * @property string $description
 * @property string $end_time
 * @property-read Schedule|null $schedule
 * @property int $schedule_id
 */
class TimeSlot extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'schedule_id',
        'title',
        'start_time',
        'end_time',
        'description'
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
