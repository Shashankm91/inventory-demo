<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable = [
        'invoice_no','customer_name','invoice_date','subtotal','tax','discount','total','created_by'
    ];

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public static function generateInvoiceNo()
    {
        // Simple unique invoice no for demo: INV-YYYYMMDD-<id>
        $date = now()->format('Ymd');
        $next = (self::max('id') ?? 0) + 1;
        return 'INV-' . $date . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
