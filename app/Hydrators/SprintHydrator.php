<?php

namespace App\Hydrators;

use App\Models\Sprint;
use Carbon\Carbon;

class SprintHydrator
{
    public function hydrate(array $data): Sprint
    {
        return new Sprint(
            $data['id'],
            $data['name'],
            $data['board_id'],
            $data['start_date'],
            $data['end_date'],
            $data['length'],
            $data['total_points'],
            $data['total_goal_points'],
            array_key_exists('days', $data) ? $data['days'] : null,
            new Carbon($data['created_at']),
            !empty($data['updated_at']) ? new Carbon($data['updated_at']) : null
        );
    }
}
