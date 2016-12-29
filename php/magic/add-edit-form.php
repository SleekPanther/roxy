<?php
echo "<table>\n";
echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='txtMovieTitle'>Title</label></td>\n";
echo "\t\t\t\t\t<td><input type='text' name='txtMovieTitle' id='txtMovieTitle' tabindex='".$tabIndex++."' value='".$title."'";
if($titleError){
	echo " class='mistake' ";
}
if($parentFolder=='magic'){
	echo " autofocus ";
}
echo "></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='txtRuntime'>Runtime (minutes)</label></td>\n";
echo "\t\t\t\t\t<td><input type='text' name='txtRuntime' id='txtRuntime' tabindex='".$tabIndex++."' value='".$runtime."'";
if($runtimeError){
	echo " class='mistake' ";
}
echo"></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='lstRating'>Rating</label></td>\n";
echo "\t\t\t\t\t<td><select id='lstRating' name='lstRating' tabindex='".$tabIndex++."' >\n";
	foreach($ratings as $oneRating){
		echo "\t\t\t\t\t\t<option value='".$oneRating."'";
		if($oneRating==$rating){
			echo ' selected ';
		}
		echo ">".$oneRating."</option>\n";
	}
echo "\t\t\t\t\t</select></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='datReleateDate'>Release Date (YYYY-MM-DD)</label></td>\n";
echo "\t\t\t\t\t<td><input type='date' name='datReleaseDate' id='datReleateDate' tabindex='".$tabIndex++."' value='".$releaseDate."'";
if($releaseDateError){
	echo " class='mistake' ";
}
echo "></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='lstDisplay'>Visibility (display to public or not)</label></td>\n";
echo "\t\t\t\t\t<td><select id='lstDisplay' name='lstDisplay' tabindex='".$tabIndex++."' >\n";
	foreach($displayOptions as $option){
		echo "\t\t\t\t\t\t<option value='".$option."'";
		if($option==$display){
			echo ' selected ';
		}
		echo ">".$option."</option>\n";
	}
echo "\t\t\t\t\t</select></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='txtDirector'>Director</label></td>\n";
echo "\t\t\t\t\t<td><input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$director."'";
if($directorError){
	echo " class='mistake' ";
}
echo "></td>\n";
echo "\t\t\t\t</tr>\n";

echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td><label for='txtSynopsis'>Synopsis (optional) <br>(1000 characters max)</label></td>\n";
echo "\t\t\t\t\t<td><textarea name='txtSynopsis' id='txtSynopsis' tabindex='".$tabIndex++."'";
if($synopsisError){
	echo " class='mistake' ";
}
echo ">".$synopsis."</textarea></td>\n";	//make it sticky to remember what they entered
echo "\t\t\t\t</tr>\n";


echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'>Choose Poster Image (best aspect ratio is 470:700, max 3MB) <input type='file' name='filPosterImageFile' id='filPosterImageFile'></td>";
echo "\t\t\t\t</tr>\n";


echo "\t\t\t\t<tr>\n";
echo "\t\t\t\t\t<td colspan='2'>Choose Poster Image (contact webmaster if no images are left)</td>";
echo "\t\t\t\t</tr>\n";

//query database to get list of all pictures already associated with a movie
$query ="SELECT fldImgFilename FROM tblPictures";
$dbPictures=$thisDatabaseReader->select($query,"",0);

$pictures=array();		//this array converts the mysql associative array to a normal array
foreach($dbPictures as $onePic){
	$pictures[]=$onePic['fldImgFilename'];
}
?>