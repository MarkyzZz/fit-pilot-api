<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('exercises', static function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('force')->default('');
            $table->string('level');
            $table->string('mechanic')->default('');
            $table->string('equipment')->default('');
            $table->string('category');

            $table->jsonb('primary_muscles')->default('[]');
            $table->jsonb('secondary_muscles')->default('[]');
            $table->jsonb('instructions')->default('[]');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::drop('exercises');
    }
};
