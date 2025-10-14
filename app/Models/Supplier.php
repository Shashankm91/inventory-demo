<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'contact_person',
        'address',
        'notes',
    ];

    // optional: link to products if you add supplier_id to products
    // public function products()
    // {
    //     return $this->hasMany(Product::class);
    // }
}
