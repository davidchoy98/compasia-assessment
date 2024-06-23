<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductModels extends Model
{
    use HasFactory;

    protected $fillable = [
        'capacity_id',
        'name'
    ];

    public function capacity(): BelongsTo
    {
        return $this->belongsTo(Capacities::class);
    }
}
