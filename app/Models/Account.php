<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    protected $appends = ['balance'];

    protected $fillable = ['company_id', 'name', 'number', 'currency_code', 'opening_balance', 'bank_name', 'bank_phone', 'bank_address', 'enabled'];

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function invoice_payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function bill_payments()
    {
        return $this->hasMany(BillPayment::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function setOpeningBalanceAttribute($value)
    {
        $this->attributes['opening_balance'] = (double) $value;
    }

    /**
     * Get the current balance.
     *
     * @return string
     */
    public function getBalanceAttribute()
    {
        // Opening Balance
        $total = $this->opening_balance;

        // Sum Incomes
        $total += $this->invoice_payments()->sum('amount') + $this->revenues()->sum('amount');

        // Subtract Expenses
        $total -= $this->bill_payments()->sum('amount') + $this->payments()->sum('amount');

        return $total;
    }

}
