<?php

namespace App\Controllers\API;

use App\Models\Entities\API;
use App\Models\Entities\User;
use App\Models\Repositories\APIRepository;
use App\Models\Repositories\UserRepository;

class APIUserVoteController {
	public function render() : void {
		header(header: "Content-Type: application/json");

		switch ($_SERVER["REQUEST_METHOD"]) {
			case "POST":
				$json = file_get_contents(filename: "php://input");
				$body = json_decode(json: $json);

				if (isset($body->uid)) {
					http_response_code(response_code: 200);

					$user = new User(uid: $body->uid);
					$userRepo = new UserRepository(user: $user);

					$data["user"] = $body->uid;
					$data["vote"] = $userRepo->isVote() ? true : false;
				} else {
					http_response_code(response_code: 400);

					$data["error"] = "Invalid request";
					$data["description"] = "Unspecified UID.";
				}

				$api = new API(data: $data);
				break;
			case "PUT":
				$json = file_get_contents(filename: "php://input");
				$body = json_decode(json: $json);

				if (
					isset($body->uid)
					&& isset($body->candidatUid)
					&& isset($body->groupUid)
				) {
					$user = new User(uid: $body->uid, groupUid: $body->groupUid);
					$userRepo = new UserRepository(user: $user);

					if (
						$userRepo::getGroup(uid: $body->uid) != null
						&& $userRepo::getGroup(uid: $body->candidatUid) != null
					) {
						http_response_code(response_code: 200);

						$userRepo->vote(uidCandidat: $body->candidatUid);

						$data["message"] = "200";
						$data["description"] = "$body->uid's vote saved.";
					} else {
						http_response_code(response_code: 404);

						$data["error"] = "Not found";
						$data["description"] = "Unknown users.";
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
