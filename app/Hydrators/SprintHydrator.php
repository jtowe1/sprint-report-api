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
            $data['length'],
            $data['total_points'],
            $data['total_goal_points'],
            $data['days'],
            new Carbon($data['created_at']),
            !empty($data['updated_at']) ? new Carbon($data['updated_at']) : null
        );
    }
}
