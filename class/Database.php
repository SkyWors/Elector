<?php

namespace Elector;

use
	PDO,
	Exception;

class Database {
	private $connection;

	/**
	 * Main database object class
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return void
	 */
	public function __construct() {
		try {
			$hostname = $_ENV["DATABASE_HOST"];
			$port = $_ENV["DATABASE_PORT"];

			$dbname = $_ENV["DATABASE_NAME"];
			$username = $_ENV["DATABASE_USER"];
			$password = $_ENV["DATABASE_PASSWORD"];

			$driver = $_ENV["DATABASE_DRIVER"];
			$charset = $_ENV["DATABASE_CHARSET"];

			$this->connection = new PDO("$driver:dbname=$dbname; host=$hostname; port=$port; options='--client_encoding=$charset'", $username, $password);
			$this->connection->exec("SET NAMES '$charset'");
		} catch (Exception $e) {}
	}

	/**
	 * Return database connection
	 *
	 * @author Erick Paoletti <erick.paoletti@gmail.com>
	 *
	 * @return PDO
	 */
	public function getConnection() {
		return $this->connection;
	}
}
