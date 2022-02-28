<?php

namespace App\Http\Controllers;

use App\Services\JiraApiService;
use GuzzleHttp\Client;

class ExampleController extends Controller
{
    public function getSprintBoard(int $id, JiraApiService $client)
    {
        $board = $client->getBoard($id);

        $response = response($board);
        $response->header('Content-Type', 'application/json');

        return $response;
    }
}
