<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMonthlySavingsPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monthly_savings_payments', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('ippis');
            $table->integer('pay_point')->comment('members pay point');
            $table->integer('monthly_saving_id');
            $table->string('ref')->nullable();
            $table->date('withdrawal_date')->nullable();
            $table->date('deposit_date')->nullable();
            $table->double('dr', 15,2)->default(0.00);
            $table->double('cr', 15,2)->default(0.00);
            $table->double('bal', 15,2)->default(0.00);
            $table->string('month');
            $table->string('year');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('monthly_savings_payments');
    }
}
