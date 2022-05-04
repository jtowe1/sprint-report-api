<?php

namespace App\Writers;

use App\Models\Sprint;
use Carbon\Carbon;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\DB;

class SprintWriter
{
    const DATABASE = 'database';

    const WRITE_TO = self::DATABASE;

    public function write(Sprint $sprint): bool
    {
        if (self::WRITE_TO === self::DATABASE) {
            try {
                return $this->updateInDatabase($sprint);
            } catch (\Throwable $e) {
                return $this->insertIntoDatabase($sprint);
            }
        }
    }

    private function updateInDatabase(Sprint $sprint): bool
    {
        DB::beginTransaction();

        error_log('Trying to Update Sprint');
        $result = DB::table('sprint')
            ->where('id', '=', $sprint->getId())
            ->update(
                [
                    'id' => $sprint->getId(),
                    'name' => $sprint->getName(),
                    'board_id' => $sprint->getBoardId(),
                    'length' => $sprint->getLength(),
                    'start_date' => $sprint->getStartDate(),
                    'end_date' => $sprint->getEndDate(),
                    'total_points' => $sprint->getTotalPoints(),
                    'total_goal_points' => $sprint->getTotalGoalPoints(),
                    'updated_at' => Carbon::now(),
                ]
            );
        DB::commit();

        if ($result === 0) {
            error_log('Unable to Update Sprint');
            throw new RecordsNotFoundException('Unable to update sprint with id: ' . $sprint->getId());
        }

        return true;
    }

    private function insertIntoDatabase(Sprint $sprint): bool
    {
        DB::beginTransaction();

        try {
            error_log('Trying to insert Sprint');
            DB::table('sprint')->insert(
                [
                    'id' => $sprint->getId(),
                    'name' => $sprint->getName(),
                    'board_id' => $sprint->getBoardId(),
                    'start_date' => $sprint->getStartDate(),
                    'end_date' => $sprint->getEndDate(),
                    'length' => $sprint->getLength(),
                    'total_points' => $sprint->getTotalPoints(),
                    'total_goal_points' => $sprint->getTotalGoalPoints(),
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ]
            );
            DB::commit();
        } catch (\Throwable $e) {
            error_log('Exception when inserting Sprint');
            DB::rollBack();
            throw $e;
        }

        return true;
    }
}