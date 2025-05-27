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
        Schema::create('tbl_rental', function (Blueprint $table) {
            $table->integer('rentalID', true);
            $table->unsignedBigInteger('customerID');
            $table->unsignedBigInteger('motorID');
            $table->string('transactionType');
            $table->datetime('rentalDay');
            $table->datetime('returnDate');
            $table->datetime('commingDate')->nullable();
            $table->integer('rentalPeriod');
            $table->integer('price')->nullable();
            $table->unsignedBigInteger('staff_id');
            $table->integer('userID');
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
        Schema::dropIfExists('tbl_rental');
    }
};
