<?php

namespace App\Models;

use App\Enums\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetailStore extends Model
{
    /** @use HasFactory<\Database\Factories\RetailStoreFactory> */
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'phone',
        'email',
        'description',
        'address_id'
    ];

    public function address()
    {
        return $this->belongsTo(Address::class);
    }

    public function customers()
    {
        return $this->hasMany(User::class)->where('role', UserRole::CUSTOMER->value);
    }

    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where('name', 'like', '%' . $search . '%');
        }
        return $query;
    }
}
