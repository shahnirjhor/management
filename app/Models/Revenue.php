<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\DateTime;
use Akaunting\Money\Money;
use Session;
use Akaunting\Money\Currency;

class Revenue extends Model
{
    use DateTime;
    protected $fillable = [
        'company_id',
        'account_id',
        'paid_at',
        'amount',
        'currency_code',
        'currency_rate',
        'customer_id',
        'description',
        'category_id',
        'payment_method',
        'reference',
        'attachment',
        'reconciled',
        'parent_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'customer_id', 'id');
    }

    public function account()
    {
        return $this->belongsTo(Account::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function transfers()
    {
        return $this->hasMany(Transfer::class);
    }

    public function scopeIsTransfer($query)
    {
        return $query->where('category_id', '=', Category::transfer());
    }

    public function scopeIsNotTransfer($query)
    {
        return $query->where('category_id', '<>', Category::transfer());
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
    }

    public function convert($amount, $code, $rate, $format = false)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();


        $default = new Currency($company->default_currency);

        if ($format) {
            $money = Money::$code($amount, true)->convert($default, (double) $rate)->format();
        } else {
            $money = Money::$code($amount)->convert($default, (double) $rate)->getAmount();
        }

        return $money;
    }

}
