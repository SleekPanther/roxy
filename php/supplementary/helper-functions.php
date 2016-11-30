<?php
//some functions to convert dates & interpret data. Included here to reduce duplicate code

//convert sql date to nice human readable dale with php date()
function dateSqlToNice($inputdate){
	$dateInSeconds = strtotime($inputdate);	//convert date to number of seconds since 1970
	return date('l F d, Y', $dateInSeconds);
}

//add leading zeros for number less than 10
function leadingZeros($inputNumber,$desiredNumberOfDigits){
    if($inputNumber<pow(10,($desiredNumberOfDigits-1)) ){   //when 10^(n-1)
        return sprintf("%0".$desiredNumberOfDigits."d",$inputNumber);
    }
    return $inputNumber;
}

//find nearest date given a dat & date (e.g. find nearest friday looks ahead & to previous to find closest)
function nearestDate($day, $date){
    //$last=date("Y-m-d", strtotime("last $day", strtotime($date)) );
    //$nextOrThis=date("Y-m-d", strtotime("this $day", strtotime($date)) );
    
    $last=strtotime("last $day", strtotime($date));         //previous week
    $nextOrThis=strtotime("this $day", strtotime($date));   //look forward (gives current if it matches the current day)
    
    //Subtract to find magnitude of the distance between the input date & the next & previous day. Returns the correct value (the closer date)
    if( (strtotime($date) - $last) < ($nextOrThis - strtotime($date)) ){
        return date("Y-m-d", $last);
    }
    return date("Y-m-d", $nextOrThis);
    
    //return strtotime($date) - $last;
    
    //return $nextOrThis - strtotime($date);
    
    
    //return date('d', strtotime($nextOrThis) - strtotime($last) );
    
    //return $nextOrThis. " :: " .$last;
}

//convert total minutes to hours & minutes
function runtimeToHoursMinutes($minutes,$returnArray=false,$minuteLabel='m',$hourLabel='h'){
    $hrMinArray=array();    //size=2. 0th index=hours, 1st index=minutes
    $hrMinArray[0]=floor($minutes/60);
    $hrMinArray[1]=$minutes%60;
    
    if($returnArray){
        return $hrMinArray;
    }
    //default is to return the following: components separated by labels after hour & minute
    return $hrMinArray[0].' '.$hourLabel.' '.$hrMinArray[1].' '.$minuteLabel;
}

//Pass in a folder & get a list of files in that folder (including extension)
function getFilesInDirectory($url, $extensions = array("jpg","jpeg","png","gif","tif","tiff")) {
    $correctedFileList = array();	//array holds list of image filenames (not their whole path)

    $allFilesList = scandir($url);		//all files in folder including . & ..

    if (count($allFilesList) > 0) {
        //Start at index 2, to ignore the ".." and "." folders
        for ($i = 2; $i < count($allFilesList); $i++) {  			//maybe even start i @ 0 since it seems to work
            // Only add files to the image array that have the expected extension
            $ext = strtolower( pathinfo($allFilesList[$i], PATHINFO_EXTENSION) );   //make sure lowercase extension
            if (in_array($ext, $extensions)) {
                $correctedFileList[]=$allFilesList[$i];
                // array_push($correctedFileList, $allFilesList[$i]);
            }
        }
    }
    return $correctedFileList;
}

//display array contents inside pre tags
function printArray(&$array){
    echo "<pre>\n";
    print_r($array);
    echo "</pre>\n";
}
?>