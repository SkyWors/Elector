<?php

use
	Elector\User;

require __DIR__ . "/start.php";

if (isset($_SESSION["userUID"])) {
	$user = new User($_SESSION["userUID"]);
}

switch (explode("?", $_SERVER["REQUEST_URI"])[0]) {
	case "/":
		$headerTitle = "";
		include "page/index/index.php";
		break;
	case "/connect":
		$headerTitle = "";
		include "api/index.php";
		break;
	case "/dashboard":
		$headerTitle = "Tableau de bord";
		include "page/dashboard/index.php";
		break;
	case "/login":
		$headerTitle = "Connexion";
		include "page/account/login.php";
		break;
	case "/logout":
		$headerTitle = "";
		include "event/logout.php";
		break;
	case "/notfound":
		$headerTitle = "Page introuvable.";
		include "page/error/notfound.php";
		break;
	case "/error":
		$headerTitle = "Erreur";
		include "page/error/fatal.php";
		break;
	default:
		$headerTitle = "Page introuvable.";
		include "page/error/notfound.php";
		break;
}
