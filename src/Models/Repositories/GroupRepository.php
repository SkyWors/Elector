<?php

namespace App\Models\Repositories;

use App\Configs\Database;
use App\Models\Entities\Group;
use App\Utils\ApplicationData;
use App\Utils\System;
use PDO;

class GroupRepository {
	private $group;

	/**
	 * Database construct
	 *
	 * @param Group $group
	 */
	public function __construct(Group $group) {
		$this->group = $group;
	}

	/**
	 * User's create
	 *
	 * @return string
	 */
	public function create() : string {
		$this->group->uid = System::uidGen(size: 6, table: Database::GROUPS);

		return ApplicationData::request(
			query: "INSERT INTO `" . Database::GROUPS . "` (uid, name) VALUES (:uid, :name) RETURNING uid",
			data: [
				"uid" => $this->group->uid,
				"name" => $this->group->name
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}

	/**
	 * Create election
	 *
	 * @return void
	 */
	public function createElection() : void {
		$uid = System::uidGen(size: 16, table: Database::ELECTION);

		ApplicationData::request(
			query: "INSERT INTO " . Database::ELECTION . " (uid, uid_group) VALUES (:uid, :uidGroup)",
			data: [
				"uid" => $uid,
				"uidGroup" => $this->group->uid
			]
		);
	}

	/**
	 * Get group election's candidats
	 *
	 * @return array
	 */
	public function getCandidats() : array {
		$state = $this->getElectionState();

		$query = "";
		if ($state === 1) {
			$query = "SELECT * FROM " . Database::USERS . " WHERE uid_group = :uidGroup ORDER BY surname";

			$data = ["uidGroup" => $this->group->uid];
		} elseif ($state === 2) {
			$query = "SELECT * FROM " . Database::USERS . " WHERE uid IN (SELECT uid_candidat FROM " . Database::VOTE . " WHERE uid_group = :uidGroup AND round = 1) ORDER BY surname";

			$data = ["uidGroup" => $this->group->uid];
		} else {
			$query = "SELECT u.uid, u.name, u.surname from " . Database::VOTE . " v, " . Database::USERS . " u WHERE v.uid_election = :uidElection AND v.round = 2 AND v.uid_candidat = u.uid GROUP BY v.uid_candidat ORDER BY count(*) DESC, u.surname LIMIT 2";

			$data = ["uidElection" => $this->getElection()];
		}

		return ApplicationData::request(
			query: $query,
			data: $data,
			returnType: PDO::FETCH_ASSOC
		);
	}

	/**
	 * Get group informations
	 *
	 * @return array | null
	 */
	public function getInformations() : array | null {
		return ApplicationData::request(
			query: "SELECT * FROM `" . Database::GROUPS . "` WHERE uid = :uid",
			data: [
				"uid" => $this->group->uid
			],
			returnType: PDO::FETCH_ASSOC,
			singleValue: true
		);
	}

	/**
	 * Get election state
	 *
	 * @return int | null
	 */
	public function getElectionState() : int | null {
		return ApplicationData::request(
			query: "SELECT id_state FROM " . Database::ELECTION . " WHERE uid_group = :uidGroup",
			data: [
				"uidGroup" => $this->group->uid
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}

	/**
	 * Set election state
	 *
	 * @param int $idState
	 *
	 * @return void
	 */
	public function setElectionState(int $idState) : void {
		if (in_array(needle: $idState, haystack: ApplicationData::getStates())) {
			ApplicationData::request(
				query: "UPDATE " . Database::ELECTION . " SET id_state = :idState WHERE uid_group = :uidGroup",
				data: [
					"uidGroup" => $this->group->uid,
					"idState" => $idState
				]
			);
		}
	}

	/**
	 * Get election uid
	 *
	 * @return string | null
	 */
	public function getElection() : string | null {
		return ApplicationData::request(
			query: "SELECT uid FROM " . Database::ELECTION . " WHERE uid_group = :uidGroup",
			data: [
				"uidGroup" => $this->group->uid
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}

	/**
	 * Get election's result
	 *
	 * @param int $round
	 *
	 * @return array
	 */
	public function getResult(int $round) : array {
		$uidElection = $this->getElection();

		return ApplicationData::request(
			query: "SELECT uid_candidat as candidat, count(*) as number from " . Database::VOTE . " where uid_election = :uidElection and round = :round group by uid_candidat order by count(*) desc",
			data: [
				"uidElection" => $uidElection,
				"round" => $round
			],
			returnType: PDO::FETCH_ASSOC
		);
	}

	/**
	 * Get group student's number
	 *
	 * @return int
	 */
	public function getNumber() : int {
		return ApplicationData::request(
			query: "SELECT count(*) FROM " . Database::USERS . " WHERE uid_group = :uid",
			data: [
				"uid" => $this->group->uid
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}
}
