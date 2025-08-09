<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    /** @use HasFactory<\Database\Factories\SupplierFactory> */
    use HasFactory;

    protected $fillable = ['name', 'email', 'phone', 'siret', 'description', 'address_id'];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function products()
    {
        return $this->hasMany(Product::class);
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
