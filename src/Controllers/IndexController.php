<?php

namespace App\Controllers;

use App\Configs\Path;
use App\Models\Entities\Group;
use App\Models\Entities\User;
use App\Models\Repositories\GroupRepository;
use App\Models\Repositories\UserRepository;
use App\Utils\System;

class IndexController {
	public function render() : void {
		require Path::LAYOUT . "/header.php";

		require Path::LAYOUT . "/navbar.php";

		if (isset($_GET["group"])) {
			$group = new Group(uid: $_GET["group"]);
			$groupRepo = new GroupRepository(group: $group);

			if ($groupRepo->getInformations() === null) {
				System::redirect(url: "/");
			}

			if (isset($_SESSION["user"])) {
				$user = new User(uid: $_SESSION["user"]["uid"], groupUid: UserRepository::getGroup(uid: $_SESSION["user"]["uid"]));
				$userRepo = new UserRepository(user: $user);

				$roles = UserRepository::getRoles(uid: $_SESSION["user"]["uid"]);

				echo "<script>const uid = \"" . $_SESSION["user"]["uid"] . "\";</script>";
			}

			require Path::LAYOUT . "/index/election.php";

			System::implementScripts(
				scripts: [
					"/scripts/engine.js",
					"/scripts/theme.js",
					"https://cdn.jsdelivr.net/npm/@tsparticles/confetti@3.4.0/tsparticles.confetti.bundle.min.js",
					"/scripts/election.js"
				]
			);
		} else {
			require Path::COMPONENTS . "/actions/search_bar.php";

			System::implementScripts(scripts: ["/scripts/engine.js", "/scripts/theme.js"]);
		}

		include Path::LAYOUT . "/footer.php";
	}
}
