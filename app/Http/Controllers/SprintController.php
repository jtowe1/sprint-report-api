<?php

namespace App\Http\Controllers;

use App\Services\SprintChartService;
use Illuminate\Database\RecordsNotFoundException;
use Illuminate\Routing\Controller as BaseController;

class SprintController extends BaseController
{
    public function get(int $boardId, int $sprintId, SprintChartService $sprintChartService)
    {
        $sprint = null;

        try {
            $sprint = $sprintChartService->getSprintChartBySprintId($sprintId);
        } catch (RecordsNotFoundException $e) {
            return response('', 404);
        }

        $response = response($sprint);
        $response->header('Content-Type', 'application/json');

        return $response;
    }
}
