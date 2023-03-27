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
        Schema::create('user_level_sections', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('levelSection_id');
            $table->foreign('levelSection_id')->references('id')->on('level_sections')->onDelete('cascade');
            
            $table->unsignedBigInteger('teacher_id');
            $table->foreign('teacher_id')->references('id')->on('user_l_m_s')->onDelete('cascade');
            
            $table->unsignedBigInteger('student_id');
            $table->foreign('student_id')->references('id')->on('user_l_m_s')->onDelete('cascade');
            
            $table->unsignedBigInteger('course_id');
            $table->foreign('course_id')->references('id')->on('courses')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_level_sections');
    }
};
