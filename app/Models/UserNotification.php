<?php

namespace App\Models;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserNotification extends Model
{
    use HasFactory;
    protected $fillable= ['notification_id','user_id'];


    // /**
    //  * Get all of the notifications for the UserNotification
    //  *
    //  * @return HasMany
    //  */
    // public function notifications(): HasMany
    // {
    //     return $this->hasMany(Notification::class, 'id', 'notification_id');
    // }
    /**
     * Get the notifications associated with the UserNotification
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function notification(): HasOne
    {
        return $this->hasOne(Notification::class, 'id', 'notification_id');
    }
}
