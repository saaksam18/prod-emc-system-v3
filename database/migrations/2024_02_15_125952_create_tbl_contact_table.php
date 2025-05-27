<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tbl_contact', function (Blueprint $table) {
            $table->id();
            $table->string('customerID');
            $table->string('pre_contactType')->default(null)->nullable();
            $table->string('pre_contactDetail')->default(null)->nullable();
            $table->string('contactType');
            $table->string('contactDetail');
            $table->string('is_Active')->default(0);
            $table->integer('userID');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_contact');
    }
};
