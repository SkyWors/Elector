<?php

namespace App\Models\Entities;

class User {
	private ?string $uid;
	private ?string $groupUid;
	private ?string $name;
	private ?string $surname;
	private ?string $identifiant;
	private ?string $password;

	/**
	 * USer __construct
	 * 
	 * @param string $uid
	 * @param string $groupUid
	 * @param string $name
	 * @param string $surname
	 * @param string $identifiant
	 * @param string $password
	 */
	function __construct(string $uid = null, string $groupUid = null, string $name = null, string $surname = null, string $identifiant = null, string $password = null) {
		$this->uid = $uid;
		$this->groupUid = $groupUid;
		$this->name = $name;
		$this->surname = $surname;
		$this->identifiant = $identifiant;
		$this->password = $password;
	}

	public function __set($var, $value) : void {
		$this->$var = $value;
	}

	public function __get($var) : mixed {
		return $this->$var;
	}
}
