<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillItemTax extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'bill_id',
        'bill_item_id',
        'tax_id',
        'name',
        'amount'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
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
