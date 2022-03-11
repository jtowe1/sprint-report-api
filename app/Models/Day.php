<?php

namespace App\Models;

use Carbon\Carbon;

class Day
{
    /**
     * This tables ids come from jira,
     * so we do not want an auto incrementing id.
     * Setting this var to false disables this in eloquent.
     *
     * @var false
     */
    public $incrementing = false;

    public int $id;
    public int $sprintId;
    public Carbon $date;
    public string $dayOfWeek;
    public string $totalPointsDone;
    public string $totalPointsRemaining;
    public string $totalGoalPointsRemaining;

    public function __construct()
    {
        $this->totalPointsRemaining = "0";
        $this->totalPointsDone = "0";
        $this->totalGoalPointsRemaining = "0";
    }
}
