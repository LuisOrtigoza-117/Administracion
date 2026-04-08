<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropForeign(['group_id']);
            $table->dropUnique(['group_id', 'day', 'hour_number']);
            $table->json('group_ids')->nullable()->after('group_id');
            $table->string('combined_groups')->nullable()->after('group_ids');
            $table->foreignId('group_id')->change();
        });
    }

    public function down(): void
    {
        Schema::table('schedules', function (Blueprint $table) {
            $table->dropColumn(['group_ids', 'combined_groups']);
            $table->dropForeign(['group_id']);
            $table->unique(['group_id', 'day', 'hour_number']);
            $table->foreignId('group_id')->change();
        });
    }
};
