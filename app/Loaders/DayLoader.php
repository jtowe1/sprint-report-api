<?php

namespace App\Loaders;

use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\DB;

class DayLoader
{
    public function loadById(int $id): array
    {
        $dayData =
            DB::table('sprint')
                ->select()
                ->where('id', '=', $id)
                ->first();
        if (!$dayData) {
            throw new RecordsNotFoundException('Day not found with id: ' . $id);
        }

        return get_object_vars($dayData);
    }
}