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
        Schema::create('countries', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('num_code')->unsigned()->default(0);
            $table->string('alpha_2_code', 2)->nullable();
            $table->string('alpha_3_code', 3)->nullable();
            $table->string('en_short_name', 52)->nullable();
            $table->string('nationality', 39)->nullable();
            $table->unique(['alpha_2_code', 'alpha_3_code']);
            $table->timestamps();
          });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
