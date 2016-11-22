<?php
echo "<pre>";
print_r($_POST);
echo "</pre>";
$reviewAuthor=htmlentities($_POST['txtAuthor'], ENT_QUOTES, "UTF-8");
$reviewDate=htmlentities($_POST['datReviewDate'], ENT_QUOTES, "UTF-8");
$reviewSource=htmlentities($_POST['txtReviewSource'], ENT_QUOTES, "UTF-8");
$review=htmlentities($_POST['txtReview'], ENT_QUOTES, "UTF-8");

if(!verifyAlphaNum($reviewAuthor)) {
	$errorMsg[]='Review Author cannot have special characters';
	$reviewAuthorError=true;
}

if($reviewDate==''){
	$errorMsg[]='Review date cannot be empty';
	$reviewDateError=true;
}elseif(!validateSqlDate($reviewDate)){
	$errorMsg[]='Review date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$reviewDateError=true;
}

if(!verifyAlphaNum($reviewSource)) {
	$errorMsg[]='Review Source cannot have special characters';
	$reviewSourceError=true;
}

if(!verifyAlphaNum($review)) {
	$errorMsg[]='Review cannot have special characters';
	$reviewError=true;
}

?>