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
        Schema::create('tbl_motorInfor', function (Blueprint $table) {
            $table->integer('motorID', true);
            $table->integer('motorno')->unique();
            $table->string('year')->default(NULL)->nullable();
            $table->string('plateNo')->default(NULL)->nullable();
            $table->string('engineNo')->default(NULL)->nullable();
            $table->string('chassisNo')->default(NULL)->nullable();
            $table->string('motorColor')->default(NULL)->nullable();
            $table->string('motorType')->default(NULL)->nullable();
            $table->string('motorModel')->default(NULL)->nullable();
            $table->date('purchaseDate')->default(NULL)->nullable();
            $table->boolean('motorStatus')->default(NULL)->nullable();
            $table->integer('compensationPrice')->default(NULL)->nullable();
            $table->integer('totalPurchasePrice')->default(NULL)->nullable();
            $table->integer('customerID')->nullable();
            $table->string('is_Active')->default(1);
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
        Schema::dropIfExists('tbl_motorInfor');
    }
};
