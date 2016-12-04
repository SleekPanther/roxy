<?php
session_start();
include	"../php/top.php";

if (!(isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");


$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector FROM tblMovies WHERE pmkMovieId=?";
$data=array($currentMovieId);
$movieInfo=$thisDatabaseReader->select($query,$data,1);
if(empty($movieInfo)){
	header('Location: index.php');	//redirect them if no movie exists in the database
}

if(isset($_POST['btnDeleteMovie'])){
	$query="DELETE FROM tblShowtimes WHERE fnkMovieId=?";
	$data=array($currentMovieId);
	$thisDatabaseWriter->select($query,$data,1);		//uses same data array

	$query="DELETE FROM tblPictures WHERE fnkMovieId=?";
	$thisDatabaseWriter->select($query,$data,1);		//uses same data array

	$query="DELETE FROM tblSynopses WHERE fnkMovieId=?";
	$thisDatabaseWriter->select($query,$data,1);		//uses same data array

	$query="DELETE FROM tblReviews WHERE fnkMovieId=?";
	$thisDatabaseWriter->select($query,$data,1);		//uses same data array

	$query="DELETE FROM tblMovies WHERE pmkMovieId=?";
	$thisDatabaseWriter->select($query,$data,1);		//uses same data array

	$_SESSION['whatJustHappened']='Movie Succesfully Deleted';
	header('Location: index.php');
}elseif(isset($_POST['btnCancel'])){
	$_SESSION['whatJustHappened']='Canceled Movie Deletion';
	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment

?>		
	<article class='movieContainer'>
		<article class='articleBg'>
			<form name='frmDeleteMovie' id='frmDeleteMovie' action='<?php echo PHP_SELF."?movieId=".$currentMovieId."'";?>' method='post'>
				<h2>Delte Movie Confirmation</h2>
				<h3>Are you sure you want to delete this movie & all of it's showtimes?</h3>
				<p>Upcoming movies with visibility "Coming Soon" aren't displayed to the public</p>
				<p>Showtimes can be deleted individually & aren't <strong>showtimes in the past aren't displayed</strong> to the public</p>
				<p>You can also mark a movie as hidden</p>
				<?php
				foreach($movieInfo as $oneMovie){
					echo "<br><p>Title: ".$oneMovie['fldTitle']."</p>\n";
					echo "\t\t\t\t<p>Runtime: ".$oneMovie['fldRuntime']." Minutes</p>\n";
					echo "\t\t\t\t<p>Rating: ".$oneMovie['fldRating']."</p>\n";
					echo "\t\t\t\t<p>Release Date: ".$oneMovie['fldReleaseDate']."</p>\n";
					echo "\t\t\t\t<p>Visibility: ".$oneMovie['fldDisplay']."</p>\n";
					echo "\t\t\t\t<p>Director: ".$oneMovie['fldDirector']."</p>\n";
				}
				echo "\t\t\t\t<br><input type='submit' name='btnDeleteMovie' id='btnDeleteMovie' value='Delete Movie'>";
				echo "<input type='submit' name='btnCancel' id='btnCancel' value='Cancel'>\n";
				?>
			</form>
		</article>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>