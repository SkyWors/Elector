<?php

namespace Elector;

use
	PDO,
	Elector\Utils;

class User {
	protected $uid;

	function __construct($uid) {
		$this->uid = $uid;
	}

	public static function add($name, $surname, $password, $uidGroup) {
		$uid = Utils::uidGen(16, DATABASE_USER);
		$passwordHash = password_hash($password, PASSWORD_BCRYPT);
		$identifiant = preg_replace("/[^a-zA-Z]/", "", substr($name, 0, 1)) . "." . preg_replace("/[^a-zA-Z]/", "", explode(" ", $surname)[0]);

		$i = 2;
		while (User::isIdentifiant($identifiant)) {
			$identifiant = substr($identifiant, 0, -1) . $i;
			$i++;
		}

		$query = "INSERT INTO " . DATABASE_USER . " (uid, name, surname, identifiant, password, uid_group) VALUE (:uid, :name, :surname, :identifiant, :password, :uidGroup) RETURNING identifiant";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uid", $uid);
		$stmt->bindParam(":name", $name);
		$stmt->bindParam(":surname", $surname);
		$stmt->bindParam(":identifiant", $identifiant);
		$stmt->bindParam(":password", $passwordHash);
		$stmt->bindParam(":uidGroup", $uidGroup);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0];
	}

	public static function isIdentifiant($identifiant) {
		$query = "SELECT uid FROM " . DATABASE_USER . " WHERE identifiant = :identifiant";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":identifiant", $identifiant);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? null;

		return $result != null ? true : false;
	}

	public static function verified($identifiant, $password) {
		$query = "SELECT uid, password FROM " . DATABASE_USER . " WHERE identifiant = :identifiant";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":identifiant", $identifiant);
		$stmt->execute();
		$result = $stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? null;

		if ($result === null) {
			return null;
		}

		if (password_verify($password, $result["password"])) {
			return $result["uid"];
		}
		return null;
	}

	public function getInformations($uid = null) {
		$uid = isset($uid) ? $uid : $this->uid;

		$query = "SELECT * FROM " . DATABASE_USER . " WHERE uid = :uid";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uid", $uid);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC)[0] ?? null;
	}

	public function isVote($groupUid) {
		$group = new Group($groupUid);
		$round = $group->getElectionState();
		$uidElection = $group->getElection();

		$query = "SELECT uid FROM " . DATABASE_VOTE. " WHERE uid_user = :uidUser AND round = :round AND uid_election = :uidElection";
		$stmt = DATABASE->prepare($query);
		$stmt->bindParam(":uidUser", $this->uid);
		$stmt->bindParam(":uidElection", $uidElection);
		$stmt->bindParam(":round", $round);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
	}

	public function vote($uidElection = null, $uidCandidat = null) {
		$uid = Utils::uidGen(16, DATABASE_VOTE);
		$groupUid = $this->getInformations()["uid_group"];
		$group = new Group($groupUid);
		$round = $group->getElectionState();
		$uidElection = isset($uidElection) ? $uidElection : $group->getElection();

		// Check if vote already existed
		$result = $this->isVote($groupUid);

		if ($result != null) {
			$uid = $result;
			$query = "UPDATE " . DATABASE_VOTE . " SET uid_candidat = :uidCandidat WHERE uid = :uid";

			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uid", $uid);
			$stmt->bindParam(":uidCandidat", $uidCandidat);
		} else {
			$query = "INSERT INTO " . DATABASE_VOTE . " (uid, uid_user, uid_candidat, uid_election, round) VALUES (:uid, :uidUser, :uidCandidat, :uidElection, :round)";

			$stmt = DATABASE->prepare($query);
			$stmt->bindParam(":uid", $uid);
			$stmt->bindParam(":uidUser", $this->uid);
			$stmt->bindParam(":uidCandidat", $uidCandidat);
			$stmt->bindParam(":uidElection", $uidElection);
			$stmt->bindParam(":round", $round);
		}

		$stmt->execute();
	}
}
