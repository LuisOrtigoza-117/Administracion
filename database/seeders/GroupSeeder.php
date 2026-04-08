<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Group;
use App\Models\User;

class GroupSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::where('role', 'teacher')->first();
        
        if ($teacher) {
            Group::create(['name' => '3E', 'grade' => '3', 'section' => 'A', 'school_year' => '2024-2025', 'teacher_id' => $teacher->id]);
            Group::create(['name' => '3A', 'grade' => '3', 'section' => 'A', 'school_year' => '2024-2025', 'teacher_id' => $teacher->id]);
            Group::create(['name' => '3B', 'grade' => '3', 'section' => 'B', 'school_year' => '2024-2025', 'teacher_id' => $teacher->id]);
        } else {
            Group::create(['name' => '3E', 'grade' => '3', 'section' => 'A', 'school_year' => '2024-2025']);
            Group::create(['name' => '3A', 'grade' => '3', 'section' => 'A', 'school_year' => '2024-2025']);
            Group::create(['name' => '3B', 'grade' => '3', 'section' => 'B', 'school_year' => '2024-2025']);
        }
        
        echo "Grupos creados\n";
    }
}
