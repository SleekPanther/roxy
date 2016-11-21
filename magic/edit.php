<?php
session_start();
include	"../php/top.php";

$tabIndex=1;		//print on every form input element & increment

$movieUpdated=false;	//changed to true if they click submit so that a success message is added

if (!isset($_GET['pmkMovieId'])){
	header('Location: index.php');	//redirect to homepage if they accidentally clicked this page & GET isn't set
}
$currentMovieId=htmlentities($_GET['pmkMovieId'], ENT_QUOTES, "UTF-8");		//sanitize value from GET array

$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
fldSynopsis,
fldImgFilename FROM tblMovies
 LEFT JOIN tblSynopses ON pmkMovieId=tblSynopses.fnkMovieId
 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
 WHERE pmkMovieId LIKE ?";
$data=array($currentMovieId);
$movieInfo=$thisDatabaseReader->select($query,$data,1);	//query database to see if movie exists
if(empty($movieInfo)){			//redirect them again if the movie doesn't exist
	header('Location: index.php');
}

//query reviews table
//query the showtimes table

// echo "<pre>";
// print_r($movieInfo);
// echo "</pre>";

$imageFolderPath='../images/posters/';		//directory to search when adding image to movie
$imageList=getFilesInDirectory($imageFolderPath);


//variables to hold form input (get from database)
$title=$movieInfo[0]['fldTitle'];
$runtime=$movieInfo[0]['fldRuntime'];
$rating=$movieInfo[0]['fldRating'];
$releaseDate=$movieInfo[0]['fldReleaseDate'];
$display=$movieInfo[0]['fldDisplay'];
$director=$movieInfo[0]['fldDirector'];
$synopsis=$movieInfo[0]['fldSynopsis'];
if($movieInfo[0]['fldImgFilename'] == NULL){	//if no image is selected, then store 'none' so that the correct radio button will be CHECKED
	$poster='none';
}else{
	$poster=$movieInfo[0]['fldImgFilename'];
}

include '../php/magic/showtime-variables.php';	//initialize variables in separate file


$titleError=false;		//error variables for form input validation
$runtimeError=false;
$ratingError=false;
$releaseDateError=false;
$displayError=false;
$directorError=false;
$synopsisError=false;
//showtime error variables innitialized in showtime-variables.php

$errorMsg=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");	//only valid options for MPAA ratings listbox
$displayOptions=array('Hidden', 'Current', 'Coming Soon');		//only valid options 4 display listbox

if(isset($_POST['btnUpdateMovie']) || isset($_POST['btnAddShowtime'])){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
	$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
	$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
	$releaseDate=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
	$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
	$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");
	$synopsis=htmlentities($_POST['txtSynopsis'], ENT_QUOTES, "UTF-8");
	$poster=htmlentities($_POST['radImageChoose'], ENT_QUOTES, "UTF-8");

	if($title==""){
		$errorMsg[]="Title cannot be empty";
		$titleError=true;
	}elseif (!verifyAlphaNum($title)) {
		$errorMsg[]="Title cannot have Special Characters";
		$titleError=true;
	}

	if($runtime==""){
		$errorMsg[]="Runtime cannot be empty";
		$runtimeError=true;
	}elseif(!verifyNumeric($runtime)){
		$errorMsg[]="Runtime must be a number";
		$runtimeError=true;
	}

	//skip rating & visibility validation since listboxes almost impossible to "hack".

	if($releaseDate==''){
		$errorMsg[]='Release Date cannot be empty';
		$releaseDateError=true;
	}elseif(!validateSqlDate($releaseDate)){
		$errorMsg[]='Release Date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
		$releaseDateError=true;
	}

	if(!verifyAlphaNum($director)){
		$errorMsg[]="Director text cannot have special characters";
		$directorError=true;
	}

	if($synopsis !=''){		//only validate if NOT empty
		if(!verifyAlphaNum($synopsis)){
			$errorMsg[]="Synopsis Cannot have special characters";
			$synopsisError=true;
		}
	}


	include '../php/magic/showtime-validation.php';
	

	if(!$errorMsg){
		$query="UPDATE tblMovies SET fldTitle=?, fldRuntime=?, fldRating=?, fldReleaseDate=?, fldDisplay=?, fldDirector=? WHERE pmkMovieId LIKE ?";
		$data=array($title,$runtime,$rating,$releaseDate,$display,$director,$currentMovieId);
		$thisDatabaseWriter->insert($query,$data,1);

		if($synopsis !=''){		//if it's not empty, add to the database
			$query="INSERT INTO tblSynopses SET fnkMovieId=?, fldSynopsis=? ON DUPLICATE KEY UPDATE fldSynopsis=?";
			$data=array($currentMovieId,$synopsis,$synopsis);
			$thisDatabaseWriter->insert($query,$data,0);
		}
		else{	//else delete empty entries. This technically attempts to remove even if not in databse, but doesn't matter & is mainly for when they already havea description, but then clear the textarea (avoid leaving empty synopses left in table)
			$query="DELETE FROM tblSynopses WHERE fnkMovieId LIKE ?";
			$data=array($currentMovieId);
			$thisDatabaseWriter->insert($query,$data,1);
		}
		

		$query="INSERT INTO tblPictures (fnkMovieId, fldImgFilename) VALUES (?,?) ON DUPLICATE KEY UPDATE fldImgFilename=?";
		$data=array($currentMovieId,$poster,$poster);
		$thisDatabaseWriter->insert($query,$data,0);

		//execute sql statements to add to database
		include "../php/magic/showtime-sql.php";

		$movieUpdated=true;
	}
}

if ($errorMsg) {
	echo "<div id='errors'>\n";
	echo "<h1>Your form has the following mistakes</h1>\n";
	echo "<ol>\n";
	foreach ($errorMsg as $err) {
		echo "<li>" .$err . "</li>\n";
	}
	echo "</ol>\n";
	echo "</div>\n";
}
?>
	<article>
		<h1>Edit Movie Info (admin) <br>dropdown to switch btwen movies</h1>
		<p><a href="index.php">Return to all movies list (edit another movie)</a></p>
		<form action="<?php echo PHP_SELF.'?pmkMovieId='.$currentMovieId;?>" method='post' id='frmAddMovie' name='frmAddMovie' >
			<?php
			if($movieUpdated){
				echo "<p class='movieUpdated'>Movie Successfully updated!</p>";
			}

			echo "<table>\n";
			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtMovieTitle'>Title</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtMovieTitle' id='txtMovieTitle' tabindex='".$tabIndex++."' value='".$title."'";
			if($titleError){echo " class='mistake' ";}
			echo" autofocus></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtRuntime'>Runtime (minutes)</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtRuntime' id='txtRuntime' tabindex='".$tabIndex++."' value='".$runtime."'";
			if($runtimeError){echo " class='mistake' ";}
			echo"></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='lstRating'>Rating</label></td>\n";
			echo "\t\t\t\t\t<td><select id='lstRating' name='lstRating' tabindex='".$tabIndex++."' >\n";
				foreach($ratings as $oneRating){
					echo "\t\t\t\t\t\t<option value='".$oneRating."'";
					if($oneRating==$rating){ echo ' selected ';}
					echo ">".$oneRating."</option>\n";
				}
			echo "\t\t\t\t\t</select></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='datReleaseDate'>Release Date (YYYY-MM-DD)</label></td>\n";
			echo "\t\t\t\t\t<td><input type='date' name='datReleaseDate' id='datReleaseDate' tabindex='".$tabIndex++."' value='".$releaseDate."'";
			if($releaseDateError){echo " class='mistake' ";}
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='lstDisplay'>Visibility (display to public or not)</label></td>\n";
			echo "\t\t\t\t\t<td><select id='lstDisplay' name='lstDisplay' tabindex='".$tabIndex++."' >\n";
				foreach($displayOptions as $option){
					echo "\t\t\t\t\t\t<option value='".$option."'";
					if($option==$display){echo ' selected ';}
					echo ">".$option."</option>\n";
				}
			echo "\t\t\t\t\t</select></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtDirector'>Director (optional)</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$director."'";
			if($directorError){echo " class='mistake' ";}
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtSynopsis'>Synopsis (optional) <br>(1000 characters max)</label></td>\n";
			echo "\t\t\t\t\t<td><textarea name='txtSynopsis' id='txtSynopsis' tabindex='".$tabIndex++."'";
			if($synopsisError){echo " class='mistake' ";}
			echo ">".$synopsis."</textarea></td>\n";	//make it sticky to remember what they entered
			echo "\t\t\t\t</tr>\n";


			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td colspan='2'>Change Image (contact webmaster if no images are left)</td>";
			echo "\t\t\t\t</tr>\n";

			//query database to get list of all pictures already associated with a movie
			$query ="SELECT fldImgFilename FROM tblPictures";
			$dbPictures=$thisDatabaseReader->select($query,"",0);

			$pictures=array();		//this array converts the mysql associative array to a normal array
			foreach($dbPictures as $onePic){
				$pictures[]=$onePic['fldImgFilename'];
			}

			//Print the currently selected image (this basically puts 'checked' )
			if($poster !='none'){
				//print the option to select NO IMAGE
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-none'>No Image</label></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' ></td>\n";
				echo "\t\t\t\t</tr>\n";

				// Print the actual image from the database
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-".$poster."'><strong>".$poster." (CURRENT)</strong></label> <a href='".$imageFolderPath.$poster."' target='_blank'>View Image (new tab)</a></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$poster."' value='".$poster."' checked >";
				echo "\t\t\t\t</tr>\n";
			}
			else{		//else print no image. Similar to above, but this time it's current
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-none'><strong>No Image (CURRENT)</strong></label></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' checked ></td>\n";
				echo "\t\t\t\t</tr>\n";
			}

			foreach($imageList as $image){		//iterate through all possible files in folder (called @ start of this file)
				if(!in_array($image, $pictures)){	//only print picture if it's NOT already in the database
					echo "\t\t\t\t<tr>\n";
					echo "\t\t\t\t\t<td><label for='radImg-".$image."'>".$image."</label> <a href='".$imageFolderPath.$image."' target='_blank'>View Image (new tab)</a></td>\n";
					echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$image."' value='".$image."' ";
					echo "></td>\n";
					echo "\t\t\t\t</tr>\n";
				}
			}

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t<td><br><input type='submit' name='btnUpdateMovie' value='Update Movie Info' tabindex='".$tabIndex++."'></td>\n";
			echo "\t\t\t\t</tr>\n";

			include "../php/magic/showtime-form.php";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t<td><br><input type='submit' name='btnAddShowtime' value='Add New Showtime' tabindex='".$tabIndex++."'></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t</table>\n";

			//print existing showtimes

			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>