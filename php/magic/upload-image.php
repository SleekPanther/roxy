<?php
//this entire file is includes in a block which already check if the submit button is set

$targetFolder = IMAGE_POSTER_PATH.'1/';

$targetFile = $targetFolder.basename($_FILES["filPosterImageFile"]["name"]);
$okToUpload = 1;
$imageFileType = pathinfo($targetFile,PATHINFO_EXTENSION);

$imageAttributes = getimagesize($_FILES["filPosterImageFile"]["tmp_name"]);	//returns 0 if not a valid image
if($imageAttributes === false) {
    $errorMsgMovie[]="Error! File is not an image";
    $okToUpload = 0;
}elseif(!in_array($imageFileType, $VALID_IMAGE_TYPES)) {	//double checl extension is on MY list of valid images
    $errorMsgMovie[]="Sorry, only image file types are allowed";
    $okToUpload = 0;
}

if (file_exists($targetFile)) {
	$errorMsgMovie[]="Sorry, file already exists.";
	$okToUpload = 0;
}
if ($_FILES["filPosterImageFile"]["size"] > 3000000) {		//size in bytes, so divide by 1000
    $errorMsgMovie[]="Sorry, your file is too large.";
    $okToUpload = 0;
}

if ($okToUpload == 0) {
    $errorMsgMovie[]="Sorry, your file was not uploaded.";
} else {
	if(!$errorMsgMovie){
	    if (move_uploaded_file($_FILES["filPosterImageFile"]["tmp_name"], $targetFile)) {
	        $errorMsgMovie[]="The file ". basename( $_FILES["filPosterImageFile"]["name"]). " has been uploaded.";
	    } else {
	        $errorMsgMovie[]="Sorry, there was an error uploading your file.";
	    }
	}
}
?>