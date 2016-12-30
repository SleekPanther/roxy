<?php
//this entire file is included in a block which already checks if a submit button is set
$posterLinkPath=getFullPosterLinkPath($poster);		//if movie already exists, make sure it has valid image

$targetFolder = IMAGE_POSTER_PATH;
$targetFileName=basename($_FILES["filPosterImageFile"]["name"]);

$containingFolderAndFileName=$parentFolder.'/'.$fileName;
if($containingFolderAndFileName=='magic/edit.php' && file_exists($posterLinkPath) && !$targetFileName){
		//do nothing since this is edit.php with a valid form & no new file set to replace it
}
else{
	if(!$targetFileName){
		$errorMsgMovie[]='Please add a Poster image';
		$posterError=true;
	}else{
		$targetFileName = strtolower( str_replace(' ','-', $targetFileName) );			//replace spaces with hyphens
		$targetFileName = str_replace("'",'', $targetFileName);							//remove single quotes
		$targetFilePath= $targetFolder.$targetFileName;

		$okToUpload = 1;		//assume it has no errors to start with

		$imageFileType = pathinfo($targetFilePath,PATHINFO_EXTENSION);
		$imageAttributes = getimagesize($_FILES["filPosterImageFile"]["tmp_name"]);
		if($imageAttributes === false) {
			$errorMsgMovie[]="Error! File is not an image or is too large";
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
			$posterError=true;
			$errorMsgMovie[]="Sorry, your file was not uploaded";
		} else {
			if(!$errorMsgMovie){
				if (move_uploaded_file($_FILES["filPosterImageFile"]["tmp_name"], $targetFilePath)) {
					$oldPosterToDelete=$poster;
			    	//most important line: actually saves filename so it can be stored in database
					$poster=$targetFileName;
				}else{
					$errorMsgMovie[]="Sorry, there was an error uploading your file";
				}
			}
		}
	}
}
?>