<?php

namespace App\Controllers\API;

use App\Models\Entities\API;
use App\Models\Entities\Group;
use App\Models\Repositories\APIRepository;
use App\Models\Repositories\GroupRepository;

class APIGroupController {
	public function render() : void {
		header(header: "Content-Type: application/json");

		switch ($_SERVER["REQUEST_METHOD"]) {
			case "POST":
				$json = file_get_contents(filename: "php://input");
				$body = json_decode(json: $json);

				if (isset($body->uid)) {
					$group = new Group(uid: $body->uid);
					$groupRepo = new GroupRepository(group: $group);

					if ($groupRepo->getInformations() != null) {
							http_response_code(response_code: 200);

							$data["message"] = "200";
							$data["description"] = "Group $body->uid found.";
							$data["length"] = $groupRepo->getNumber();
					} else {
						http_response_code(response_code: 404);

						$data["error"] = "Not found";
						$data["description"] = "Unknown group.";
					}
				} else {
					http_response_code(response_code: 400);

					$data["error"] = "Invalid request";
					$data["description"] = "Malformed API request.";
				}

				$api = new API(data: $data);
				break;
			default:
				http_response_code(response_code: 404);

				$data["error"] = "Not found";
				$data["description"] = "Unknown API call.";

				$api = new API(data: $data);
				break;
		}

		$apiRepo = new APIRepository(api: $api);
		echo $apiRepo->answer();
	}
}
