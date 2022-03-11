<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller as BaseController;

class SprintController extends BaseController
{
    public function get(int $boardId, int $sprintId)
    {
        return response($sprintId);
        // $sprint = null;

        // try {
        //     $sprint = $sprintChartService->getSprintChartBySprintId($sprintId);
        // } catch (RecordsNotFoundException $e) {
        //     return response('', 404);
        // }

        // $response = response($sprint);
        // $response->header('Content-Type', 'application/json');

        // return $response;
    }
}
