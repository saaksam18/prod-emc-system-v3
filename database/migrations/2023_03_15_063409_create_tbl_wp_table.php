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
        Schema::create('tbl_wp', function (Blueprint $table) {
            $table->integer('wpID', true);
            $table->unsignedBigInteger('customerID');
            $table->datetime('wpExpireDate');
            $table->datetime('wpRemindDate');
            $table->unsignedBigInteger('staff_id');
            $table->integer('userID');
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
        Schema::dropIfExists('tbl_wp');
    }
};
