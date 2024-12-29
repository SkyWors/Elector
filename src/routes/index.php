<?php

use App\Configs\Role;
use App\Router;
use App\Utils\Lang;

$router = new Router;

$controllersPath = "App\Controllers\\";

// Pages
$router->add(
	url: "",
	controller: $controllersPath . "IndexController"
);
$router->add(
	url: "/dashboard",
	controller: $controllersPath . "DashboardController",
	title: APP_NAME . " - " . Lang::translate(key: "DASHBOARD_TITLE"),
	needLoginToBe: true,
	accessRoles: [
		Role::TEACHER,
		Role::ADMINISTRATOR
	]
);

// Sessions
$router->add(
	url: "/login",
	controller: $controllersPath . "LoginController",
	title: APP_NAME . " - " . Lang::translate(key: "LOGIN_TITLE"),
	needLoginToBe: false
);
$router->add(
	url: "/disconnect",
	controller: $controllersPath . "DisconnectController",
	needLoginToBe: true
);

// API
$router->add(
	url: "/api",
	controller: $controllersPath . "API\APIController",
	title: APP_NAME . " - API"
);
$router->add(
	url: "/api/election",
	controller: $controllersPath . "API\APIElectionController",
	title: APP_NAME . " - API"
);
$router->add(
	url: "/api/election/state",
	controller: $controllersPath . "API\APIElectionStateController",
	title: APP_NAME . " - API"
);
$router->add(
	url: "/api/group",
	controller: $controllersPath . "API\APIGroupController",
	title: APP_NAME . " - API"
);
$router->add(
	url: "/api/user/vote",
	controller: $controllersPath . "API\APIUserVoteController",
	title: APP_NAME . " - API"
);
