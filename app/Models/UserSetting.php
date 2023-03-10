<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;


/**
 * App\Models\UserSetting
 *
 * @property int $id
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read UserType|null $User
 * @property-read UserType|null $UserType
 * @property-read User|null $user
 * @property-read UserType|null $userType
 * @property int $user_id
 * @property int $type_id
 * @property string $timezone
 */
class UserSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'user_id',
        'type_id',
        'timezone',
    ];

    public function userType(): BelongsTo
    {
        return $this->belongsTo(UserType::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }



}
