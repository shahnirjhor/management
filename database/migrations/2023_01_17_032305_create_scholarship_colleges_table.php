<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateScholarshipCollegesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('scholarship_colleges', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->default('1');
            $table->string('name');
            $table->enum('college_type', ['Govt.', 'Govt. Aided', 'Private'])->nullable();
            $table->string('scholarship_village_id')->nullable();
            $table->string('district')->nullable();
            $table->string('email')->nullable();
            $table->text('website')->nullable();
            $table->text('description')->nullable();
            $table->string('picture')->nullable();
            $table->boolean('status');
            $table->index('company_id','type');
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
        Schema::dropIfExists('scholarship_colleges');
    }
}
