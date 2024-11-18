<?php

use
	Elector\Group,
	Elector\User,
	Elector\Utils;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	if (isset($_POST["name"])) {
		$uid = Group::create($_POST["name"]);
		$group = new Group($uid);

		$csv = new SplFileObject($_FILES["import"]['tmp_name']);
		$csv->setFlags(SplFileObject::READ_CSV);
		$csv->setCsvControl(';');

		$users = [];

		$firstLine = false;
		foreach ($csv as $line) {
			if (isset($line[1])) {
				if ($firstLine == false) {
					$firstLine = true;
					continue;
				}
				$password =  Utils::uidGen(6);
				$identifiant = User::add(mb_strtolower($line[1]), mb_strtolower($line[0]), $password, $uid);

				$users[$identifiant] = $password;
			}
		}
		include __DIR__ . "/get_password.php";
	}
	header("Refresh: 0");
	exit;
}
