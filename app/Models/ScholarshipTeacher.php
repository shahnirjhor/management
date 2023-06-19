<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScholarshipTeacher extends Model
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
        'name',
        'email',
        'school_or_college',
        'scholarship_school_id',
        'scholarship_college_id',
        'phone',
        'address',
        'photo',
        'locale',
        'date_of_birth',
        'gender',
        'blood_group',
        'status'
    ];

    public function school()
    {
        return $this->belongsTo(ScholarshipSchool::class);
    }

    public function college()
    {
        return $this->belongsTo(ScholarshipCollege::class);
    }
}
