<?php

namespace App\Services;

use App\Hydrators\DayHydrator;
use App\Hydrators\SprintHydrator;
use App\Models\Day;
use App\Models\Sprint;
use App\Repositories\DayRepository;
use App\Repositories\SprintRepository;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\RecordsNotFoundException;

class SprintChartService
{
    const DONE = 'Done';
    const ESTIMATE_FIELD = 'customfield_10002';
    const SPRINT_GOAL_FIELD = 'customfield_12101';


    public function __construct(
        private JiraApiService $client,
        private DayHydrator $dayHydrator,
        private DayRepository $dayRepository,
        private SprintHydrator $sprintHydrator,
        private SprintRepository $sprintRepository,
        private array $sprintResponse = [],
        private array $issuesResponse = []
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
        if (Carbon::now()->isWeekday()) {
            $issuesResponse = $this->getIssuesFromJiraBySprintId($sprintId);
            $day = $this->buildNewDayFromJiraResponse($issuesResponse, $sprintId);
            $this->dayRepository->save($day);
        }

        $sprint = $this->sprintRepository->loadById($sprintId);

        return $sprint;
    }

    private function buildNewDayFromJiraResponse(array $issuesResponse, int $sprintId): Day
    {
        $totalPointsDone = $this->getTotalPointsDoneFromIssues($issuesResponse);
        $totalPointsRemaining = $this->getTotalPointsRemainingFromIssues($issuesResponse);
        $totalGoalPointsDone = $this->getTotalGoalPointsDoneFromIssues($issuesResponse);
        $totalGoalPointsRemaining = $this->getTotalGoalPointsRemainingFromIssues($issuesResponse);
        $day = $this->dayHydrator->hydrate(
            [
                'date_code' => Carbon::now()->format('Ymd'),
                'sprint_id' => $sprintId,
                'total_points_done' => $totalPointsDone,
                'total_points_remaining' => $totalPointsRemaining,
                'total_goal_points_done' => $totalGoalPointsDone,
                'total_goal_points_remaining' => $totalGoalPointsRemaining,
                'created_at' => Carbon::now()
            ]
        );

        return $day;
    }


    private function getSprintFromJiraById(int $sprintId): array
    {
        if (empty($this->sprintResponse)) {
            $rawSprintRespone = json_decode($this->client->getSprint($sprintId), true);
            $this->sprintResponse = $rawSprintRespone ?? [];
        }

        return $this->sprintResponse;
    }

    private function getIssuesFromJiraBySprintId(int $sprintId): array
    {
        if (empty($this->issuesResponse)) {
            $rawIssuesResponse = json_decode($this->client->getIssuesBySprintId($sprintId), true);

            $this->issuesResponse = $rawIssuesResponse['issues'] ?? [];
        }

        return $this->issuesResponse;
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

    private function getTotalGoalPointsDoneFromIssues($issues)
    {
        $totalGoalPointsDone = 0;
        foreach ($issues as $issue) {
            if ($issue['fields']['status']['name'] === self::DONE) {
                if ($issue['fields'][self::SPRINT_GOAL_FIELD] !== null
                    && $issue['fields'][self::SPRINT_GOAL_FIELD][0]['value'] === 'Yes'
                ) {
                    $totalGoalPointsDone += $issue['fields'][self::ESTIMATE_FIELD];
                }
            }
        }

        return $totalGoalPointsDone;
    }

    private function getTotalGoalPointsRemainingFromIssues($issues)
    {
        $totalGoalPointsRemaining = 0;
        foreach ($issues as $issue) {
            if ($issue['fields']['status']['name'] !== self::DONE) {
                if ($issue['fields'][self::SPRINT_GOAL_FIELD] !== null
                    && $issue['fields'][self::SPRINT_GOAL_FIELD][0]['value'] === 'Yes'
                ) {
                    $totalGoalPointsRemaining += $issue['fields'][self::ESTIMATE_FIELD];
                }
            }
        }

        return $totalGoalPointsRemaining;
    }

    private function getTotalPointsFromIssues($issues)
    {
        $totalPoints = 0;
        foreach ($issues as $issue) {
            $totalPoints += $issue['fields'][self::ESTIMATE_FIELD];
        }

        return $totalPoints;
    }

    private function getTotalPointsDoneFromIssues($issues)
    {
        $totalPointsDone = 0;
        foreach ($issues as $issue) {
            if ($issue['fields']['status']['name'] === self::DONE) {
                $totalPointsDone += $issue['fields'][self::ESTIMATE_FIELD];
            }
        }

        return $totalPointsDone;
    }

    private function getTotalPointsRemainingFromIssues($issues)
    {
        $totalPointsRemaining = 0;
        foreach ($issues as $issue) {
            if ($issue['fields']['status']['name'] !== self::DONE) {
                $totalPointsRemaining += $issue['fields'][self::ESTIMATE_FIELD];
            }
        }

        return $totalPointsRemaining;
    }

    private function buildDays()
    {

    }

    private function somethingElse(int $sprintId)
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
