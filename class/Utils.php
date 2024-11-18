<?php

namespace Elector;

use
	PDO;

class Utils {

	/**
	 * UID function generator
	 *
	 * @param int $size UID length
	 * @param string $table Database table to check for existing UID
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return string
	 */
	public static function uidGen($size, $table = null) {
		$char = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ_";
		$uid = "";
		$randomByte = random_bytes($size);

		foreach (str_split($randomByte) as $byte) {
			$random = ord($byte) % strlen($char);
			$uid .= $char[$random];
		}

		if (!empty($table)) {
			while ($uid == null) {
				$query = "SELECT uid FROM " . $table . " WHERE uid = :uid";
				$stmt = DATABASE->prepare($query);
				$stmt->bindParam(":uid", $uid);
				$stmt->execute();
				$uid = $stmt->fetchAll(PDO::FETCH_COLUMN)[0] ?? NULL;
			}
		}
		return $uid;
	}

	/**
	 * Header location function replacement
	 *
	 * @param string $page Page destination
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return void
	 */
	public static function head($page) {
		header("Location: " . $page);
		exit;
	}

	public static function getGroups() {
		$query = "SELECT * FROM `" . DATABASE_GROUP ."`";
		$stmt = DATABASE->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_ASSOC) ?? NULL;
	}

	public static function getStates() {
		$query = "SELECT id FROM " . DATABASE_STATE;
		$stmt = DATABASE->prepare($query);
		$stmt->execute();
		return $stmt->fetchAll(PDO::FETCH_COLUMN) ?? NULL;
	}

	public static function getGitBranch() {
		$fname = sprintf( '.git/HEAD' );
		$data = file_get_contents($fname);
		$ar  = explode( "/", $data );
		$ar = array_reverse($ar);
		return  trim ("" . @$ar[0]);
	}

	public static function getGitCommit() {
		$path = sprintf('.git/');
		$head = trim(substr(file_get_contents($path . 'HEAD'), 4));
		$hash = trim(file_get_contents(sprintf($path . $head)));
		return $hash;
	}
}
