<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('company_id')->default('1');
            $table->bigInteger('user_id');
            $table->string('name')->nullable();
            $table->string('year')->nullable();
            $table->enum('school_or_college',['1','2'])->default('1');
            $table->bigInteger('scholarship_school_id')->nullable();
            $table->bigInteger('scholarship_college_id')->nullable();
            $table->string('scholarship_village_id')->nullable();
            $table->string('amount')->nullable();
            $table->enum('status',['1','2'])->default('1');
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
        Schema::dropIfExists('expenses');
    }
}
