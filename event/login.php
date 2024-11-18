<?php

use
	Elector\User,
	Elector\Utils;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	$result = User::verified($_POST["identifiant"], $_POST["password"]);
	if (isset($result)) {
		$_SESSION["userUID"] = $result;
		Utils::head("/");
	} else {
		$errorMessage = "Identifiant ou mot de passe incorrect.";
	}
}
