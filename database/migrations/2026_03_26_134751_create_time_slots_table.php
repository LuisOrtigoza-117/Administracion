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
        Schema::create('time_slots', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Ej: "1ª Hora", "Receso", "Comida"
            $table->integer('hour_number'); // 1-10
            $table->time('start_time');
            $table->time('end_time');
            $table->integer('duration_minutes'); // Duración en minutos
            $table->enum('type', ['class', 'recess', 'lunch']); // Tipo: clase, receso, comida
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_slots');
    }
};
