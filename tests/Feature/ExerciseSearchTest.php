<?php

namespace Feature;

use App\Models\Exercise;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ExerciseSearchTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Exercise::factory()->count(10)->create();
    }

    #[Test]
    public function search_returns_correct_response_keys_in_response(): void
    {
        $response = $this
            ->actingAs(User::factory()->create())
            ->getJson(route('exercises.search'));

        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'name',
                    'force',
                    'level',
                    'mechanic',
                    'equipment',
                    'category',
                    'primaryMuscles',
                    'secondaryMuscles',
                    'instructions',
                ],
            ],
        ]);
    }
}
