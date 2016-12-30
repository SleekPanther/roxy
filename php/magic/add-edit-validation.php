<?php
$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
$releaseDate=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");
$synopsis=htmlentities($_POST['txtSynopsis'], ENT_QUOTES, "UTF-8");
$trailer=htmlentities($_POST['txtTrailer'], ENT_QUOTES, "UTF-8");

if($title==""){
	$errorMsgMovie[]="Title cannot be empty";
	$titleError=true;
}elseif (!verifyAlphaNum($title)) {
	$errorMsgMovie[]="Title cannot have Special Characters";
	$titleError=true;
}

if($runtime==""){
	$errorMsgMovie[]="Runtime cannot be empty";
	$runtimeError=true;
}elseif(!verifyNumeric($runtime)){
	$errorMsgMovie[]="Runtime must be a number";
	$runtimeError=true;
}

//skip rating & visibility validation since listboxes almost impossible to "hack".

if($releaseDate==''){
	$errorMsgMovie[]='Release Date cannot be empty';
	$releaseDateError=true;
}elseif(!validateSqlDate($releaseDate)){
	$errorMsgMovie[]='Release Date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$releaseDateError=true;
}

if($director!='' && !verifyAlphaNum($director)){
	$errorMsgMovie[]="Director text cannot have special characters";
	$directorError=true;
}

if($synopsis !=''){		//only validate if NOT empty
	if(!verifyAlphaNumNewline($synopsis)){
		$errorMsgMovie[]="Synopsis Cannot have special characters";
		$synopsisError=true;
	}
}

if($trailer!=''){		//only check if NOT empty (since it's optional)
	if(!filter_var($trailer, FILTER_VALIDATE_URL)){
		$errorMsgMovie[]='Please enter a valid URL for the Trailer';
		$trailerError=true;
	}else{
		if(!urlExists($trailer)){
			$errorMsgMovie[]='The Trailer link you entered appears to link to a dead page';
			$trailerError=true;
		}
	}
}
?>