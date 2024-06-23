<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'type_id',
        'brand_id',
        'model_id',
        'quantity'
    ];

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brands::class);
    }

    public function model(): BelongsTo
    {
        return $this->belongsTo(ProductModels::class);
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(ProductTypes::class);
    }
}
