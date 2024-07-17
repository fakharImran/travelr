<?php

namespace App\Models;

use App\Models\MerchandiserTimeSheet;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TimeSheetRecord extends Model
{
    use HasFactory;
    protected $table= 'time_sheet_records';
    protected $fillable= ['time_sheet_id', 'date', 'time', 'status','gps_location'];

    /**
     * Get the timeSheet that owns the TimeSheetRecord
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timeSheet(): BelongsTo
    {
        return $this->belongsTo(MerchandiserTimeSheet::class, 'time_sheet_id');
    }

}
