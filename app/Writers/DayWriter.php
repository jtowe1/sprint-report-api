<?php

namespace App\Writers;

use App\Models\Day;
use Carbon\Carbon;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Support\Facades\DB;

class DayWriter
{
    public function write(Day $day): bool
    {
        try {
            return $this->updateInDatabase($day);
        } catch (\Throwable $e) {
            return $this->insertIntoDatabase($day);
        }
    }

    private function updateInDatabase(Day $day): bool
    {
        DB::beginTransaction();

        error_log('Trying to Update Day');
        $result = DB::table('day')
            ->where('id', '=', $day->getId())
            ->update(
                [
                    'date_code' => $day->getDateCode(),
                    'sprint_id' => $day->getSprintId(),
                    'total_points_done' => $day->getTotalPointsDone(),
                    'total_points_remaining' => $day->getTotalPointsRemaining(),
                    'total_goal_points_done' => $day->getTotalGoalPointsDone(),
                    'total_goal_points_remaining' => $day->getTotalGoalPointsRemaining(),
                    'updated_at' => Carbon::now(),
                ]
            );
        DB::commit();

        if ($result === 0) {
            error_log('Unable to Update Day');
            throw new RecordsNotFoundException('Unable to update Day with id: ' . $day->getId());
        }

        return true;
    }

    private function insertIntoDatabase(Day $day): bool
    {
        DB::beginTransaction();

        try {
            error_log('Trying to insert Day');
            DB::table('day')->insert(
                [
                    'date_code' => $day->getDateCode(),
                    'sprint_id' => $day->getSprintId(),
                    'total_points_done' => $day->getTotalPointsDone(),
                    'total_points_remaining' => $day->getTotalPointsRemaining(),
                    'total_goal_points_done' => $day->getTotalGoalPointsDone(),
                    'total_goal_points_remaining' => $day->getTotalGoalPointsRemaining(),
                    'created_at' => Carbon::now(),
                    'updated_at' => null,
                ]
            );
            DB::commit();
        } catch (\Throwable $e) {
            error_log('Exception when inserting Day');
            DB::rollBack();
            throw $e;
        }

        return true;
    }
}
