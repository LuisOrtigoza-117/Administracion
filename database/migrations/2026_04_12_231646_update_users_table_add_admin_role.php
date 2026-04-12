<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("DROP TABLE IF EXISTS temp_users");
        DB::statement("CREATE TABLE temp_users AS SELECT * FROM users");
        DB::statement("DROP TABLE users");
        DB::statement("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR NOT NULL,
            email VARCHAR NOT NULL,
            email_verified_at DATETIME,
            password VARCHAR NOT NULL,
            remember_token VARCHAR,
            created_at DATETIME,
            updated_at DATETIME,
            role VARCHAR CHECK (role IN ('admin', 'teacher', 'student')) NOT NULL DEFAULT 'teacher',
            student_id INTEGER,
            phone VARCHAR,
            specialty VARCHAR,
            FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE SET NULL
        )");
        DB::statement("INSERT INTO users SELECT * FROM temp_users");
        DB::statement("DROP TABLE temp_users");
        DB::statement("CREATE UNIQUE INDEX users_email_unique ON users (email)");
    }

    public function down(): void
    {
        DB::statement("DROP TABLE IF EXISTS temp_users");
        DB::statement("CREATE TABLE temp_users AS SELECT * FROM users");
        DB::statement("DROP TABLE users");
        DB::statement("CREATE TABLE users (
            id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
            name VARCHAR NOT NULL,
            email VARCHAR NOT NULL,
            email_verified_at DATETIME,
            password VARCHAR NOT NULL,
            remember_token VARCHAR,
            created_at DATETIME,
            updated_at DATETIME,
            role VARCHAR CHECK (role IN ('teacher', 'student')) NOT NULL DEFAULT 'teacher',
            student_id INTEGER,
            phone VARCHAR,
            specialty VARCHAR,
            FOREIGN KEY(student_id) REFERENCES students(id) ON DELETE SET NULL
        )");
        DB::statement("INSERT INTO users SELECT * FROM temp_users");
        DB::statement("DROP TABLE temp_users");
        DB::statement("CREATE UNIQUE INDEX users_email_unique ON users (email)");
    }
};
