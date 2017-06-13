<?php
	require_once "includes/db.php";
	require_once "includes/utils.php";
	require_once "includes/quote.php";

	$quotes = Quote::grabAll();
	$numQuotes = count($quotes); $quotesHTMLPile = [];
	$quoteAreaInstr = "Type In A Quote You Wish To Add Here...";
	$numQuotesBadge = "<span class=\"badge\">${numQuotes}</span>";
	$pageHeader = Utils::centredElementHTML("<h1>Awesome Quotes ${numQuotesBadge}</h1>");

	foreach ($quotes as $quote) {
		$quotesHTMLPile[] = Quote::panelHTML($quote->getText(), $quote->getID());
	}

	$quotesHTML = empty($quotesHTMLPile) ? "" : join("\n", $quotesHTMLPile);

echo <<<pageHTML
<!DOCTYPE html>

<html lang="en">
	<head>
		<meta charset="UTF-8">
		<title>PHPMySQL</title>
		<script src="scripts/jquery.min.js"></script>
		<script src="scripts/jquery-ui.min.js"></script>
		<script src="scripts/bootstrap.min.js"></script>
		<link rel="stylesheet" type="text/css" href="styles/jquery-ui.min.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap.min.css">
		<link rel="stylesheet" type="text/css" href="styles/font-awesome.min.css">
		<link rel="stylesheet" type="text/css" href="styles/bootstrap-theme.min.css">
		<style>
			div {
				font-family: "Segoe UI", Arial, sans-serif; text-align: center;
			}
			textarea#quoteArea {
				font-size: 24px; text-align: justify;
				resize: none; white-space: normal;
			}
			div.well, div.panel-group {
				margin-bottom: 100px;
			}
			div.alert, ::-webkit-scrollbar {
				display: none;
			}
		</style>
		<script>
			$(function() {
				var quoteID = 0;
				var editingQuote = false;
				var showingNotification = false;
				var scrollTo = function(scrollLocation, time) {
					var scrollTop;
					switch (scrollLocation) {
						default: scrollTop = $(scrollLocation).position().top; break;
						case "BOTTOM": scrollTop = $(document).height(); break;
						case "TOP": scrollTop = 0; break;
					}
					$("html, body").animate({scrollTop: scrollTop}, time);
				};
				var performBTSAction = function(action, string, id, callback) {
					$.post("ajax.php", {
						action: action, string: string, id: id
					}, callback, "json");
				};
				var showNotif = function(msg, cls, time, data) {
					if (data) $(".badge").html(data.count);
					showingNotification = true;
					var alertDiv = $("#notif");
					alertDiv.addClass(cls);
					alertDiv.html("<h5><strong>"+msg+"</strong></h5>");
					alertDiv.slideToggle(500);
					setTimeout(function() {
						alertDiv.slideToggle(500);
						setTimeout(function() {
							alertDiv.removeClass(cls); showingNotification = false;
							if (cls == "alert-warning") scrollTo("TOP", 2000);
							else if (cls == "alert-success") {
								scrollTo("BOTTOM", 2000);
								setTimeout(function() {
									$(".panel-group").append(data.html);
									var quoteDiv = $("#quoteDiv"+data.id);
									quoteDiv.css("display", "none");
									quoteDiv.slideToggle(1000);
								}, 1000);
							} else if (cls == "alert-info") {
								var quoteDivID = "#quoteDiv"+quoteID;
								scrollTo(quoteDivID, 1500); quoteID = 0;
								setTimeout(function() {
									var quoteDiv = $("#quoteBody"+data.id);
									var quoteHeader = $("#quote"+data.id);
									quoteDiv.slideToggle(750);
									setTimeout(function() {
										quoteHeader.html(data.string);
										quoteDiv.slideToggle(750);
									}, 800);
								}, 1500);
							}
						}, 500);
					}, time);
				};
				$(document).on("click", "a", function(event) {
					event.preventDefault();
					var linkID = $(this).attr("id");
					if (linkID.startsWith("save")) {
						var quote = $("#quoteArea").val().trim();
						if (editingQuote && quote && !showingNotification) {
							$("#quoteArea").val("");
							performBTSAction("update", quote, quoteID, function(data) {
								showNotif("Quote Updated!", "alert-info", 1000, data);
								editingQuote = false;
							});
						} else if (quote && !showingNotification) {
							$("#quoteArea").val("");
							performBTSAction("save", quote, quoteID, function(data) {
								showNotif("Quote Saved!", "alert-success", 1000, data);
								editingQuote = false;
							});
						} else if (!showingNotification) {
							showNotif("Type In A Quote First!", "alert-warning", 2000);
							editingQuote = false;
						}
					} else if (linkID.startsWith("edit")) {
						var quoteIDStr = linkID.split("editQuote").join("");
						var quote = $("#quote"+quoteIDStr).text();
						quoteID = Number(quoteIDStr);
						$("#quoteArea").val(quote);
						scrollTo("TOP", 1000);
						setTimeout(function() {
							$("#quoteArea").focus(); editingQuote = true;
						}, 1000);
						
					} else if (linkID.startsWith("delete")) {
						var quoteIDStr = linkID.split("deleteQuote").join("");
						var quote = $("#quote"+quoteIDStr).text();
						quoteID = Number(quoteIDStr);
						performBTSAction("delete", quote, quoteID, function(data) {
							var quoteDiv = $("#quoteDiv"+data.id);
							var quoteHeader = $("#quote"+data.id);
							quoteDiv.slideToggle(1000);
							setTimeout(function() {
								scrollTo("TOP", 1000);
							}, 1000);
							setTimeout(function() {
								showNotif("Quote Deleted!", "alert-warning", 1500, data);
								editingQuote = false; quoteID = 0;
							}, 2000);
						});
					}
				});
			});
		</script>
	</head>

	<body>
		<div class="container">
			<div class="page-header">
				${pageHeader}
			</div>
			<div class="well well-sm">
				<form id="quoteForm">
					<div class="form-group">
						<textarea id="quoteArea" class="form-control" name="quoteArea"
						placeholder="${quoteAreaInstr}" rows="3" maxlength="240"
						spellcheck="true" required></textarea> <br>
						<a id="saveQuote" href="">Save Quote</a>
					</div>
				</form>
			</div>
			<div id="notif" class="alert">
				<!-- For All Notifications... -->
			</div>
			<div class="panel-group">
				${quotesHTML}
			</div>
		</div>
	</body>
</html>
pageHTML;
?>