<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->default('1');
            $table->bigInteger('user_id');
            $table->string('full_name')->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('house_no')->nullable();
            $table->bigInteger('scholarship_village_id')->nullable();
            $table->string('street')->nullable();
            $table->string('post_office')->nullable();
            $table->string('taluk')->nullable();
            $table->string('district')->nullable();
            $table->string('pincode')->nullable();
            $table->string('state')->nullable();
            $table->string('contact_no_1')->nullable();
            $table->string('contact_no_2')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('age')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();
            $table->string('aadhar_no')->nullable();
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
        Schema::dropIfExists('student_details');
    }
}
