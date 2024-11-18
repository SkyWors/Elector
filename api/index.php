<?php

use
	Elector\Api,
	Elector\Group,
	Elector\Utils,
	Elector\User;

header('Content-Type: application/json');

switch ($_SERVER["REQUEST_METHOD"]) {
	case "POST":
		try {
			$json = file_get_contents("php://input");
			$data = json_decode($json);

			if (property_exists($data, "ask")) {
				switch ($data->ask) {
					case "create_election":
						$group = new Group($data->uid);
						if ($group->getElection() == null) {
							$group->createElection();
							echo Api::returnMessage("Election created!");
							break;
						}
						echo Api::returnMessage("Election already created.");
						break;

					case "state_election":
						$group = new Group($data->uid);
						if ($group->getElection() != null) {
							$group->setElectionState($data->state);
							echo Api::returnMessage("Election state changed to $data->state!");
							break;
						}
						echo Api::returnMessage("Election not started.");
						break;

					case "group_exist":
						$group = new Group($data->uid);
						$groupInformations = $group->getInformations();
						echo $groupInformations != null ? Api::returnMessage(true) : Api::returnMessage(false);
						break;

					case "vote":
						$user = new User($data->uid);
						$user->vote(uidCandidat: $data->candidat);
						echo Api::returnMessage("Vote saved!");
						break;

					case "group_result":
						$group = new Group($data->uid);
						$result = $group->getResult($data->round);
						echo Api::returnData($result);
						break;

					case "group_number":
						$group = new Group($data->uid);
						echo Api::returnData($group->getNumber());
						break;

					case "vote_exist":
						$user = new User($data->uid);
						echo $user->isVote($user->getInformations()["uid_group"]) != null ? Api::returnMessage(true) : Api::returnMessage(false);
						break;
						
					default:
						echo Api::returnMessage("Malformed api ask.");
				}
			} else {
				echo Api::returnMessage("Malformed api ask.");
			}
		} catch (Exception) {}
		break;
	default:
		Utils::head("/notfound");
}
