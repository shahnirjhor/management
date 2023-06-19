<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'name',
        'type',
        'color',
        'enabled'
    ];

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function scopeType($query, $type)
    {
        return $query->whereIn('type', (array) $type);
    }

    public function scopeTransfer($query)
    {
        return $query->where('type', 'other')->pluck('id')->first();
    }
}
