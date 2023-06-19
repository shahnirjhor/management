<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceItemTax extends Model
{
    use HasFactory;

    protected $fillable = [
        'company_id',
        'invoice_id',
        'invoice_item_id',
        'tax_id',
        'name',
        'amount'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }
}
