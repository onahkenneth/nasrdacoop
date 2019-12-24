<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLedgersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ledgers', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('staff_id');
            $table->integer('pay_point')->comment('members pay point');
            $table->string('date')->nullable();
            $table->string('ref')->nullable();
            $table->date('loan_date')->nullable();
            $table->date('deposit_date')->nullable();
            $table->date('withdrawal_date')->nullable();
            $table->double('savings_dr', 15,2)->default(0.00);
            $table->double('savings_cr', 15,2)->default(0.00);
            $table->double('savings_bal', 15,2)->default(0.00);
            $table->double('long_term_dr', 15,2)->default(0.00);
            $table->double('long_term_cr', 15,2)->default(0.00);
            $table->double('long_term_bal', 15,2)->default(0.00);
            $table->double('short_term_dr', 15,2)->default(0.00);
            $table->double('short_term_cr', 15,2)->default(0.00);
            $table->double('short_term_bal', 15,2)->default(0.00);
            $table->double('commodity_dr', 15,2)->default(0.00);
            $table->double('commodity_cr', 15,2)->default(0.00);
            $table->double('commodity_bal', 15,2)->default(0.00);
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
        Schema::dropIfExists('ledgers');
    }
}
