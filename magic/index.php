<?php
session_start();
include	"../php/top.php";

$tabIndex=1;

$title='title';			//variables to hold form input
$runtime='run';
$rating='pg';
$display='dis';
$releaseDate='2day';
$director='direc';

$titleError=false;		//error variables for form input validation
$runtimeError=false;
$ratingError=false;
$displayError=false;
$releaseDateError=false;
$directorError=false;

$errorMsg=array();

$ratings=array("G","PG","PG-13","R","Not Rated","NC-17");

if(isset($_POST['btnAddMovie'])){
	$title=htmlentities($_POST['txtMovieTitle'], ENT_QUOTES, "UTF-8");
	$runtime=htmlentities($_POST['txtRuntime'], ENT_QUOTES, "UTF-8");
	$rating=htmlentities($_POST['lstRating'], ENT_QUOTES, "UTF-8");
	$display=htmlentities($_POST['lstDisplay'], ENT_QUOTES, "UTF-8");
	$releaseDate=htmlentities($_POST['datDate'], ENT_QUOTES, "UTF-8");
	$director=htmlentities($_POST['txtDirector'], ENT_QUOTES, "UTF-8");

	//if rating not emptu
	//if rating not in $ratings

	if(!$errorMsg){

	}
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
			echo"><br>\n";

			echo "<label for='txtRuntime'>Runtime (minutes)</label>\n";
			echo "\t\t\t<input type='text' name='txtRuntime' id='txtRuntime' tabindex='".$tabIndex++."' value='".$runtime."'";
			if($runtimeError){echo " class='mistake' ";}
			echo"><br>\n";

			
			echo "\t\t\t<label for='lstRating'>Rating</label>\n";
			echo "\t\t\t<select id='lstRating' name='lstRating' tabindex='".$tabIndex++."' >\n";
				foreach($ratings as $rating){
					echo "\t\t\t\t<option value='".$rating;
					//print selected
					echo "'>".$rating."</option>\n";
				}
			echo "\t\t\t</select><br>\n";

			echo "<label for='txtDirector'>Director (optional)</label>\n";
			echo "\t\t\t<input type='text' name='txtDirector' id='txtDirector' tabindex='".$tabIndex++."' value='".$director."'";
			if($directorError){echo " class='mistake' ";}
			//if(){echo " class='mistake' ";}
			echo "><br>\n";

			echo "\t\t\t<input type='submit' name='btnAddMovie' id='btnAddMovie' value='Add Movie'>\n";
			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>