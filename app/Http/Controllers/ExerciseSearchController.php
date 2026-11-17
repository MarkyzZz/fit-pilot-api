<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExerciseSearchRequest;
use App\Http\Resources\ExerciseSearchResource;
use App\Models\Exercise;

class ExerciseSearchController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __invoke(ExerciseSearchRequest $request)
    {
        $exercises = Exercise::query()->get();

        return ExerciseSearchResource::collection($exercises);
    }
}
