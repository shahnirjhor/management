<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipSchool extends Model
{
    use HasFactory;
    use SoftDeletes;


    protected $fillable = [
        'company_id',
        'name',
        'school_type',
        'scholarship_village_id',
        'district',
        'email',
        'website',
        'description',
        'picture',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarshipVillage()
    {
        return $this->belongsTo(ScholarshipVillage::class);
    }

}
