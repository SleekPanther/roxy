<?php
// if(isset($_POST['btnImgSubmit'])){
printArray($_POST);
echo 'form works 1234';
//this entire file is includes in a block which already check if the submit button is set

$target_dir = IMAGE_POSTER_PATH.'1/';	//test if folder need to exist

$target_file = $target_dir.basename($_FILES["filPosterImageFile"]["name"]);
$uploadOk = 1;
$imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
// Check if image file is a actual image or fake image
// if(isset($_POST["submit"])) {
	//rename check
    $check = getimagesize($_FILES["filPosterImageFile"]["tmp_name"]);
    if($check !== false) {
        echo "File is an image - " . $check["mime"] . ".";
        //redundant
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if (file_exists($target_file)) {
		echo "Sorry, file already exists.";
		$uploadOk = 0;
	}

	if ($_FILES["filPosterImageFile"]["size"] > 3000000) {		//size in bytes to divide by 1000
	    echo "Sorry, your file is too large.";
	    $uploadOk = 0;
	}

	//VALID_IMAGE_TYPES
	// Allow certain file formats. Uses && for shot-circuit evaluation, but really just OR
	//change to inArray 	$VALID_IMAGE_TYPES
	if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
	    echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
	    $uploadOk = 0;
	}

	if ($uploadOk == 0) {
	    echo "Sorry, your file was not uploaded.";
	// if everything is ok, try to upload file
	} else {
	    if (move_uploaded_file($_FILES["filPosterImageFile"]["tmp_name"], $target_file)) {
	        echo "The file ". basename( $_FILES["filPosterImageFile"]["name"]). " has been uploaded.";
	    } else {
	        echo "Sorry, there was an error uploading your file.";
	    }
	}
// }

// }
//change echos 2 $errorMsgMovie
?>