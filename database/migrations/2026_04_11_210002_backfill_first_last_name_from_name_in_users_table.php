<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::table('users')->whereNotNull('name')->update([
            'first_name' => DB::raw("split_part(name, ' ', 1)"),
            'last_name' => DB::raw("nullif(trim(substring(name from position(' ' in name))), '')"),
        ]);
    }

    public function down(): void
    {
        DB::table('users')->update([
            'name' => DB::raw("first_name || ' ' || coalesce(last_name, '')"),
        ]);
    }
};
