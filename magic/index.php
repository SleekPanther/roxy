<?php
session_start();
include	"../php/top.php";

$tabIndex=1;

$title='';			//variables to hold form input
$runtime='';
$rating='PG-13';	//most common rating
$releaseDate='';
$display='Hidden';	//default is hidden (since don't want 2 display movie without showtimes)
$director='';

$titleError=false;		//error variables for form input validation
$runtimeError=false;
$ratingError=false;
//$releaseDateError=false;
$displayError=false;
$directorError=false;

$errorMsg=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");	//only valid options for MPAA ratings listbox
$displayOptions=array('Hidden', 'Current', 'Coming Soon');		//only valid options 4 display listbox

if(isset($_POST['btnAddMovie'])){
	echo "<pre>".print_r($_POST)."</pre>";	//prints extra 1 from print_r saying "true"
	$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
	$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
	$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
	$releaseDate=htmlentities($_POST['datReleaseDate'], ENT_QUOTES, "UTF-8");
	$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
	$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");

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

	if(!$errorMsg){
		//
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

			echo "<label for='txtRuntime'>Runtime (minutes)</label>\n";
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

			echo "<label for='datReleateDate'>Release Date</label>\n";
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

			echo "<label for='txtDirector'>Director (optional)</label>\n";
			echo "\t\t\t<input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$director."'";
			if($directorError){echo " class='mistake' ";}
			//if(){echo " class='mistake' ";}
			echo "><br>\n";

			echo "\t\t\t<input type='submit' name='btnAddMovie' id='btnAddMovie' value='Add Movie' tabindex='".$tabIndex++."'>\n";
			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>