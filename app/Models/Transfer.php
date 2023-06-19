<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Akaunting\Money\Money;
use Akaunting\Money\Currency;

class Transfer extends Model
{
    protected $fillable = [
        'company_id',
        'payment_id',
        'revenue_id'
    ];

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function paymentAccount()
    {
        return $this->belongsTo(Account::class, 'payment.account_id', 'id');
    }

    public function revenue()
    {
        return $this->belongsTo(Revenue::class);
    }

    public function revenueAccount()
    {
        return $this->belongsTo(Account::class, 'revenue.account_id', 'id');
    }

    public function getDivideConvertedAmount($format = false)
    {
        return $this->divide($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function getDynamicConvertedAmount($format = false)
    {
        return $this->dynamicConvert($this->default_currency_code, $this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function divide($amount, $code, $rate, $format = false)
    {
        if ($format) {
            $money = Money::$code($amount, true)->divide((double) $rate)->format();
        } else {
            $money = Money::$code($amount)->divide((double) $rate)->getAmount();
        }

        return $money;
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
