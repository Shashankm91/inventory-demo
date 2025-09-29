<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'name', 'category', 'unit', 'price', 'stock_quantity', 'image','cost_price','selling_price'
    ];

    public function stockMovements()
    {
        return $this->hasMany(StockMovement::class);
    }

    public function invoiceItems()
    {
        return $this->hasMany(InvoiceItem::class);
    }
}
