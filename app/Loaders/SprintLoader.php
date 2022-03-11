<?php

namespace App\Loaders;

use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\DB;

class SprintLoader
{
    public function loadById(int $id): array
    {
        $sprintData =
            DB::table('sprint')
                ->select()
                ->where('id', '=', $id)
                ->first();

        if (!$sprintData) {
            throw new RecordsNotFoundException('Sprint not found with id: ' . $id);
        }

        return get_object_vars($sprintData);
    }
}
