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

## Create a User (Postman)
Setup a POST request to `localhost:80/register` with the following headers
```
Accept: application/json
Referer: localhost:80
X-XSRF-TOKEN: {{xsrf-token}}
```
And the following form body
```
name: <Your name>
email: <Your email>
password: <Your password>
password_confirmation: <Your password>
```
And set the following as a `Pre-request Script`
```js
pm.sendRequest({
    url: 'http://localhost:80/sanctum/csrf-cookie',
    method: 'GET'
}, function (error, response, { cookies }) {
    if (!error) {
        pm.environment.set('xsrf-token', cookies.get('XSRF-TOKEN'))
    }
})
```
You should get a `201 Created` response or a `422 Unprocessable Content` (with errors in response body)

## Login (Postman)
Setup a POST request to `localhost:80/login` with the following headers
```
Accept: application/json
Referer: localhost:80
X-XSRF-TOKEN: {{xsrf-token}}
```
And the following form body
```
email: <Your email>
password: <Your password>
```
And set the following as a `Pre-request Script`
```js
pm.sendRequest({
    url: 'http://localhost:80/sanctum/csrf-cookie',
    method: 'GET'
}, function (error, response, { cookies }) {
    if (!error) {
        pm.environment.set('xsrf-token', cookies.get('XSRF-TOKEN'))
    }
})
```
You should get a `200 OK` response or a `422 Unprocessable Content` (with errors in response body)

Doing this will set a `XSRF-TOKEN` and `laravel_session` cookie in Postman that will now be sent with each request.  The `laravel_session` is your key to being logged in.

## Read the api docs
TODO: Link to built and rendered api docs will go here