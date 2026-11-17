<?php

namespace Database\Seeders;

use App\Models\Exercise;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;

class ExerciseSeeder extends Seeder
{
    public function run(): void
    {
        $exercises = File::json(database_path('seeders/exercises.json'))['exercises'];

        foreach ($exercises as $exercise) {
            Exercise::firstOrCreate(
                ['name' => $exercise['name']],
                [
                    'force' => $exercise['force'] ?? '',
                    'level' => $exercise['level'],
                    'mechanic' => $exercise['mechanic'] ?? '',
                    'equipment' => $exercise['equipment'] ?? '',
                    'category' => $exercise['category'],
                    'primary_muscles' => $exercise['primaryMuscles'],
                    'secondary_muscles' => $exercise['secondaryMuscles'],
                    'instructions' => $exercise['instructions'],
                ],
            );
        }
    }
}
