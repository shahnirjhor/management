<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    use HasFactory;
    protected $appends = ['label'];

    protected $fillable = [
        'company_id',
        'name',
        'code'
    ];

    public function getLabelAttribute()
    {
        switch ($this->code) {
            case 'paid':
                $label = 'bg-aqua';
                break;
            case 'delete':
                $label = 'label-danger';
                break;
            case 'partial':
            case 'sent':
                $label = 'label-warning';
                break;
            default:
                $label = 'bg-aqua';
                break;
        }
        return $label;
    }
}
