<?php

require __DIR__ . "/vendor/autoload.php";

use
	Dotenv\Dotenv,
	Elector\Database,
	Elector\ErrorHandler;

$dotenv = Dotenv::createImmutable(__DIR__)->load();

if ($_ENV["DEBUG"] == 1) {
	ini_set('display_errors', 1);
	ini_set('display_startup_errors', 1);
	error_reporting(E_ALL);
} else {
	set_error_handler(function($severity, $message, $file, $line) {
		throw new ErrorException($message, 0, $severity, $file, $line);
	});
	set_exception_handler([ErrorHandler::class, "handle"]);
	register_shutdown_function([ErrorHandler::class, "shutdown"]);
}

session_start();

$db = new Database();
define("DATABASE", $db->getConnection());

define("DATABASE_USER", "user");
define("DATABASE_VOTE", "vote");
define("DATABASE_ELECTION", "election");
define("DATABASE_ROLE", "role");
define("DATABASE_STATE", "state");
define("DATABASE_GROUP", "group");

define("ROLE_STUDENT", 1);
define("ROLE_TEACHER", 10);
