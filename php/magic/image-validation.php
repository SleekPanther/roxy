<?php
//this entire file is included in a block which already checks if a submit button is set

$targetFolder = IMAGE_POSTER_PATH;
$targetFileName = strtolower( str_replace(' ','-', basename($_FILES["filPosterImageFile"]["name"])) );	//replace spaces with hyphens
$targetFileName = str_replace("'",'', $targetFileName);								//remove single quotes
$targetFilePath= $targetFolder.$targetFileName;

$okToUpload = 1;		//assume it has no errors to start with

$imageFileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
$imageAttributes = getimagesize($_FILES["filPosterImageFile"]["tmp_name"]);
if($imageAttributes === false) {
    $errorMsgMovie[]="Error! File is not an image";
    $okToUpload = 0;
}elseif(!in_array($imageFileType, $VALID_IMAGE_TYPES)) {	//double check extension is on MY LIST of valid images
    $errorMsgMovie[]="Sorry, only image file types are allowed";
    $okToUpload = 0;
}

if (file_exists($targetFilePath)) {
	$errorMsgMovie[]="Sorry, file already exists. Please rename & try again";
	$okToUpload = 0;
}
if ($_FILES["filPosterImageFile"]["size"] > 3000000) {		//size in bytes, so divide by 1000
    $errorMsgMovie[]="Sorry, your file is too large.";
    $okToUpload = 0;
}

if ($okToUpload == 0) {
    $errorMsgMovie[]="Sorry, your file was not uploaded";
} else {
	if(!$errorMsgMovie){
	    if (move_uploaded_file($_FILES["filPosterImageFile"]["tmp_name"], $targetFilePath)) {
	        echo $targetFileName.' pat ' .$targetFilePath;
	    }else{
	    	$errorMsgMovie[]="Sorry, there was an error uploading your file";
	    }
	}
}
?>