<?php

namespace App\Models;

use Session;
use Akaunting\Money\Money;
use Akaunting\Money\Currency;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
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

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function revenues()
    {
        return $this->hasMany(Revenue::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function onCloning($src, $child = null)
    {
        $this->user_id = null;
    }

    public function getUnpaidAttribute()
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();

        $amount = 0;

        $invoices = $this->invoices()->accrued()->notPaid()->get();

        foreach ($invoices as $invoice) {
            $invoice_amount = $invoice->amount - $invoice->paid;

            $amount += $this->dynamicConvert($company->default_currency, $invoice_amount, $invoice->currency_code, $invoice->currency_rate, false);
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
