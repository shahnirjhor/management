<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'sku',
        'description',
        'sale_price',
        'purchase_price',
        'quantity',
        'category_id',
        'tax_id',
        'enabled',
        'picture'
    ];

    protected $sortable = [
        'name',
        'category',
        'quantity',
        'sale_price',
        'purchase_price',
        'enabled'
    ];



    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function tax()
    {
        return $this->belongsTo(Tax::class);
    }
}
