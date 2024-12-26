<?php

namespace App\Models\Entities;

class Group {
	private ?string $uid;
	private ?string $name;

	/**
	 * Database __construct
	 *
	 * @param string $uid
	 * @param string $name
	 */
	public function __construct(string $uid = null, string $name = null) {
		$this->uid = $uid;
		$this->name = $name;
	}

	public function __set($var, $value) : void {
		$this->$var = $value;
	}

	public function __get($var) : mixed {
		return $this->$var;
	}
}
