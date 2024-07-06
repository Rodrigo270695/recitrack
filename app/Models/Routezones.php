<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RouteZones extends Model
{
    use HasFactory;

    protected $table = 'routezones';

    protected $guarded = [];

    public function route(): BelongsTo
    {
        return $this->belongsTo(Route::class);
    }

    public function zone(): BelongsTo
    {
        return $this->belongsTo(Zone::class);
    }


}
