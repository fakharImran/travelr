<?php

namespace App\Models;

use App\Models\User;
use App\Models\Company;
use App\Models\Activity;
use App\Models\OutOfStock;
use App\Models\PriceAudit;
use App\Models\Opportunity;
use App\Models\Notification;
use App\Models\MarketingActivity;
use App\Models\StockCountByStores;
use App\Models\ProductExpiryTracker;
use App\Models\MerchandiserTimeSheet;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlanogramComplianceTracker;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CompanyUser extends Model
{
    use HasFactory;
    protected $table= 'company_users';
    protected $fillable= ['company_id', 'user_id' , 'access_privilege', 'last_login_date_time', 'date_modified'];
    public $timestamps = true;

    /**
     * Get the user that owns the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    /**
     * Get the company that owns the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get all of the timeSheets for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function timeSheets(): HasMany
    {
        // return $this->id;
        return $this->hasMany(MerchandiserTimeSheet::class, "company_user_id");
    }
    /**
     * Get all of the notifications for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function notifications(): HasMany
    {
        return $this->hasMany(Notification::class, 'company_user_id');
    }
    /**
     * Get all of the activities for the CompanyUser
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class, 'company_user_id');
    }

    public function stockCountByStores(): HasMany
    {
        return $this->hasMany(StockCountByStores::class, 'store_id');
    }
    public function priceAudits(): HasMany
    {
        return $this->hasMany(PriceAudit::class, 'store_id');
    }
    public function marketingActivities(): HasMany
    {
        return $this->hasMany(MarketingActivity::class, 'store_id');
    }
    public function outOfStocks(): HasMany
    {
        return $this->hasMany(OutOfStock::class, 'store_id');
    }
    public function opportunities(): HasMany
    {
        return $this->hasMany(Opportunity::class, 'store_id');
    }
    public function productExpiryTrackers(): HasMany
    {
        return $this->hasMany(ProductExpiryTracker::class, 'store_id');
    }
    public function planogramComplianceTrackers(): HasMany
    {
        return $this->hasMany(PlanogramComplianceTracker::class, 'store_id');
    }
}
