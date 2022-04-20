<?php

namespace App\Loaders;

use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\DB;

class DayLoader
{
    public function loadById(int $id): array
    {
        $dayData =
            DB::table('day')
                ->select()
                ->where('id', '=', $id)
                ->first();
        if (!$dayData) {
            throw new RecordsNotFoundException('Day not found with id: ' . $id);
        }

        return get_object_vars($dayData);
    }

    public function loadByDateCode(int $dateCode): array
    {
        $dayData =
            DB::table('day')
                ->select()
                ->where('date_code', '=', $dateCode)
                ->first();
        if (!$dayData) {
            throw new RecordsNotFoundException('Day not found with Date Code: ' . $dateCode);
        }

        return get_object_vars($dayData);
    }

    public function loadBySprintId(int $sprintId): array
    {
        $dayData =
            DB::table('day')
                ->select()
                ->where('sprint_id', '=', $sprintId)
                ->get();
        if (!$dayData) {
            throw new RecordsNotFoundException('Day not found with Sprint Id: ' . $sprintId);
        }

        return $dayData->map(
            fn($item, $key) => get_object_vars($item)
        )->all();

        // return get_object_vars($dayData);
    }
}
