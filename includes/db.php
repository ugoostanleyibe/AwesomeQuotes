<?php
	if (!defined("DB_SERVER")) define("DB_SERVER", "localhost", true);
	if (!defined("DB_NAME")) define("DB_NAME", "awesomequotes", true);
	if (!defined("DB_USER")) define("DB_USER", "someusername", true);
	if (!defined("DB_PASS")) define("DB_PASS", "#pa55w0rd", true);

	class DBMgr {
		private $dbConnection;

		function __construct() {
			$this->openDBConnection();
		}

		public function openDBConnection() {
			$this->dbConnection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS, DB_NAME);
			if (mysqli_connect_errno()) die("Database Connection Failed! :(");
		}

		public function closeDBConnection() {
			if ($this->dbConnection) mysqli_close($this->dbConnection);
		}

		public function query($queryStr) {
			$queryResult = mysqli_query($this->dbConnection, $queryStr);
			if (!$queryResult) die("Database Query Failed! :(");
			return $queryResult;
		}

		public function fetchArray($res) {
			return mysqli_fetch_assoc($res);
		}

		public function insertID() {
			return mysqli_insert_id($this->dbConnection);
		}

		public function affectedRows() {
			return mysqli_affected_rows($this->dbConnection);
		}

		public function cleanSQL($string) {
			return mysqli_real_escape_string($this->dbConnection, $string);
		}

		function __destruct() {
			$this->closeDBConnection();
		}
	}

	$dbMgr = new DBMgr();
?>