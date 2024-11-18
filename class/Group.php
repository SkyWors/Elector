<?php

namespace Elector;

use
	PDO;

class Group {
	private $uid;

	public function __construct($uid) {
		$this->uid = $uid;
	}

	public static function create($name) {
		$uid = Utils::uidGen(6, DATABASE_GROUP);

		$query = "INSERT INTO `" . DATABASE_GROUP . "` (uid, name) VALUES (:uid, :name) RETURNING uid";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uid", $uid);
		$stmt->bindParam(":name", $name);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
	}

	public function createElection() {
		$uid = Utils::uidGen(16, DATABASE_ELECTION);

		$query = "INSERT INTO " . DATABASE_ELECTION . " (uid, uid_group) VALUES (:uid, :uidGroup)";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uid", $uid);
		$stmt->bindParam(":uidGroup", $this->uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? NULL;
	}

	public function getCandidats() {
		$state = $this->getElectionState();

		$query = "";
		if ($state === 1) {
			$query = "SELECT * FROM " . DATABASE_USER . " WHERE uid_group = :uidGroup";

			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uidGroup", $this->uid);
		} elseif ($state === 2) {
			$query = "SELECT * FROM " . DATABASE_USER . " WHERE uid IN (SELECT uid_candidat FROM " . DATABASE_VOTE . " WHERE uid_group = :uidGroup AND round = 1)";

			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uidGroup", $this->uid);
		} else {
			$query = "SELECT u.uid, u.name, u.surname from " . DATABASE_VOTE . " v, user u WHERE v.uid_election = :uidElection AND v.round = 2 AND v.uid_user = u.uid GROUP BY v.uid_candidat ORDER BY count(*) DESC LIMIT 2";

			$uidElection = $this->getElection();
			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uidElection", $uidElection);
		}

		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? NULL;
	}

	public function getInformations() {
		$query = "SELECT * FROM `" . DATABASE_GROUP . "` WHERE uid = :uidgroup";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uidgroup", $this->uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? NULL;
	}

	public function getElectionState() {
		$query = "SELECT id_state FROM " . DATABASE_ELECTION . " WHERE uid_group = :uidgroup";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uidgroup", $this->uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
	}

	public function setElectionState($idState) {
		if (in_array($idState, Utils::getStates())) {
			$query = "UPDATE " . DATABASE_ELECTION . " SET id_state = :idState WHERE uid_group = :uidGroup";
			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uidGroup", $this->uid);
			$stmt->bindParam(":idState", $idState);
			$stmt->execute();
		}
	}

	public function getElection() {
		$query = "SELECT uid FROM " . DATABASE_ELECTION. " WHERE uid_group = :uidGroup";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uidGroup", $this->uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
	}

	public function getResult($round) {
		$uidElection = $this->getElection();

		$query = "select uid_candidat as candidat, count(*) as number from vote where uid_election = :uidElection and round = :round group by uid_candidat order by count(*) desc";

		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uidElection", $uidElection);
		$stmt->bindParam(":round", $round);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? NULL;
	}

	public function getNumber() {
		$query = "SELECT count(*) FROM " . DATABASE_USER . " WHERE uid_group = :uid";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uid", $this->uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
	}
}
