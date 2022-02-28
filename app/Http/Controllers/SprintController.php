<?php

namespace App\Http\Controllers;

use App\Hydrators\SprintHydrator;
use App\Loaders\SprintLoader;
use App\Models\Sprint;
use App\Repositories\SprintRepository;
use App\Services\JiraApiService;
use App\Services\SprintChartService;
use Illuminate\Database\RecordsNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SprintController extends Controller
{
    public function list(int $boardId, JiraApiService $client)
    {
        $sprints = $client->getSprintsForBoard($boardId);

        $response = response($sprints);
        $response->header('Content-Type', 'application/json');

        return $response;
    }

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
