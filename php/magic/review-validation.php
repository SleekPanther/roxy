<?php
$_SESSION['reviewAuthor']=htmlentities($_POST['txtAuthor'], ENT_QUOTES, "UTF-8");
$_SESSION['reviewDate']=htmlentities($_POST['datReviewDate'], ENT_QUOTES, "UTF-8");
$_SESSION['reviewSource']=htmlentities($_POST['txtReviewSource'], ENT_QUOTES, "UTF-8");
$_SESSION['reviewLink']=htmlentities($_POST['txtReviewLink'], ENT_QUOTES, "UTF-8");
$_SESSION['review']=htmlentities($_POST['txtReview'], ENT_QUOTES, "UTF-8");

if(!verifyAlphaNum($_SESSION['reviewAuthor'])) {
	$errorMsg[]='Review Author cannot have special characters';
	$reviewAuthorError=true;
}

if($_SESSION['reviewDate']==''){
	$errorMsg[]='Review date cannot be empty';
	$reviewDateError=true;
}elseif(!validateSqlDate($_SESSION['reviewDate'])){
	$errorMsg[]='Review date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$reviewDateError=true;
}

if(($_SESSION['reviewSource']!='') && !verifyAlphaNum($_SESSION['reviewSource'])) {		//only check if NOT empty
	$errorMsg[]='Review Source Text cannot have special characters';
	$reviewSourceError=true;
}

if(($_SESSION['reviewSource']!='') && ($_SESSION['reviewLink']!='') ) {		//only check if both are NOT empty
	if(!filter_var($_SESSION['reviewLink'], FILTER_VALIDATE_URL)){
		$errorMsg[]='Please enter a valid URL';
		$reviewLinkError=true;
	}else{
		$headers = get_headers($_SESSION['reviewLink']);
		if(!strpos($headers[0], '200')){
			$errorMsg[]='The link you entered appears to link to a dead page';
			$reviewLinkError=true;
		}
	}
}

if($_SESSION['review']==''){
	$errorMsg[]='Review cannot be empty';
	$reviewError=true;
}elseif(!verifyAlphaNumNewline($_SESSION['review'])) {
	$errorMsg[]='Review cannot have special characters';
	$reviewError=true;
}

?>