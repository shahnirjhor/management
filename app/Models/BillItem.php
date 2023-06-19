<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class BillItem extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'bill_id',
        'item_id',
        'name',
        'sku',
        'quantity',
        'price',
        'total',
        'tax',
        'tax_id'
    ];

    public function bill()
    {
        return $this->belongsTo(Bill::class);
    }

    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    public function taxes()
    {
        return $this->hasMany(BillItemTax::class, 'bill_item_id', 'id');
    }

    public function setPriceAttribute($value)
    {
        $this->attributes['price'] = (double) $value;
    }

    public function setTotalAttribute($value)
    {
        $this->attributes['total'] = (double) $value;
    }

    public function setTaxAttribute($value)
    {
        $this->attributes['tax'] = (double) $value;
    }
}
