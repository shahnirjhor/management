<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Scholarship extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id',
        'company_id',
        'application_no',
        'year',
        'annual_income',
        'percentage_marks_obtained',
        'student_detail_id',
        'school_or_college',
        'scholarship_school_id',
        'scholarship_college_id',
        'marks_obtained_type',
        'marks_obtained',
        'marks_subject',
        'school_year',
        'school_subject',
        'school_grade',
        'school_contact_person',
        'school_contact_number',
        'school_designation',
        'school_seal_signature',
        'further_education_details_school_or_college',
        'further_education_details_scholarship_school_id',
        'further_education_details_scholarship_college_id',
        'further_education_details_course_joined',
        'further_education_details_course_school_college',
        'given_information',
        'any_other_scholarship',
        'scholarship_refunded',
        'date',
        'student_sign',
        'parent_gurdian_sign',
        'place',
        'photo',
        'income_certificate',
        'id_proof',
        'previous_educational_marks_card',
        'original_fee_receipt',
        'fee_amount',
        'apply_amount',
        'bank_passbook',
        'scholarship_bank_detail_id',
        'print_form',
        'status',
        'payment_date'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scholarshipBankDetail()
    {
        return $this->belongsTo(ScholarshipBankDetail::class);
    }

    public function studentDetail()
    {
        return $this->belongsTo(StudentDetail::class);
    }

    public function schoolDetail()
    {
        return $this->belongsTo(ScholarshipSchool::class,'scholarship_school_id');
    }

    public function furtherEducationschollDetail()
    {
        return $this->belongsTo(ScholarshipSchool::class,'further_education_details_scholarship_school_id');
    }

    public function furtherEducationCollegeDetail()
    {
        return $this->belongsTo(ScholarshipCollege::class,'further_education_details_scholarship_college_id');
    }

    public function collegeDetail(){
        return $this->belongsTo(ScholarshipCollege::class, 'scholarship_college_id');
    }

    public function classDetail(){
        return $this->belongsTo(ScholarshipClass::class, 'school_grade');
    }



    public function scholarshipVillage()
    {
        return $this->belongsTo(ScholarshipVillage::class);
    }

}
