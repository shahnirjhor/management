<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->integer('company_id');
            $table->integer('category_id')->default();
            $table->string('invoice_number');
            $table->string('order_number')->nullable();
            $table->string('invoice_status_code');
            $table->dateTime('invoiced_at');
            $table->dateTime('due_at');
            $table->double('amount', 15, 4);
            $table->string('currency_code');
            $table->double('currency_rate', 15, 8);
            $table->integer('parent_id')->default(0);
            $table->integer('customer_id');
            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_tax_number')->nullable();
            $table->string('customer_phone')->nullable();
            $table->text('customer_address')->nullable();
            $table->text('notes')->nullable();
            $table->string('attachment')->nullable();
            $table->timestamps();
            $table->softDeletes();
            $table->index('company_id');
            $table->unique(['company_id', 'invoice_number', 'deleted_at']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('invoices');
    }
}
