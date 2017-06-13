<?php
	class Utils {
		public static function niceHTML($string="") {
			return htmlentities($string);
		}

		public static function cleanString($string="") {
			if ($string == "") return "An Empty Quote... Haha...";
			else if (ctype_alpha(substr($string, -1))) return $string;
			else return Utils::cleanString(substr($string, 0, -1));
		}

		public static function redirTo($location="index.php") {
			header("Location: ${location}"); exit;
		}

		public static function centredElementHTML($string="") {
			return "<div class=\"row\">
					<div class=\"col-sm-3\"></div>
					<div class=\"col-sm-6\">${string}</div>
					<div class=\"col-sm-3\"></div>
				</div>";
		}
	}
?>