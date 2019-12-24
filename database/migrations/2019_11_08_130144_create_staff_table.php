<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('pf')->unique()->nullable();
            $table->integer('ippis')->unique();
            $table->integer('center_id')->nullable();
            $table->integer('pay_point')->nullable();
            $table->string('sbu')->nullable();
            $table->string('title')->nullable();
            $table->string('fname')->nullable();
            $table->string('lname')->nullable();
            $table->string('full_name')->nullable();
            $table->string('slug'); 
            $table->string('email')->nullable();
            $table->string('username')->nullable();
            $table->string('phone')->nullable();
            $table->string('coop_no')->nullable();
            $table->string('nok_name')->nullable();
            $table->string('nok_phone')->nullable();
            $table->text('nok_address')->nullable();
            $table->string('nok_rship')->nullable();
            $table->boolean('is_active')->default(1);
            $table->dateTime('activation_date')->nullable();
            $table->dateTime('deactivation_date')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
