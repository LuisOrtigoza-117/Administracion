<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP TABLE IF EXISTS temp_tasks");
        DB::statement("CREATE TABLE temp_tasks AS SELECT * FROM tasks");
        DB::statement("DROP TABLE tasks");
        DB::statement("CREATE TABLE tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            group_ids TEXT,
            title VARCHAR NOT NULL,
            description TEXT,
            due_date DATE NOT NULL,
            max_points DECIMAL(5, 2) DEFAULT 10,
            attachments TEXT,
            created_at DATETIME,
            updated_at DATETIME
        )");
        
        DB::statement("INSERT INTO tasks (id, group_ids, title, description, due_date, max_points, attachments, created_at, updated_at) 
            SELECT id, '[' || group_id || ']', title, description, due_date, max_points, attachments, created_at, updated_at 
            FROM temp_tasks WHERE group_id IS NOT NULL");
        
        DB::statement("DROP TABLE temp_tasks");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS temp_tasks");
        DB::statement("CREATE TABLE temp_tasks AS SELECT * FROM tasks");
        DB::statement("DROP TABLE tasks");
        DB::statement("CREATE TABLE tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            group_id INTEGER,
            title VARCHAR NOT NULL,
            description TEXT,
            due_date DATE NOT NULL,
            max_points DECIMAL(5, 2) DEFAULT 10,
            attachments TEXT,
            created_at DATETIME,
            updated_at DATETIME,
            FOREIGN KEY(group_id) REFERENCES groups(id) ON DELETE CASCADE
        )");
        
        DB::statement("INSERT INTO tasks (id, group_id, title, description, due_date, max_points, attachments, created_at, updated_at) 
            SELECT id, json(group_ids), title, description, due_date, max_points, attachments, created_at, updated_at 
            FROM temp_tasks");
        
        DB::statement("DROP TABLE temp_tasks");
    }
};
