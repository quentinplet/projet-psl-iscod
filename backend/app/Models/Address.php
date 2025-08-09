<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    /** @use HasFactory<\Database\Factories\AddressFactory> */
    use HasFactory;

    protected $fillable = [
        'street',
        'city',
        'postal_code',
        'country',
        'region',
        'additional_info',
    ];

    public function retailStore()
    {
        return $this->hasOne(RetailStore::class);
    }
}
