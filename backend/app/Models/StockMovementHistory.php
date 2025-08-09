<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StockMovementHistory extends Model
{
    protected $fillable = [
        'product_id',
        'quantity',
        'movement_type', // e.g., 'in', 'out'
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * Get the product associated with the stock movement history.
     */
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Scope a query to only include movements of a given type.
     */
    public function scopeOfType($query, $type)
    {
        return $query->where('movement_type', $type);
    }

    /**
     * Scope a query to filter movements by product.
     */
    public function scopeForProduct($query, $productId)
    {
        return $query->where('product_id', $productId);
    }
}
