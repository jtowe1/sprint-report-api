<?php

namespace App\Services;

use App\Hydrators\SprintHydrator;
use App\Models\Day;
use App\Models\Sprint;
use App\Repositories\SprintRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\RecordsNotFoundException;

class SprintChartService
{
    const ESTIMATE_FIELD = 'customfield_10002';
    const SPRINT_GOAL_FIELD = 'customfield_12101';


    public function __construct(
        private JiraApiService $client,
        private SprintRepository $sprintRepository,
        private SprintHydrator $sprintHydrator
    )
    {}

    public function getSprintChartBySprintId(int $sprintId): Sprint
    {
        $sprint = $this->getSprintById($sprintId);

        return $sprint;
    }

    private function getSprintById(int $sprintId): Sprint
    {
        $sprint = null;

        try {
            $sprint = $this->sprintRepository->loadById($sprintId);
        } catch (RecordsNotFoundException) {
            $sprintResponse = $this->getSprintFromJiraById($sprintId);
            $issuesResponse = $this->getIssuesFromJiraBySprintId($sprintId);
            $sprint = $this->buildNewSprintFromJiraResponse($sprintResponse, $issuesResponse);
            $this->sprintRepository->save($sprint);
        }

        return $sprint;
    }

    private function getSprintFromJiraById(int $sprintId): array
    {
        $rawSprintRespone = json_decode($this->client->getSprint($sprintId), true);

        return $rawSprintRespone ?? [];
    }

    private function getIssuesFromJiraBySprintId(int $sprintId): array
    {
        $rawIssuesResponse = json_decode($this->client->getIssuesBySprintId($sprintId), true);

        return $rawIssuesResponse['issues'] ?? [];
    }

    private function buildNewSprintFromJiraResponse(array $sprintResponse, array $issuesResponse): Sprint
    {
        if (empty($sprintResponse) || empty($issuesResponse)) {
            throw new Exception("Can't build sprint with empty Jira responses");
        }

        $sprintStartDate = Carbon::createFromTimeString($sprintResponse['startDate']);
        $sprintEndDate = Carbon::createFromTimeString($sprintResponse['endDate']);
        $sprintLength = $sprintStartDate->diffInWeekDays($sprintEndDate) + 1;

        $totalPoints = $this->getTotalPointsFromIssues($issuesResponse);
        $totalGoalPoints = $this->getTotalGoalPointsFromIssues($issuesResponse);

        return $this->sprintHydrator->hydrate(
            [
                'id' => $sprintResponse['id'],
                'name' => $sprintResponse['name'],
                'board_id' => $sprintResponse['originBoardId'],
                'length' => $sprintLength,
                'total_points' => $totalPoints,
                'total_goal_points' => $totalGoalPoints,
                'created_at' => Carbon::now()
            ]
        );

    }

    private function getTotalGoalPointsFromIssues($issues)
    {
        $totalGoalPoints = 0;
        foreach ($issues as $issue) {
            if ($issue['fields'][self::SPRINT_GOAL_FIELD] !== null
                    && $issue['fields'][self::SPRINT_GOAL_FIELD][0]['value'] === 'Yes'
            ) {
                $totalGoalPoints += $issue['fields'][self::ESTIMATE_FIELD];
            }
        }

        return $totalGoalPoints;
    }

    private function getTotalPointsFromIssues($issues)
    {
        $totalPoints = 0;
        foreach ($issues as $issue) {
            $totalPoints += $issue['fields'][self::ESTIMATE_FIELD];
        }

        return $totalPoints;
    }

    private function buildDays()
    {

    }

    private function somethingElse(int $sprintId): Sprint
    {





        // $day = new Day();
        // $day->date = Carbon::now();
        // $day->dayOfWeek = Carbon::now()->englishDayOfWeek;

        // $totalPoints = 0;
        // $totalGoalPoints = 0;
        // foreach ($issues as $issue) {
        //     // Get done points vs remaining points
        //     if ($issue['fields']['status']['name'] === 'Done') {
        //         $day->totalPointsDone += $issue['fields'][self::ESTIMATE_FIELD];
        //     } else {
        //         $day->totalPointsRemaining += $issue['fields'][self::ESTIMATE_FIELD];

        //         if ($issue['fields'][self::SPRINT_GOAL_FIELD] !== null
        //             && $issue['fields'][self::SPRINT_GOAL_FIELD][0]['value'] === 'Yes'
        //         ) {
        //             $day->totalGoalPointsRemaining += $issue['fields'][self::ESTIMATE_FIELD];
        //         }
        //     }
        // }
    }
}
