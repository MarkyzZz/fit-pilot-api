<?php

namespace Database\Migrations\Concerns;

use Illuminate\Support\Facades\DB;

/**
 * For use only in data migrations that contain dialect-specific SQL.
 * Never use on schema migrations — SQLite schema drift will cause false-positive tests.
 */
trait SkipsOnSqlite
{
    protected function isSqlite(): bool
    {
        return DB::getDriverName() === 'sqlite';
    }
}
