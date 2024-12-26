<?php

namespace App\Models\Repositories;

use App\Configs\Database;
use App\Models\Entities\Group;
use App\Models\Entities\User;
use App\Utils\ApplicationData;
use App\Utils\System;
use Exception;
use PDO;

class UserRepository {
	private $user;

	/**
	 * User construct
	 *
	 * @param User $user
	 */
	public function __construct(User $user) {
		$this->user = $user;
	}

	/**
	 * Create user
	 *
	 * @return Exception | string
	 */
	public function create() : Exception | string {
		$this->user->uid = System::uidGen(size: 16, table: Database::USERS);
		$this->user->password = password_hash(password: $this->user->password, algo: PASSWORD_BCRYPT);

		$this->user->identifiant = preg_replace(pattern: "/[^a-zA-Z]/", replacement: "", subject: substr(string: $this->user->name, offset: 0, length: 1)) . "." . preg_replace(pattern: "/[^a-zA-Z]/", replacement: "", subject: explode(separator: " ", string: $this->user->surname)[0]);

		$i = 2;
		while (UserRepository::isIdentifiant(identifiant: $this->user->identifiant)) {
			$this->user->identifiant = substr(string: $this->user->identifiant, offset: 0, length: -1) . $i;
			$i++;
		}

		try {
			ApplicationData::request(
				query: "INSERT INTO " . Database::USERS . " (uid, name, surname, identifiant, password, uid_group) VALUES (:uid, :name, :surname, :identifiant, :password, :uidGroup)",
				data: [
					"uid" => $this->user->uid,
					"name" => $this->user->name,
					"surname" => $this->user->surname,
					"identifiant" => $this->user->identifiant,
					"password" => $this->user->password,
					"uidGroup" => $this->user->groupUid
				]
			);
		} catch (Exception $exception) {
			return $exception;
		}

		return $this->user->identifiant;
	}

	/**
	 * Verify user password
	 *
	 * @return Exception | string
	 */
	public function verifyPassword() : Exception | string {
		$userData = ApplicationData::request(
			query: "SELECT uid, password FROM " . Database::USERS . " WHERE identifiant = :identifiant",
			data: [
				"identifiant" => $this->user->identifiant
			],
			returnType: PDO::FETCH_ASSOC,
			singleValue: true
		);

		if ($userData != null) {
			if (password_verify(password: $this->user->password, hash: $userData["password"])) {
				return $userData["uid"];
			} else {
				return new Exception(message: "Wrong password");
			}
		}

		return new Exception(message: "Unknown user");
	}

	/**
	 * Get user's role(s)
	 *
	 * @param string $uid User's UID
	 *
	 * @return array
	 */
	public static function getRoles(string $uid) : array {
		return ApplicationData::request(
			query: "SELECT id_role FROM " . Database::USER_ROLE . " WHERE uid_user = :uid",
			data: [
				"uid" => $uid
			],
			returnType: PDO::FETCH_COLUMN
		);
	}

	/**
	 * Get user's group
	 *
	 * @param string $uid User's UID
	 *
	 * @return array | null
	 */
	public static function getGroup(string $uid) : string | null {
		return ApplicationData::request(
			query: "SELECT uid_group FROM " . Database::USERS . " WHERE uid = :uid",
			data: [
				"uid" => $uid
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}

	/**
	 * Check if user's identifiant exist
	 *
	 * @param string $identifiant
	 *
	 * @return bool
	 */
	public static function isIdentifiant(string $identifiant) : bool {
		$result = ApplicationData::request(
			query: "SELECT uid FROM " . Database::USERS . " WHERE identifiant = :identifiant",
			data: [
				"identifiant" => $identifiant
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);

		return $result != null ? true : false;
	}

	/**
	 * Check if user already vote
	 *
	 * @return string | null
	 */
	public function isVote() : string | null {
		$group = new Group(uid: $this->user->groupUid);
		$groupRepo = new GroupRepository(group: $group);
		$round = $groupRepo->getElectionState();
		$uidElection = $groupRepo->getElection();

		return ApplicationData::request(
			query: "SELECT uid FROM " . Database::VOTE . " WHERE uid_user = :uidUser AND round = :round AND uid_election = :uidElection",
			data: [
				"uidUser" => $this->user->uid,
				"uidElection" => $uidElection,
				"round" => $round
			],
			returnType: PDO::FETCH_COLUMN,
			singleValue: true
		);
	}

	/**
	 * Save user's vote
	 *
	 * @param string $uidElection
	 * @param string $uidCandidat
	 *
	 * @return void
	 */
	public function vote(string $uidElection = null, string $uidCandidat = null) : void {
		$uid = System::uidGen(size: 16, table: Database::VOTE);
		$group = new Group(uid: $this->user->groupUid);
		$groupRepo = new GroupRepository(group: $group);
		$round = $groupRepo->getElectionState();
		$uidElection = isset($uidElection) ? $uidElection : $groupRepo->getElection();

		$result = $this->isVote();

		if ($result != null) {
			ApplicationData::request(
				query: "UPDATE " . Database::VOTE . " SET uid_candidat = :uidCandidat WHERE uid = :uid",
				data: [
					"uid" => $result,
					"uidCandidat" => $uidCandidat
				]
			);
		} else {
			ApplicationData::request(
				query: "INSERT INTO " . Database::VOTE . " (uid, uid_user, uid_candidat, uid_election, round) VALUES (:uid, :uidUser, :uidCandidat, :uidElection, :round)",
				data: [
					"uid" => $uid,
					"uidUser" => $this->user->uid,
					"uidCandidat" => $uidCandidat,
					"uidElection" => $uidElection,
					"round" => $round
				]
			);
		}
	}
}
