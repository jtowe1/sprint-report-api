<?php

namespace App\Hydrators;

use App\Models\Day;
use Carbon\Carbon;

class DayHydrator
{
    public function hydrate(array $data): Day
    {
        return new Day(
            $data['id'],
            $data['date_code'],
            $data['sprint_id'],
            $data['total_points_done'],
            $data['total_points_remaining'],
            $data['total_goal_points_done'],
            new Carbon($data['created_at']),
            !empty($data['updated_at']) ? new Carbon($data['updated_at']) : null
        );
    }
}
