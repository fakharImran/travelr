<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Dispatch extends Model
{
    use HasFactory;

    protected $table= 'dispatches';
    protected $fillable= ['driver_id','pick_up_address', 'drop_off_address', 'phone_no', 'fare','send_button','time_away','status'];

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }
}

