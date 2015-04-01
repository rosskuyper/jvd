<?php
namespace Jvd;
use PDO;

class DB {
	/**
	 * PDO object
	 * @var PDO
	 */
	protected $pdo;

	/**
	 * Construct and connect to DB
	 */
	public function __construct($host, $username, $password, $dbname, $port = '3306') {
		$dsn = 'mysql:dbname=' . $dbname . ';host=' . $host . ';port='.$port .';charset=UTF8';

		$this->pdo = new PDO($dsn, $username, $password);
	}

	/**
	 * @param string $sql
	 * @param array  $params
	 * @return \PDOStatement
	 */
	public function query($sql, $params = array()) {
		$statement = $this->pdo->prepare($sql);

		// Bind params
		foreach ($params as $index => $param) {
			if (is_numeric($var))
				$type = PDO::PARAM_INT;
			elseif (is_bool($var))
				$type = PDO::PARAM_BOOL;
			elseif (is_null($var))
				$type = PDO::PARAM_NULL;
			else
				$type = PDO::PARAM_STR;

			$statement->bindValue($index + 1, $param, $type);
		}

		$statement->execute();

		return $statement;
	}

	/**
	 * @param \PDOStatement $stmt
	 * @param type $vars
	 */
	protected static function bindVars(\PDOStatement &$stmt, $vars) {
		if (!is_array($vars)) {
			throw new \Exception("vars needs to be an array in PSQL");
		}
		foreach ($vars as $key => &$var) {
			if (is_string($key)) {
				if (is_numeric($var)) {
					$type = \PDO::PARAM_INT;
				} else if (is_bool($var)) {
					$type = \PDO::PARAM_BOOL;
				} else if (is_null($var)) {
					$type = \PDO::PARAM_NULL;
				} else {
					$type = \PDO::PARAM_STR;
				}
				$stmt->bindParam($key, $var, $type);
			} else {
				$stmt->bindValue($key + 1, $var);
			}
		}
	}
}
