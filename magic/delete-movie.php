<?php
include	"../php/top.php";

if (!(isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");


$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector, fldImgFilename FROM tblMovies 
JOIN tblPictures ON pmkMovieId=fnkMovieId WHERE pmkMovieId=?";
$data=array($currentMovieId);
$movieInfo=$thisDatabaseReader->select($query,$data,1);
if(!$movieInfo){
	header('Location: index.php');
}

if(isset($_POST['btnDeleteMovie'])){
	deleteImage(getFullPosterLinkPath($movieInfo[0]['fldImgFilename']));

	$query="DELETE FROM tblPictures WHERE fnkMovieId=?";
	$data=array($currentMovieId);						//same data for all queries
	$databaseSuccess=array();
	$databaseSuccess[]=$thisDatabaseWriter->delete($query,$data,1);

	$query="DELETE FROM tblShowtimes WHERE fnkMovieId=?";
	$databaseSuccess[]=$thisDatabaseWriter->delete($query,$data,1);

	$query="DELETE FROM tblSynopses WHERE fnkMovieId=?";
	$databaseSuccess[]=$thisDatabaseWriter->delete($query,$data,1);

	$query="DELETE FROM tblReviews WHERE fnkMovieId=?";
	$databaseSuccess[]=$thisDatabaseWriter->delete($query,$data,1);

	$query="DELETE FROM tblMovies WHERE pmkMovieId=?";
	$databaseSuccess[]=$thisDatabaseWriter->delete($query,$data,1);

	$_SESSION['whatJustHappened']='';	//have to initialize to empty since the loop uses concatenation & yields 2 copies of the list of errors
	$orderOfQuerriesInWords=array('Showtime','Picture','Synopsis','Review','Movie');
	$doErrorsExist=false;
	for($i=0; $i<count($databaseSuccess); $i++){
		if(!$databaseSuccess[$i]){
			$doErrorsExist=true;
			$_SESSION['whatJustHappened'].='Failed to delete '.$orderOfQuerriesInWords[$i]."<br>\n";
		}
	}
	if(!$doErrorsExist){
		$_SESSION['whatJustHappened']='Movie Succesfully Deleted';
	}
	
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
				<p>You can always change <strong>visibility to "Hidden"</strong> to hide from the public</p>
				<p>Movies with release dates are automatically deemed <strong>"Coming Soon"</strong></p>
				<p>Showtimes can be deleted individually & <strong>EXPIRED showtimes aren't displayed</strong></p>
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