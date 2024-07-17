<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Company;
use App\Models\Product;
use App\Models\Category;
use App\Models\CompanyUser;
use App\Models\StoreLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PriceAudit extends Model
{
    use HasFactory;
    protected $table= 'price_audits';
    protected $fillable= ['store_location_id','store_id', 'company_user_id', 'category_id', 'product_id', 'Product_SKU', 'product_store_price', 'tax_in_percentage', 'competitor_product_name', 'competitor_product_price','competitor_product_tax' ,'notes'];

   
    public function storeLocation(): BelongsTo
    {
        return $this->belongsTo(StoreLocation::class, 'store_location_id');
    }
    
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
   
    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

        
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
