<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Statusroute extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function vehicleroutes(): HasMany
    {
        return $this->hasMany(Vehicleroutes::class);
    }
}
