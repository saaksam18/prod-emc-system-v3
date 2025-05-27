<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tbl_exchange_motor', function (Blueprint $table) {
            $table->integer('motoExchangeID', true);
            $table->integer('customerID');
            $table->integer('preMotoID');
            $table->integer('currMotorID');
            $table->string('comment');
            $table->unsignedBigInteger('staff_id');
            $table->integer('userID');
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
        Schema::dropIfExists('tbl_exchange_motor');
    }
};
