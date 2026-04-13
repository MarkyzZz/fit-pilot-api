<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->string('first_name')->nullable(false)->change();
            $table->string('last_name')->nullable(false)->change();
        });

        Schema::table('users', static function (Blueprint $table): void {
            $table->dropColumn('name');
        });
    }

    public function down(): void
    {
        Schema::table('users', static function (Blueprint $table): void {
            $table->string('name')->nullable()->after('id');
        });

        Schema::table('users', static function (Blueprint $table): void {
            $table->string('first_name')->nullable()->change();
            $table->string('last_name')->nullable()->change();
        });
    }
};
