<?php
if ($errorMsg) {
	echo "<div id='errors'>\n";
	echo "<h1>Your form has the following mistakes</h1>\n";
	echo "<ol>\n";
	foreach ($errorMsg as $err) {
		echo "<li>" .$err . "</li>\n";
	}
	echo "</ol>\n";
	echo "</div>\n";
}
?>