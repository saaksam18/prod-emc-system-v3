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
        Schema::create('tbl_visa', function (Blueprint $table) {
            $table->integer('visaID', true);
            $table->unsignedBigInteger('customerID');
            $table->integer('amount')->default(0)->nullable();
            $table->string('visaType')->default(NULL)->nullable();
            $table->datetime('expireDate');
            $table->datetime('remindDate');
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('userID');
            $table->boolean('is_Active')->default(0);
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
        Schema::dropIfExists('tbl_visa');
    }
};
