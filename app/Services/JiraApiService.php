<?php

namespace App\Services;

use GuzzleHttp\Client;
use Psr\Http\Message\StreamInterface;

class JiraApiService
{
    private Client $guzzleClient;

    public function __construct(Client $guzzleClient)
    {
        $this->guzzleClient = $guzzleClient;
    }

    public function getBoards(?int $startAt = 0): StreamInterface
    {
        $response = $this->guzzleClient->request('GET', 'board', ['query' => ['startAt' => $startAt]]);

        return $response->getBody();
    }

    public function getBoard(int $id): StreamInterface
    {
        $response = $this->guzzleClient->request('GET', 'board/' . $id);

        return $response->getBody();
    }

    public function getSprint(int $sprintId): StreamInterface
    {
        $response = $this->guzzleClient->request(
            'GET',
            'sprint/' . $sprintId
        );

        return $response->getBody();
    }

    public function getSprintsForBoard(int $boardId)
    {
        $reponse = $this->guzzleClient->request('GET', 'board/' . $boardId . '/sprint');

        return $reponse->getBody();
    }

    public function getIssuesBySprintId(int $sprintId)
    {
        $response = $this->guzzleClient->request('GET', 'sprint/' . $sprintId . '/issue');

        return $response->getBody();
    }
}
