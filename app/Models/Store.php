<?php

namespace App\Models;

use App\Models\Company;
use App\Models\Product;
use App\Models\OutOfStock;
use App\Models\PriceAudit;
use App\Models\Opportunity;
use App\Models\StoreLocation;
use App\Models\MarketingActivity;
use App\Models\StockCountByStores;
use App\Models\ProductExpiryTracker;
use App\Models\MerchandiserTimeSheet;
use Illuminate\Database\Eloquent\Model;
use App\Models\PlanogramComplianceTracker;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Store extends Model
{
    use HasFactory;
    protected $table= 'stores';
    protected $fillable= ['company_id', 'name_of_store', 'parish', 'channel'];

    /**
     * Get the company that owns the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }
    /**
     * Get all of the locations for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function locations(): HasMany
    {
        return $this->hasMany(StoreLocation::class, 'store_id');
    }

    // protected static function boot()
    // {
    //     Store::boot();

    //     static::deleting(function($store) {
    //         $store->products()->delete();
    //     });
    // }

    /**
     * Get all of the products for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class, 'store_id');
    }

    /**
     * Get all of the merchandiserTimeSheets for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function merchandiserTimeSheets(): HasMany
    {
        return $this->hasMany(MerchandiserTimeSheet::class, 'store_id');
    }
    /**
     * Get all of the stockCountByStores for the Store
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
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

