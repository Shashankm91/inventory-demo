<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    protected $fillable = [
        'quotation_no',
        'customer_name',
        'customer_email',
        'quotation_date',
        'valid_until',
        'subtotal',
        'tax',
        'discount',
        'total',
        'status',
        'terms',
        'created_by',
        'converted_invoice_id'
    ];

    public function items()
    {
        return $this->hasMany(QuotationItem::class);
    }

    // simple generator for demo
    public static function generateQuotationNo()
    {
        $date = now()->format('Ymd');
        $next = (self::max('id') ?? 0) + 1;
        return 'QT-' . $date . '-' . str_pad($next, 4, '0', STR_PAD_LEFT);
    }
}
