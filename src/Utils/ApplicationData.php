<?php

namespace App\Utils;

use App\Configs\Database;
use PDO;

class ApplicationData {

	/**
	 * Database request
	 *
	 * @param string $query
	 * @param array $data
	 * @param int $returnType
	 * @param bool $singleValue
	 *
	 * @return mixed
	 */
	public static function request(string $query, array $data = null, int $returnType = null, bool $singleValue = false) : mixed {
		$stmt = DATABASE->prepare(query: $query);

		if ($data) {
			foreach (array_keys($data) as $key) {
				$stmt->bindParam(
					param: $key,
					var: $data[$key]
				);
			}
		}

		$stmt->execute();

		if ($returnType) {
			return $singleValue ? $stmt->fetchAll($returnType)[0] ?? null : $stmt->fetchAll($returnType) ?? null;
		}

		return 0;
	}

	/**
	 * Return every users uid
	 *
	 * @return array
	 */
	public static function getUsers() : array {
		return ApplicationData::request(
			query: "SELECT uid FROM " . Database::USERS,
			returnType: PDO::FETCH_COLUMN
		);
	}

	/**
	 * Return every groups
	 *
	 * @return array
	 */
	public static function getGroups() : array {
		return ApplicationData::request(
			query: "SELECT * FROM `" . Database::GROUPS ."`",
			returnType: PDO::FETCH_ASSOC
		);
	}

	/**
	 * Return every states
	 *
	 * @return array
	 */
	public static function getStates() : array {
		return ApplicationData::request(
			query: "SELECT id FROM " . Database::STATES,
			returnType: PDO::FETCH_COLUMN
		);
	}
}