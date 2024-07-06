<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Horary extends Model
{
    use HasFactory;
    protected $guarded = [];
    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class);
    }
    public function vehicleoccupant(): BelongsTo
    {
        return $this->belongsTo(Vehicleoccupant::class);
    }
    public function activities(): HasMany
    {
        return $this->hasMany(Activity::class);
    }
}
