<?php

namespace Database\Factories;

use App\Models\Exercise;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\File;

/**
 * @extends Factory<Exercise>
 */
class ExerciseFactory extends Factory
{
    /**
     * @var array<int, array<string, mixed>>|null
     */
    private static ?array $dictionary = null;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return fake()->randomElement(self::dictionary());
    }

    /**
     * Load the exercise dictionary once and map it to model attributes.
     *
     * @return array<int, array<string, mixed>>
     */
    private static function dictionary(): array
    {
        return self::$dictionary ??= array_map(
            static fn (array $exercise): array => [
                'name' => $exercise['name'],
                'force' => $exercise['force'] ?? '',
                'level' => $exercise['level'],
                'mechanic' => $exercise['mechanic'] ?? '',
                'equipment' => $exercise['equipment'] ?? '',
                'category' => $exercise['category'],
                'primary_muscles' => $exercise['primaryMuscles'],
                'secondary_muscles' => $exercise['secondaryMuscles'],
                'instructions' => $exercise['instructions'],
            ],
            File::json(database_path('seeders/exercises.json'))['exercises'],
        );
    }
}
