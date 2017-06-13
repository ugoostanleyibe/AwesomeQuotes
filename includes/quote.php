<?php
	class Quote {
		private $id;
		private $text;

		function __construct($text="", $id=null) {
			$this->text = $text; $this->id = $id;
		}

		public function getID() {
			return $this->id;
		}

		public function getText() {
			return $this->text;
		}

		public function setText($text) {
			$this->text = $text;
		}

		protected function make() {
			global $dbMgr;
			$queryStr = "INSERT into quotes (text) ";
			$this->text = ucwords(Utils::cleanString($this->text));
			$perfectText = $dbMgr->cleanSQL($this->text);
			$queryStr .= "VALUES ('{$perfectText}')";
			if (!($dbMgr->query($queryStr))) return false;
			$this->id = $dbMgr->insertID(); return true;
		}

		protected function update() {
			global $dbMgr; $id = $this->id;
			$queryStr = "UPDATE quotes SET ";
			$this->text = ucwords(Utils::cleanString($this->text));
			$perfectText = $dbMgr->cleanSQL($this->text);
			$queryStr .= "text = '{$perfectText}' ";
			$queryStr .= "WHERE id = {$id}";
			$dbMgr->query($queryStr);
			return ($dbMgr->affectedRows() == 1);
		}

		public function saveToDB() {
			return $this->id == null ? $this->make() : $this->update();
		}

		public function deleteFromDB() {
			global $dbMgr; $id = $this->id;
			$queryStr = "DELETE FROM quotes ";
			$queryStr .= "WHERE id = {$id} LIMIT 1";
			$dbMgr->query($queryStr);
			return ($dbMgr->affectedRows() == 1);
		}

		public static function grabAll() {
			return Quote::grabByQuery("SELECT * FROM quotes ORDER BY id ASC");
		}

		public static function grabByID($id=1) {
			return Quote::grabByQuery("SELECT * FROM quotes WHERE id = {$id}")[0];
		}

		protected static function grabByQuery($queryStr) {
			global $dbMgr; $quotes = [];
			$res = $dbMgr->query($queryStr);
			while ($row = $dbMgr->fetchArray($res))
				$quotes[] = new Quote($row["text"], $row["id"]);
			return $quotes;
		}

		public static function panelHTML($quoteText, $quoteID) {
			$quoteText = Utils::niceHTML($quoteText);
			return "
				<div id=\"quoteDiv{$quoteID}\" class=\"panel panel-default\">
					<div id=\"quoteHeading{$quoteID}\" class=\"panel-heading\">
						<h6>{$quoteID}<br><i class=\"fa fa-caret-down\"></i></h6>
					</div>
					<div id=\"quoteBody{$quoteID}\" class=\"panel-body\">
						<h3 id=\"quote{$quoteID}\">{$quoteText}</h3> <hr>
						<a id=\"editQuote{$quoteID}\" href=\"\">Edit</a>&nbsp;&nbsp;&nbsp;
						<a id=\"deleteQuote{$quoteID}\" href=\"\">Delete</a>
					</div>
				</div>";
		}
	}
?>