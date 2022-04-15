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
## Make a request
```bash
curl localhost/api/board/118/sprint/1136
```

## Alternate Install - Devcontainer Environment
A devcontainer is included that can be used as an alternate install.
Read about devcontainers with vscode: https://code.visualstudio.com/docs/remote/containers

Requirements:
- [VSCode](https://code.visualstudio.com/)
- [The Remote Container extension](https://marketplace.visualstudio.com/items?itemName=ms-vscode-remote.remote-containers)

Clone the project and setup the .env file.

Open the Project in Vscode with the remote container extension installed. By default vscode should detect the devcontainer and prompt you to re-open within the container.
You can also use the remote container extension to rebuild the devcontainer when attached to it, or to return to the local environment.

Once connected you should be able to run `docker-compose up` and then `php artisan migrate` to configure the app.

#### Docker
Docker and docker-compose are installed inside the devcontainer, and the hosts docker socket is exposed.
Running docker commands inside the container will run those on the host docker daemon and allow full ability to build and run the app while inside the devcontainer.

A project name is set via `$COMPOSE_PROJECT_NAME` so that docker-compose will create containers within the devcontainers own project, which allows network sharing to be done.

This allows `php artisan` commands to be run within the devcontainer after starting the app with `docker-compose up`.

#### Git
Git config should be autoforwarded by vscode, however SSH keys are not.
Vscode recommends using [SSH-Agent](https://code.visualstudio.com/docs/remote/containers#_using-ssh-keys) to forward your ssh keys.

To do so, on the host environment run `ssh-add` to add your default ssh key or specify a specifc key:
```
ssh-add $HOME/.ssh/github_rsa
```
