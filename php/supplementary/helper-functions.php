<?php
//some functions to convert dates & interpret data. Included here to reduce duplicate code

//convert sql date to nice human readable dale with php date()
function dateSqlToNice($inputdate){
	$dateInSeconds = strtotime($inputdate);	//convert date to number of seconds since 1970
	return date('l F d, Y', $dateInSeconds);
}

//convert total minutes to hours & minutes
function runtimeToHours($minutes){
	$hrMinArray=array();	//size=2. 0th index=hours, 1st index=minutes
	return $hrMinArray;
}

//Pass in a folder & get a list of files in that folder (including extension)
function getFilesInDirectory($url, $extensions = array("jpg", "png", "jpeg")) {
    $correctedFileList = array();	//array holds list of image filenames (not their whole path)

    $allFilesList = scandir($url);		//all files in folder including . & ..

    if (count($allFilesList) > 0) {
        //Start at index 2, to ignore the ".." and "." folders
        for ($i = 2; $i < count($allFilesList); $i++) {  			//maybe even start i @ 0 since it seems to work
        	$correctedFileList[]=$allFilesList[$i];
            //Only add files to the image array that have the expected extension
            // $ext = pathinfo($allFilesList[$i], PATHINFO_EXTENSION);
            // if (in_array($ext, $extensions)) {
            //     array_push($correctedFileList, $allFilesList[$i]);
            // }
        }
    }
    return $correctedFileList;
}
?>