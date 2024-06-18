<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Route extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicleroute(): BelongsTo
    {
        return $this->belongsTo(Vehicleroutes::class);
    }
}
