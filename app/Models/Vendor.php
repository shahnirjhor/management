<?php

namespace App\Models;

use Session;
use Akaunting\Money\Money;
use Akaunting\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Model
{
    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'email',
        'tax_number',
        'phone',
        'address',
        'website',
        'currency_code',
        'enabled',
        'reference'
    ];

    public $sortable = [
        'name',
        'email',
        'phone',
        'enabled'
    ];

    public function bills()
    {
        return $this->hasMany(Bill::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function getUnpaidAttribute()
    {
        $amount = 0;

        $bills = $this->bills()->accrued()->notPaid()->get();

        foreach ($bills as $bill) {
            $bill_amount = $bill->amount - $bill->paid;

            $amount += $this->dynamicConvert($company->default_currency, $bill_amount, $bill->currency_code, $bill->currency_rate, false);
        }

        return $amount;
    }

    public function dynamicConvert($default, $amount, $code, $rate, $format = false)
    {
        $code = new Currency($code);
        if ($format) {
            $money = Money::$default($amount, true)->convert($code, (double) $rate)->format();
        } else {
            $money = Money::$default($amount)->convert($code, (double) $rate)->getAmount();
        }
        return $money;
    }

}
