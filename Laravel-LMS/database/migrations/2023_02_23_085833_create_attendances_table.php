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
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('levelSection_id');
            $table->foreign('levelSection_id')->references('id')->on('level_sections')->onDelete('cascade');
            

            $table->unsignedBigInteger('studentId');
            $table->foreign('studentId')->references('id')->on('user_l_m_s')->onDelete('cascade'); 



            $table->string('status');
            $table->date('date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('attendaces');
    }
};
