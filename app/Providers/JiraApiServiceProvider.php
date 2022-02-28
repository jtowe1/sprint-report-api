<?php

namespace App\Providers;

use App\Services\JiraApiService;
use GuzzleHttp\Client;
use Illuminate\Support\ServiceProvider;

class JiraApiServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(JiraApiService::class, function($app) {
            $jiraBaseUrl = config('jira.baseUrl');
            $jiraRestPath = config('jira.restPath');
            $jiraOrgId = env('JIRA_ORG_ID');
            $jiraBasicUsername = env('JIRA_BASIC_USERNAME');
            $jiraBasicToken = env('JIRA_BASIC_TOKEN');

            if (empty($jiraBaseUrl) || empty($jiraRestPath) || empty($jiraOrgId)) {
                throw new \Exception('Jira configuration options missing!');
            }

            if (empty($jiraBasicUsername) || empty($jiraBasicToken)) {
                throw new \Exception('Jira basic credentials missing!');
            }

            $url = $jiraBaseUrl . $jiraOrgId . $jiraRestPath;

            return new JiraApiService(new Client([
                'base_uri' => $url,
                'auth' => [
                    $jiraBasicUsername,
                    $jiraBasicToken,
                ],
            ]));
        });
    }
}
