<?php

namespace App\Http\Controllers;

use App\Services\JiraApiService;
use Illuminate\Http\Request;

class BoardController extends Controller
{
    public function list(Request $request, JiraApiService $client)
    {
        $startAt = $request->input('startAt', 0);
        $boards = $client->getBoards($startAt);

        $response = response($boards);
        $response->header('Content-Type', 'application/json');

        return $response;
    }

    public function get(int $id, JiraApiService $client)
    {
        $board = $client->getBoard($id);

        $response = response($board);
        $response->header('Content-Type', 'application/json');

        return $response;

    }
}
