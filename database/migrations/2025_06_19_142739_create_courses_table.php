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
        Schema::create('courses', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('course_code')->unique();
            $table->string('instructor');
            $table->string('image')->nullable();
            $table->boolean('is_free')->default(false);
            $table->boolean('is_instructor_led')->default(false);
            $table->text('description');
            $table->string('duration')->nullable();
            $table->string('level')->nullable();
            $table->json('topics')->nullable();
            $table->string('syllabus_pdf')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('courses');
    }
};
