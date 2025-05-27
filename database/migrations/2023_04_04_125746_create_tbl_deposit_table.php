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
        Schema::create('tbl_deposit', function (Blueprint $table) {
            $table->integer('depositID', true);
            $table->unsignedBigInteger('customerID');
            $table->unsignedBigInteger('rentalID');
            $table->string('preDepositType')->default(null)->nullable();
            $table->string('preDeposit')->default(null)->nullable();
            $table->string('currDepositType');
            $table->string('currDeposit');
            $table->string('comment')->default(null)->nullable();
            $table->unsignedBigInteger('staff_id');
            $table->unsignedBigInteger('userID');
            $table->boolean('is_Active')->default(1);
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
        Schema::dropIfExists('tbl_deposit');
    }
};
