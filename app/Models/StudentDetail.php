<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StudentDetail extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'company_id',
        'user_id',
        'full_name',
        'father_name',
        'father_occupation',
        'mother_name',
        'mother_occupation',
        'house_no',
        'scholarship_village_id',
        'street',
        'post_office',
        'taluk',
        'district',
        'pincode',
        'state',
        'contact_no_1',
        'contact_no_2',
        'date_of_birth',
        'age',
        'gender',
        'aadhar_no'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function  scholarshipVillage()
    {
        return $this->belongsTo(ScholarshipVillage::class);
    }

    public function scholarship()
    {
        return $this->belongsTo(Scholarship::class);
    }
}
