<?php

namespace App\Models;

use Akaunting\Money\Money;
use Akaunting\Money\Currency as AkCurrency;
use App\Models\Currency;
use App\Traits\DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Session;

class Invoice extends Model
{
    use HasFactory, DateTime, SoftDeletes;

    protected $appends = ['paid'];

    protected $fillable = [
        'company_id',
        'invoice_number',
        'order_number',
        'invoice_status_code',
        'invoiced_at',
        'due_at',
        'amount',
        'currency_code',
        'currency_rate',
        'customer_id',
        'customer_name',
        'customer_email',
        'customer_tax_number',
        'customer_phone',
        'customer_address',
        'notes',
        'category_id',
        'parent_id',
        'attachment',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function items()
    {
        return $this->hasMany(InvoiceItem::class);
    }

    public function item_taxes()
    {
        return $this->hasMany(InvoiceItemTax::class);
    }

    public function histories()
    {
        return $this->hasMany(InvoiceHistory::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayment::class);
    }

    public function status()
    {
        return $this->belongsTo(InvoiceStatus::class, 'invoice_status_code', 'code');
    }

    public function totals()
    {
        return $this->hasMany(InvoiceTotal::class);
    }

    public function scopeDue($query, $date)
    {
        return $query->whereDate('due_at', '=', $date);
    }

    public function scopeLatest($query)
    {
        return $query->orderBy('paid_at', 'desc');
    }

    public function scopeAccrued($query)
    {
        return $query->where('invoice_status_code', '<>', 'draft');
    }

    public function scopePaid($query)
    {
        return $query->where('invoice_status_code', '=', 'paid');
    }

    public function scopeNotPaid($query)
    {
        return $query->where('invoice_status_code', '<>', 'paid');
    }

    public function setAmountAttribute($value)
    {
        $this->attributes['amount'] = (double) $value;
    }

    public function setCurrencyRateAttribute($value)
    {
        $this->attributes['currency_rate'] = (double) $value;
    }

    public function getDiscountAttribute()
    {
        $percent = 0;

        $discount = $this->totals()->where('code', 'discount')->value('amount');

        if ($discount) {
            $sub_total = $this->totals()->where('code', 'sub_total')->value('amount');

            $percent = number_format((($discount * 100) / $sub_total), 0);
        }

        return $percent;
    }

    public function getAmountWithoutTaxAttribute()
    {
        $amount = $this->amount;

        $this->totals()->where('code', 'tax')->each(function ($tax) use(&$amount) {
            $amount -= $tax->amount;
        });

        return $amount;
    }

    public function getPaidAttribute()
    {
        $paid = 0;
        if ($this->payments->count()) {
            $currencies = Currency::where('enabled', 1)->pluck('name', 'code');
            foreach ($this->payments as $item) {
                if ($this->currency_code == $item->currency_code) {
                    $amount = (double) $item->amount;
                } else {
                    $default_model = new InvoicePayment();
                    $default_model->default_currency_code = $this->currency_code;
                    $default_model->amount = $item->amount;
                    $default_model->currency_code = $item->currency_code;
                    $default_model->currency_rate = $currencies[$item->currency_code];
                    $default_amount = (double) $default_model->getDivideConvertedAmount();

                    $convert_model = new InvoicePayment();
                    $convert_model->default_currency_code = $item->currency_code;
                    $convert_model->amount = $default_amount;
                    $convert_model->currency_code = $this->currency_code;
                    $convert_model->currency_rate = $currencies[$this->currency_code];
                    $amount = (double) $convert_model->getDynamicConvertedAmount();
                }
                $paid += $amount;
            }
        }
        return $paid;
    }

    public function getConvertedAmount($format = false)
    {
        return $this->convert($this->amount, $this->currency_code, $this->currency_rate, $format);
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

    public function convert($amount, $code, $rate, $format = false)
    {
        $company = Company::findOrFail(Session::get('company_id'));
        $company->setSettings();


        $default = new AkCurrency($company->default_currency);

        if ($format) {
            $money = Money::$code($amount, true)->convert($default, (double) $rate)->format();
        } else {
            $money = Money::$code($amount)->convert($default, (double) $rate)->getAmount();
        }

        return $money;
    }
}
