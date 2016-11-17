<?php
session_start();
include	"../php/top.php";

$tabIndex=1;

// $title='';			//variables to hold form input
// $runtime='';
// $rating='PG-13';	//most common rating
// $releaseDate='';
// $display='Hidden';	//default is hidden (since don't want 2 display movie without showtimes)
// $director='';
// $synopsis='';

//testing purposed default vals
$title='Inception';			//variables to hold form input
$runtime='123';
$rating='PG-13';	//most common rating
$releaseDate='2016-11-30';
$display='Hidden';	//default is hidden (since don't want 2 display movie without showtimes)
$director='Christopher Nolan';
$synopsis='synopsis is optional';


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
	$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
	$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
	$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
	$releaseDate=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
	$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
	$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");
	$synopsis=htmlentities($_POST['txtSynopsis'], ENT_QUOTES, "UTF-8");

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
			echo "<label for='txtMovieTitle'>Title</label>\n";
			echo "\t\t\t<input type='text' name='txtMovieTitle' id='txtMovieTitle' tabindex='".$tabIndex++."' value='".$title."'";
			if($titleError){echo " class='mistake' ";}
			echo" autofocus><br>\n";

			echo "\t\t\t<label for='txtRuntime'>Runtime (minutes)</label>\n";
			echo "\t\t\t<input type='text' name='txtRuntime' id='txtRuntime' tabindex='".$tabIndex++."' value='".$runtime."'";
			if($runtimeError){echo " class='mistake' ";}
			echo"><br>\n";

			
			echo "\t\t\t<label for='lstRating'>Rating</label>\n";
			echo "\t\t\t<select id='lstRating' name='lstRating' tabindex='".$tabIndex++."' >\n";
				foreach($ratings as $oneRating){
					echo "\t\t\t\t<option value='".$oneRating."'";
					if($oneRating==$rating){ echo ' selected ';}
					echo ">".$oneRating."</option>\n";
				}
			echo "\t\t\t</select><br>\n";

			echo "\t\t\t<label for='datReleateDate'>Release Date</label>\n";
			echo "\t\t\t<input type='date' name='datReleaseDate' id='datReleateDate' tabindex='".$tabIndex++."' value='".$releaseDate."'";
			echo "><br>\n";

			echo "\t\t\t<label for='lstDisplay'>Visibility (display to public or not)</label>\n";
			echo "\t\t\t<select id='lstDisplay' name='lstDisplay' tabindex='".$tabIndex++."' >\n";
				foreach($displayOptions as $option){
					echo "\t\t\t\t<option value='".$option."'";
					if($option==$display){echo ' selected ';}
					echo ">".$option."</option>\n";
				}
			echo "\t\t\t</select><br>\n";

			echo "\t\t\t<label for='txtDirector'>Director (optional)</label>\n";
			echo "\t\t\t<input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$director."'";
			if($directorError){echo " class='mistake' ";}
			echo "><br>\n";

			echo "\t\t\t<label for='txtSynopsis'>Synopsis (optional)</label>\n";
			echo "\t\t\t<textarea name='txtSynopsis' id='txtSynopsis' tabindex='".$tabIndex++."'";
			if($synopsisError){echo " class='mistake' ";}
			echo ">".$synopsis."</textarea><br>\n";	//make it sticky to remember what they entered

			//synopsis=1000 char max

			echo "\t\t\t<input type='submit' name='btnAddMovie' value='Add Movie' tabindex='".$tabIndex++."'>\n";


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
				foreach($movies as $movie){
					echo "\t\t\t\t<article class='movieInfo'>\n";
					echo "\t\t\t\t\t<p>Title: ".$movie['fldTitle']."</p>\n";
					echo "\t\t\t\t\t<p>Runtime: ".$movie['fldRuntime']." minutes</p>\n";
					echo "\t\t\t\t\t<p>Rating: ".$movie['fldRating']."</p>\n";
					echo "\t\t\t\t\t<p>Release Date: ".$movie['fldReleaseDate']."</p>\n";
					echo "\t\t\t\t\t<p>Visibility (show or hide): ".$movie['fldDisplay']."</p>\n";
					echo "\t\t\t\t\t<p>Director: ".$movie['fldDirector']."</p>\n";
					echo "\t\t\t\t\t<p>Synopsis:<br> ".$movie['fldSynopsis']."</p>\n";

					//link maybe?
					echo "\t\t\t\t\t<input type='submit' name='btnEditMovie' value='Edit Info (add showtimes)'>\n";
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