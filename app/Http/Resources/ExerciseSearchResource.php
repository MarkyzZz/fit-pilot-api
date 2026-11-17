<?php

namespace App\Http\Resources;

use App\Models\Exercise;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @mixin Exercise
 */
class ExerciseSearchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'force' => $this->force,
            'level' => $this->level,
            'mechanic' => $this->mechanic,
            'equipment' => $this->equipment,
            'category' => $this->category,
            'primaryMuscles' => $this->primary_muscles,
            'secondaryMuscles' => $this->secondary_muscles,
            'instructions' => $this->instructions,
        ];
    }
}
