<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Expense extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'company_id',
        'user_id',
        'name',
        'year',
        'school_or_college',
        'scholarship_school_id',
        'scholarship_college_id',
        'scholarship_village_id',
        'amount',
        'status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schoolDetail()
    {
        return $this->belongsTo(ScholarshipSchool::class,'scholarship_school_id');
    }

    public function collegeDetail(){
        return $this->belongsTo(ScholarshipCollege::class, 'scholarship_college_id');
    }

    public function scholarshipVillage()
    {
        return $this->belongsTo(ScholarshipVillage::class, 'scholarship_village_id');
    }
}
