<?php

namespace App\Models;

use App\Models\User;
use App\Models\Store;
use App\Models\CompanyUser;
use App\Models\StoreLocation;
use App\Models\TimeSheetRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MerchandiserTimeSheet extends Model
{
    protected $fillable = [
        'store_id',
        'store_manager_name',
        'store_location_id',
        'signature',
        'company_user_id'
    ];

    protected $table = 'merchandiser_time_sheets';
    /**
     * Get the companyUser that owns the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }

    /**
     * Get all of the timeSheetRecords for the MerchandiserTimeSheet
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSheetRecords(): HasMany
    {
        return $this->hasMany(TimeSheetRecord::class, 'time_sheet_id');
    }
   
    public function store($id=null)
    {
        $store= Store::findOrFail($id);
        return $store;
    }

    public function store_location($id=null)
    {
        $store_location= StoreLocation::select('*')->where('id',$id)->first();
        return $store_location;
    }
    
    public function manager($id){
        $user = User::findOrFail($id);
        return $user;
    }
}
