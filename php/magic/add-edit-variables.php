<?php
$titleError=false;
$runtimeError=false;
$ratingError=false;
$releaseDateError=false;
$displayError=false;
$directorError=false;
$synopsisError=false;
$trailerError=false;
$posterError=false;

$errorMsgMovie=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");		//only valid options for MPAA ratings listbox
	$displayOptions=array('Current','Hidden');		//only valid options for display listbox
?>