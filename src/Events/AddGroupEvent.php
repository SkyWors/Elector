<?php

namespace App\Events;

use App\Models\Entities\Group;
use App\Models\Entities\User;
use App\Models\Repositories\GroupRepository;
use App\Models\Repositories\UserRepository;
use App\Utils\System;
use SplFileObject;

class AddGroupEvent {
	public static function implement() : void {
		if ($_SERVER["REQUEST_METHOD"] === "POST") {
			if (isset($_POST["name"])) {
				$group = new Group(name: $_POST["name"]);
				$groupRepo = new GroupRepository(group: $group);

				$groupUid = $groupRepo->create();

				$csv = new SplFileObject(filename: $_FILES["import"]['tmp_name']);
				$csv->setFlags(flags: SplFileObject::READ_CSV);
				$csv->setCsvControl(separator: ';');

				$users = [];

				$firstLine = false;
				foreach ($csv as $line) {
					if (isset($line[1])) {
						if ($firstLine == false) {
							$firstLine = true;
							continue;
						}

						$password = System::uidGen(size: 6);
						$user = new User(name: mb_strtolower(string: $line[1]), surname: mb_strtolower(string: $line[0]), password: $password, groupUid: $groupUid);
						$userRepo = new UserRepository(user: $user);
						$identifiant = $userRepo->create();

						$users[$identifiant] = $password;
					}
				}

				ob_clean();

				header(header: 'Content-Type: text/csv');
				header(header: 'Content-Disposition: attachment; filename="' . $_POST["name"] . '_mdp.csv"');

				echo "identifiant;password\n";
				foreach ($users as $identifiant => $password) {
					echo $identifiant . ";" . $password . "\n";
				}

				exit;
			}
			System::redirect();
		}
	}
}
