<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicleroutes extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function statusroutes(): HasMany
    {
        return $this->hasMany(Statusroute::class);
    }

    public function routes(): HasMany
    {
        return $this->hasMany(Route::class);
    }
}
