# Sprint Report Api

## Requirements
* [docker](https://www.docker.com/)
* php version >= 8.0
* [composer](https://getcomposer.org/)

## Clone this repo

```bash
git clone https://github.com/jtowe1/sprint-report-api.git
cd sprint-report-api
```

## Setup the environment
Create the .env file and generate an encryption key
```bash
cp .env.example .env
php artisan key:generate
```
Edit the .env file and add your database info and Jira credentials
```
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=sprint_report
DB_USERNAME=sail
DB_PASSWORD=password

JIRA_ORG_ID="jira-org-id"
JIRA_BASIC_USERNAME="jira-basic-username"
JIRA_BASIC_TOKEN="jira-basic-token"
```

## Run with PHP built-in server

```bash
php -S localhost:8008 -t public
```

## Make a request
```bash
curl localhost:8008/board/118/sprint/1136
```