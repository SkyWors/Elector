<?php

namespace App\Controllers;

use App\Configs\Path;
use App\Events\AddGroupEvent;
use App\Models\Entities\Group;
use App\Models\Repositories\GroupRepository;
use App\Utils\System;

class DashboardController {
	public function render() : void {
		require Path::LAYOUT . "/header.php";

		require Path::LAYOUT . "/navbar.php";

		if (isset($_GET["group"])) {
			$group = new Group(uid: $_GET["group"]);
			$groupRepo = new GroupRepository(group: $group);

			require Path::LAYOUT . "/dashboard/group.php";
		} else {
			AddGroupEvent::implement();

			require Path::LAYOUT . "/dashboard/groups.php";

			require Path::COMPONENTS . "/forms/add_group.php";

			require Path::COMPONENTS . "/actions/refresh_button.php";
		}

		System::implementScripts(
			scripts: [
				"/scripts/engine.js",
				"/scripts/theme.js",
				"/scripts/dashboard.js"
			]
		);

		include Path::LAYOUT . "/footer.php";
	}
}
