<?php
session_start();
include	"../php/top.php";

$tabIndex=1;		//print on every form input element & increment

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



$titleError=false;		//error variables for form input validation
$runtimeError=false;
$ratingError=false;
$displayError=false;
$directorError=false;
$synopsisError=false;

$errorMsg=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");	//only valid options for MPAA ratings listbox
$displayOptions=array('Hidden', 'Current', 'Coming Soon');		//only valid options 4 display listbox

if(isset($_POST['btnUpdateMovie'])){
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

	//skip rating & visibility validation since listboxes almost impossible to "hack". Date practially impossible since type='date'

	if(!verifyAlphaNum($director)){
		$errorMsg[]="Director text cannot have special characters";
		$directorError=true;
	}

	if(!verifyAlphaNum($synopsis)){
		$errorMsg[]="Synopsis Cannot have special characters";
		$synopsisError=true;
	}

	if(!$errorMsg){
		$query="INSERT INTO tblMovies (fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector) VALUES (?,?,?,?,?,?)";
		$data=array($title,$runtime,$rating,$releaseDate,$display,$director);
		$thisDatabaseWriter->insert($query,$data,0);
		$lastMovieId=$thisDatabaseWriter->lastInsert();		//get id of movie just added so for synopsis

		$query="INSERT INTO tblSynopses (fnkMovieId, fldSynopsis) VALUES (?,?)";
		$data=array($lastMovieId,$synopsis);
		$thisDatabaseWriter->insert($query,$data,0);

		//only insert into table if they selected a radio button image filename
		if($poster !='none'){
			$query="INSERT INTO tblPictures (fnkMovieId, fldImgFilename) VALUES (?,?)";
			$data=array($lastMovieId,$poster);
			$thisDatabaseWriter->insert($query,$data,0);
		}

		//now reset session variables values so that it DOESN't remeber the last info entered
		// $title='reset title';
		// $runtime='10';
		// $rating='R';	//most common rating
		// $releaseDate='2017-11-30';
		// $display='Coming Soon';	//default is hidden (since don't want 2 display movie without showtimes)
		// $director='reset Nolan';
		// $synopsis='reset synopsis is optional';
		// $poster='none';		//reset value to none (default)

		header('Location: edit.php');		//redirect to Edit page
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
		<h1>Edit Movie Info (admin)</h1>
		<form action="<?php echo PHP_SELF;?>" method='post' id='frmAddMovie' name='frmAddMovie' >
			<?php
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
			echo "\t\t\t\t\t<td><label for='datReleateDate'>Release Date</label></td>\n";
			echo "\t\t\t\t\t<td><input type='date' name='datReleaseDate' id='datReleateDate' tabindex='".$tabIndex++."' value='".$releaseDate."'";
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
			echo "\t\t\t\t\t<td colspan='2'>Choose Poster Image (contact webmaster if no images are left)</td>";
			echo "\t\t\t\t</tr>\n";

			//query database to get list of all pictures already associated with a movie
			$query ="SELECT fldImgFilename FROM tblPictures";
			$dbPictures=$thisDatabaseReader->select($query,"",0);

			$pictures=array();		//this array converts the mysql associative array to a normal array
			foreach($dbPictures as $onePic){
				$pictures[]=$onePic['fldImgFilename'];
			}

			//always print the 1st row so that they can choose NO IMAGE
			echo "\t\t\t\t<tr>\n";
			echo "<td><label for='radImg-none'>No Image</label></td>\n";
			echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' ";
			if($poster=='none'){echo ' checked ';}
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";
			foreach($imageList as $image){		//iterate through all possible files in folder (called @ start of this file)
				if(!in_array($image, $pictures)){	//only print picture if it's NOT already in the database
					echo "\t\t\t\t<tr>\n";
					echo "<td><label for='radImg-".$image."'>".$image."</label> <a href='".$imageFolderPath.$image."' target='_blank'>View Image (new tab)</a></td>\n";
					echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$image."' value='".$image."' ";
					if($poster==$image){echo ' checked ';}
					echo "></td>\n";
					echo "\t\t\t\t</tr>\n";
				}
			}

			echo "\t\t\t</table>\n";

			echo "\t\t\t<br><input type='submit' name='btnUpdateMovie' value='Update Movie' tabindex='".$tabIndex++."'><br>\n";

			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>