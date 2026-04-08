<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Task;
use App\Models\Group;
use Carbon\Carbon;

class TaskSeeder extends Seeder
{
    public function run(): void
    {
        $group = Group::first();
        
        if ($group) {
            Task::create([
                'title' => 'Ejercicios de Programación',
                'description' => 'Resolver los ejercicios del 1 al 5 del capítulo de algoritmos.',
                'due_date' => Carbon::now()->addDays(7),
                'group_id' => $group->id,
            ]);

            Task::create([
                'title' => 'Proyecto Final',
                'description' => 'Desarrollar una aplicación web básica usando HTML, CSS y JavaScript.',
                'due_date' => Carbon::now()->addDays(14),
                'group_id' => $group->id,
            ]);

            Task::create([
                'title' => 'Ensayo sobre Historia de la Computación',
                'description' => 'Escribir un ensayo de 5 páginas sobre la evolución de las computadoras.',
                'due_date' => Carbon::now()->addDays(3),
                'group_id' => $group->id,
            ]);

            echo "Tareas creadas para el grupo: " . $group->name . "\n";
        }
    }
}
