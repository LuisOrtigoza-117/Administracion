<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->string('brand')->nullable()->after('location');
            $table->string('model')->nullable()->after('brand');
            $table->string('processor')->nullable()->after('model');
            $table->string('ram')->nullable()->after('processor');
            $table->string('storage')->nullable()->after('ram');
            $table->string('operating_system')->nullable()->after('storage');
            $table->string('monitor')->nullable()->after('operating_system');
            $table->string('keyboard')->nullable()->after('monitor');
            $table->string('mouse')->nullable()->after('keyboard');
            $table->text('notes')->nullable()->after('mouse');
            $table->date('purchase_date')->nullable()->after('notes');
        });
    }

    public function down(): void
    {
        Schema::table('computers', function (Blueprint $table) {
            $table->dropColumn([
                'brand', 'model', 'processor', 'ram', 'storage', 
                'operating_system', 'monitor', 'keyboard', 'mouse', 
                'notes', 'purchase_date'
            ]);
        });
    }
};
