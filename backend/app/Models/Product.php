<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Product extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'reference',
        'quantity',
        'image',
        'image_path',
        'image_mime',
        'image_size',
        'category_id',
        'supplier_id',
    ];

    /**
     * Get the options for generating the slug
     */

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function scopeSearch(Builder $query, $search)
    {
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%')
                ->orWhere('slug', 'like', '%' . $search . '%')
                ->orWhere('reference', 'like', '%' . $search . '%')
                ->orWhere('description', 'like', '%' . $search . '%')
                ->orWhereHas('category', function (Builder $q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                })
                ->orWhereHas('supplier', function (Builder $q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
        }
        return $query;
    }

    public function scopeByCategory(Builder $query, $categoryId = null)
    {
        if (!empty($categoryId) && is_numeric($categoryId)) {
            $query->where('category_id', $categoryId);
        }
        return $query;
    }

    public function scopeBySupplier(Builder $query, $supplierId = null)
    {
        if (!empty($supplierId) && is_numeric($supplierId)) {
            $query->where('supplier_id', $supplierId);
        }
        return $query;
    }
}
