<?php

namespace App\Models;

use App\Models\Store;
use App\Models\CompanyUser;
use App\Models\StoreLocation;
use App\Models\UserNotification;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Notification extends Model
{
    use HasFactory;
    protected $table= 'notifications';
    protected $fillable= ['store_id','user_ids','store_location_id','title', 'message', 'attachment'];

    public function userNames()
    {
        $arr = array();
        foreach (json_decode($this->user_ids) as $key => $value) {
            
            array_push($arr, User::select('name')->where('id',$value)->first());
        }
        return ($arr);
        // return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function storeLocation(): BelongsTo
    {
        return $this->belongsTo(StoreLocation::class, 'store_location_id');
    }

    /**
     * Get the userNotification that owns the Notification
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userNotification(): BelongsTo
    {
        return $this->belongsTo(UserNotification::class, 'notification_id');
    }

}