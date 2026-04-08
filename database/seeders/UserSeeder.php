<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Student;
use App\Models\Group;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        $teacher = User::create([
            'name' => 'Profesor',
            'email' => 'profesor@escuela.com',
            'password' => Hash::make('password'),
            'role' => 'teacher',
        ]);

        $group = Group::first();
        
        if ($group) {
            $student = Student::create([
                'name' => 'Juan',
                'lastname' => 'Pérez',
                'student_number' => '2024001',
                'email' => 'juan.perez@escuela.com',
                'group_id' => $group->id,
            ]);

            User::create([
                'name' => 'Juan Pérez',
                'email' => 'juan.perez@escuela.com',
                'password' => Hash::make('password'),
                'role' => 'student',
                'student_id' => $student->id,
            ]);
        }

        echo "Usuarios creados:\n";
        echo "Maestro: profesor@escuela.com / password\n";
        if ($group) {
            echo "Alumno: juan.perez@escuela.com / password\n";
        }
    }
}
