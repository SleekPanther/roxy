<?php
$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
$releaseDate=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");
$synopsis=htmlentities($_POST['txtSynopsis'], ENT_QUOTES, "UTF-8");
$poster=htmlentities($_POST['radImageChoose'], ENT_QUOTES, "UTF-8");

if($title==""){
	$errorMsg[]="Title cannot be empty";
	$titleError=true;
}elseif (!verifyAlphaNum($title)) {
	$errorMsg[]="Title cannot have Special Characters";
	$titleError=true;
}

if($runtime==""){
	$errorMsg[]="Runtime cannot be empty";
	$runtimeError=true;
}elseif(!verifyNumeric($runtime)){
	$errorMsg[]="Runtime must be a number";
	$runtimeError=true;
}

//skip rating & visibility validation since listboxes almost impossible to "hack".

if($releaseDate==''){
	$errorMsg[]='Release Date cannot be empty';
	$releaseDateError=true;
}elseif(!validateSqlDate($releaseDate)){
	$errorMsg[]='Release Date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$releaseDateError=true;
}

if($director!='' && !verifyAlphaNum($director)){
	$errorMsg[]="Director text cannot have special characters";
	$directorError=true;
}

if($synopsis !=''){		//only validate if NOT empty
	if(!verifyAlphaNumNewline($synopsis)){
		$errorMsg[]="Synopsis Cannot have special characters";
		$synopsisError=true;
	}
}
?>