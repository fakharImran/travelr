<?php

namespace App\Models;

use App\Models\User;
use App\Models\Dispatch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Driver extends Model
{
    protected $fillable = ['first_name', 'last_name'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function dispatches()
    {
        return $this->hasMany(Dispatch::class);
    }
}
