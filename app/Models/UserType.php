<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property int $id
 * @property string $name
 * @property-read UserSetting|null $userSettings
 * @property-read UserSetting|null $userSetting
 */
class UserType extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = [
        'id',
        'name',
    ];

    public function userSetting(): HasOne
    {
        return  $this->hasOne(UserSetting::class);
    }
}
