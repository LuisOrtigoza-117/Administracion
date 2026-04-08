<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\Group;
use Carbon\Carbon;

class AttendanceSeeder extends Seeder
{
    public function run(): void
    {
        $student = Student::first();
        $group = Group::first();
        
        if ($student && $group) {
            $dates = [
                Carbon::now()->subDays(5),
                Carbon::now()->subDays(4),
                Carbon::now()->subDays(3),
                Carbon::now()->subDays(2),
                Carbon::now()->subDays(1),
            ];

            $statuses = ['present', 'present', 'present', 'late', 'present'];

            foreach ($dates as $i => $date) {
                Attendance::create([
                    'student_id' => $student->id,
                    'group_id' => $group->id,
                    'date' => $date,
                    'status' => $statuses[$i],
                ]);
            }

            echo "Asistencias creadas para el estudiante: " . $student->name . "\n";
        }
    }
}
