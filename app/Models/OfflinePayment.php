<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OfflinePayment extends Model
{
    protected $fillable = [
        'company_id',
        'name',
        'code',
        'show_to_customer',
        'order',
        'description'
    ];
}
