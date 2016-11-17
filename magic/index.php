<?php
session_start();
include	"../php/top.php";

$tabIndex=1;

// $_SESSION['title']='';			//variables to hold form input
// $_SESSION['runtime']='';
// $_SESSION['rating']='PG-13';	//most common rating
// $_SESSION['releaseDate']='';
// $_SESSION['display']='Hidden';	//default is hidden (since don't want 2 display movie without showtimes)
// $_SESSION['director']='';
// $_SESSION['synopsis']='';

//SESSION variables to hold form input. Using SESSION so that 1)if a form has errors, keep the value & reprint it & 2)if it was successfully added, clear the values instead of remembering the last thing added
$_SESSION['title']='Inception';			
$_SESSION['runtime']='123';
$_SESSION['rating']='PG-13';	//most common rating
$_SESSION['releaseDate']='2016-11-30';
$_SESSION['display']='Hidden';	//default is hidden (since don't want 2 display movie without showtimes)
$_SESSION['director']='Christopher Nolan';
$_SESSION['synopsis']='synopsis is optional';


$titleError=false;		//error variables for form input validation
$runtimeError=false;
$ratingError=false;
$displayError=false;
$directorError=false;
$synopsisError=false;

$errorMsg=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");	//only valid options for MPAA ratings listbox
$displayOptions=array('Hidden', 'Current', 'Coming Soon');		//only valid options 4 display listbox

if(isset($_POST['btnAddMovie'])){
	echo "<pre>";
	print_r($_POST);
	echo "</pre>";
	$_SESSION['title']=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
	$_SESSION['runtime']=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
	$_SESSION['rating']=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
	$_SESSION['releaseDate']=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
	$_SESSION['display']=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
	$_SESSION['director']=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");
	$_SESSION['synopsis']=htmlentities($_POST['txtSynopsis'], ENT_QUOTES, "UTF-8");

	if($_SESSION['title']==""){
		$errorMsg[]="Title cannot be empty";
		$titleError=true;
	}elseif (!verifyAlphaNum($_SESSION['title'])) {
		$errorMsg[]="Title cannot have Special Characters";
		$titleError=true;
	}

	if($_SESSION['runtime']==""){
		$errorMsg[]="Runtime cannot be empty";
		$runtimeError=true;
	}elseif(!verifyNumeric($_SESSION['runtime'])){
		$errorMsg[]="Runtime must be a number";
		$runtimeError=true;
	}

	//skip rating & visibility validation since listboxes almost impossible to "hack". Date practially impossible since type='date'

	if(!verifyAlphaNum($_SESSION['director'])){
		$errorMsg[]="Director text cannot have special characters";
		$directorError=true;
	}

	if(!verifyAlphaNum($_SESSION['synopsis'])){
		$errorMsg[]="Synopsis Cannot have special characters";
		$synopsisError=true;
	}

	if(!$errorMsg){
		$query="INSERT INTO tblMovies (fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector) VALUES (?,?,?,?,?,?)";
		$data=array($_SESSION['title'],$_SESSION['runtime'],$_SESSION['rating'],$_SESSION['releaseDate'],$_SESSION['display'],$_SESSION['director']);
		$thisDatabaseWriter->insert($query,$data,0);
		$lastMovieId=$thisDatabaseWriter->lastInsert();		//get id of movie just added so for synopsis

		$query="INSERT INTO tblSynopses (fnkMovieId, fldSynopsis) VALUES (?,?)";
		$data=array($lastMovieId,$_SESSION['synopsis']);
		$thisDatabaseWriter->insert($query,$data,0);

		//now reset session variables values so that it DOESN't remeber the last info entered
		$_SESSION['title']='reset title';
		$_SESSION['runtime']='10';
		$_SESSION['rating']='R';	//most common rating
		$_SESSION['releaseDate']='2017-11-30';
		$_SESSION['display']='Coming Soon';	//default is hidden (since don't want 2 display movie without showtimes)
		$_SESSION['director']='reset Nolan';
		$_SESSION['synopsis']='reset synopsis is optional';
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
		<h1>admin</h1>
		<h2>Add Movie</h2>
		<form action="<?php echo PHP_SELF;?>" method='post' id='frmAddMovie' name='frmAddMovie' >
			<?php
			echo "<table>\n";
			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtMovieTitle'>Title</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtMovieTitle' id='txtMovieTitle' tabindex='".$tabIndex++."' value='".$_SESSION['title']."'";
			if($titleError){echo " class='mistake' ";}
			echo" autofocus></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtRuntime'>Runtime (minutes)</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtRuntime' id='txtRuntime' tabindex='".$tabIndex++."' value='".$_SESSION['runtime']."'";
			if($runtimeError){echo " class='mistake' ";}
			echo"></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='lstRating'>Rating</label></td>\n";
			echo "\t\t\t\t\t<td><select id='lstRating' name='lstRating' tabindex='".$tabIndex++."' >\n";
				foreach($ratings as $oneRating){
					echo "\t\t\t\t\t\t<option value='".$oneRating."'";
					if($oneRating==$_SESSION['rating']){ echo ' selected ';}
					echo ">".$oneRating."</option>\n";
				}
			echo "\t\t\t\t\t</select></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='datReleateDate'>Release Date</label></td>\n";
			echo "\t\t\t\t\t<td><input type='date' name='datReleaseDate' id='datReleateDate' tabindex='".$tabIndex++."' value='".$_SESSION['releaseDate']."'";
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='lstDisplay'>Visibility (display to public or not)</label></td>\n";
			echo "\t\t\t\t\t<td><select id='lstDisplay' name='lstDisplay' tabindex='".$tabIndex++."' >\n";
				foreach($displayOptions as $option){
					echo "\t\t\t\t\t\t<option value='".$option."'";
					if($option==$_SESSION['display']){echo ' selected ';}
					echo ">".$option."</option>\n";
				}
			echo "\t\t\t\t\t</select></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtDirector'>Director (optional)</label></td>\n";
			echo "\t\t\t\t\t<td><input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$_SESSION['director']."'";
			if($directorError){echo " class='mistake' ";}
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><label for='txtSynopsis'>Synopsis (optional) <br>(1000 characters max)</label></td>\n";
			echo "\t\t\t\t\t<td><textarea name='txtSynopsis' id='txtSynopsis' tabindex='".$tabIndex++."'";
			if($synopsisError){echo " class='mistake' ";}
			echo ">".$_SESSION['synopsis']."</textarea></td>\n";	//make it sticky to remember what they entered
			echo "\t\t\t\t</tr>\n";

			// echo "\t\t\t\t<tr>\n";
			// echo "\t\t\t\t\t<td><input type='submit' name='btnAddMovie' value='Add Movie' tabindex='".$tabIndex++."'></td>\n";
			// echo "\t\t\t\t</tr>\n";
			echo "\t\t\t</table>\n";

			print_r(getFilesInDirectory("../images/posters/") );

			echo "\t\t\t<input type='submit' name='btnAddMovie' value='Add Movie' tabindex='".$tabIndex++."'><br>\n";


			$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
			 fldSynopsis FROM tblMovies 
			  LEFT JOIN tblSynopses ON pmkMovieId=fnkMovieId";		//need left join since I DO want to have movie info even if there's no synopsis
			$movies=$thisDatabaseReader->select($query,'',0);
			// echo "<pre>";
			// print_r($movies);
			// echo "</pre>";

			if(!empty($movies)){	//only print if there are movies to show
				echo "\n\t\t\t<h3>All Movies in Database</h3>\n";
				echo "\t\t\t<section class='admin-movie-list'>\n";
				echo "put listbox to narrow by DISPLAY";
				foreach($movies as $movie){
					echo "\t\t\t\t<article class='movieInfo'>\n";
					echo "\t\t\t\t\t<p>Title: ".$movie['fldTitle']."</p>\n";
					echo "\t\t\t\t\t<p>Runtime: ".$movie['fldRuntime']." minutes</p>\n";
					echo "\t\t\t\t\t<p>Rating: ".$movie['fldRating']."</p>\n";
					echo "\t\t\t\t\t<p>Release Date: ".$movie['fldReleaseDate']."</p>\n";
					echo "\t\t\t\t\t<p>Visibility (show or hide): ".$movie['fldDisplay']."</p>\n";
					echo "\t\t\t\t\t<p>Director: ".$movie['fldDirector']."</p>\n";
					echo "\t\t\t\t\t<p>Synopsis:<br> ".$movie['fldSynopsis']."</p>\n";

					echo "\t\t\t\t\t<a class='specialButtonLink' href='edit.php?pmkMovieId=".$movie['pmkMovieId']."'>Edit Info</a> (add showtimes & reviews)\n";
					echo "\t\t\t\t</article >\n";
				}
				echo "\t\t\t</section>\n";
			}
			
			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>