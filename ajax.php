<?php
	require_once "includes/db.php";
	require_once "includes/utils.php";
	require_once "includes/quote.php";

	$action = $_POST["action"];
	$string = $_POST["string"];
	$id = $_POST["id"];
	$quote = null;

	switch ($action) {
		case 'save': $quote = new Quote($string); $quote->saveToDB(); break;
		case 'update': $quote = new Quote($string, $id); $quote->saveToDB(); break;
		case 'delete': $quote = new Quote($string, $id); $quote->deleteFromDB(); break;
	}

	$quoteDivHTML = Quote::panelHTML($quote->getText(), $quote->getID());
	$numQuotes = count(Quote::grabAll());
	$returnID = $quote->getID();
	$retStr = $quote->getText();

	echo json_encode(["string"=>$retStr, "id"=>$returnID,
		"html"=>$quoteDivHTML, "count"=>$numQuotes]);
?>