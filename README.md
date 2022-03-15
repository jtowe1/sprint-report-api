# Sprint Report Api

## Requirements
* [docker](https://www.docker.com/)
* php version >= 8.0
* [composer](https://getcomposer.org/)

## Clone this repo and install packages

```bash
git clone https://github.com/jtowe1/sprint-report-api.git
cd sprint-report-api
composer install
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



## Start the stack

```bash
vendor/bin/sail up -d
```
## Run the database migrations (inside the app container shell)
```bash
vendor/bin/sail shell
php artisan migrate
exit
```

## Make a request
```bash
curl localhost/api/board/118/sprint/1136
```