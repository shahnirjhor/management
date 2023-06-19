<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarships', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('company_id')->default('1');
            $table->string('application_no')->nullable();
            $table->string('year')->nullable();
            $table->string('annual_income')->nullable();
            $table->string('percentage_marks_obtained')->nullable();
            $table->bigInteger('student_detail_id')->nullable();
            $table->enum('school_or_college',['1','2'])->default('1');
            $table->bigInteger('scholarship_school_id')->nullable();
            $table->bigInteger('scholarship_college_id')->nullable();
            $table->enum('marks_obtained_type', ['SSLC', 'PUC', 'Degree'])->nullable();
            $table->string('marks_obtained')->nullable();
            $table->string('marks_subject')->nullable();
            $table->string('school_year')->nullable();
            $table->string('school_subject')->nullable();
            $table->string('school_grade')->nullable();
            $table->string('school_contact_person')->nullable();
            $table->string('school_contact_number')->nullable();
            $table->string('school_designation')->nullable();
            $table->string('school_seal_signature')->nullable();
            $table->enum('further_education_details_school_or_college',['1','2'])->default('1');
            $table->bigInteger('further_education_details_scholarship_school_id')->nullable();
            $table->bigInteger('further_education_details_scholarship_college_id')->nullable();
            $table->string('further_education_details_course_joined')->nullable();
            $table->string('further_education_details_course_school_college')->nullable();
            $table->enum('given_information', ['1','0'])->default('0');
            $table->enum('any_other_scholarship', ['1','0'])->default('0');
            $table->enum('scholarship_refunded', ['1','0'])->default('0');
            $table->date('date')->nullable();
            $table->string('student_sign')->nullable();
            $table->string('parent_gurdian_sign')->nullable();
            $table->string('place')->nullable();
            $table->string('photo')->nullable();
            $table->string('income_certificate')->nullable();
            $table->string('id_proof')->nullable();
            $table->string('previous_educational_marks_card')->nullable();
            $table->string('original_fee_receipt')->nullable();
            $table->string('fee_amount')->nullable();
            $table->string('apply_amount')->nullable();
            $table->string('bank_passbook')->nullable();
            $table->bigInteger('scholarship_bank_detail_id')->nullable();
            $table->bigInteger('print_form')->nullable();
            $table->enum('status', ['pending','approved','payment_in_progress','payment_done','rejected'])->default('pending');
            $table->date('payment_date')->nullable();
            $table->index('company_id');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('scholarships');
    }
}
