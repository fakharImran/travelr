<?php

namespace App\Models;

use App\Models\Store;
use App\Models\Product;
use App\Models\Category;
use App\Models\CompanyUser;
use App\Models\StoreLocation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Activity extends Model
{
    use HasFactory;
    protected $table= 'activities';
    protected $fillable= ['compamy_user_id', 'activity_type', 'activity_description', 'activity_detail'];

    public function companyUser(): BelongsTo
    {
        return $this->belongsTo(CompanyUser::class, 'company_user_id');
    }
    
}
