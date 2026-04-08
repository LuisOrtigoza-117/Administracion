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
        Schema::create('fixed_teacher_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');
            $table->enum('day', ['monday', 'tuesday', 'wednesday', 'thursday', 'friday']);
            $table->integer('hour_number');
            $table->time('start_time');
            $table->time('end_time');
            $table->string('subject'); // Materia que imparte
            $table->string('grade_group'); // Grado y grupo fijo (ej: "1A", "2B")
            $table->boolean('is_locked')->default(true);
            $table->timestamps();
            
            $table->unique(['teacher_id', 'day', 'hour_number']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fixed_teacher_schedules');
    }
};
