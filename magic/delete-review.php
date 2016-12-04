<?php
session_start();
include	"../php/top.php";

if (!( isset($_GET['reviewId']) && isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$reviewId=htmlentities($_GET['reviewId'], ENT_QUOTES, "UTF-8");	//sanitize value from GET array
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");

$query="SELECT fldTitle, pmkReviewId, fnkMovieId, fldAuthor, fldReviewDate, fldReviewSource, fldReview 
	 FROM tblReviews
	 JOIN tblMovies ON pmkMovieId=fnkMovieId
	 WHERE pmkReviewId=? AND fnkMovieId=?";
$data=array($reviewId,$currentMovieId);
$reviewInfo=$thisDatabaseReader->select($query,$data,1,1);

if(empty($reviewInfo)){		//redirect if showtime doesn't exist
	header('Location: index.php');	
}

if(isset($_POST['btnDeleteReview'])){
	$query="DELETE FROM tblReviews WHERE pmkReviewId=?";
	$data=array($reviewId);
	$thisDatabaseWriter->insert($query,$data,1);

	$_SESSION['whatJustHappened']='Review Deleted';
	header('Location: edit.php?movieId='.$currentMovieId);
}elseif(isset($_POST['btnCancel'])){
	$_SESSION['whatJustHappened']='Canceled Deleting Review';
	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment

?>		
	<article class='movieContainer'>
		<article class='articleBg'>
			<form name='frmDeleteReview' id='frmDeleteReview' action='<?php echo PHP_SELF."?reviewId=".$reviewId."&movieId=".$currentMovieId;?>' method='post'>
				<h2>Delte Review Confirmation</h2>
				<h3>Are you sure you want to delete the following Review?</h3>
				<?php
				foreach($reviewInfo as $oneReview){		//loop is almost pointless since it should only be 1 reivew
					echo "<p>Review for: ".$oneReview['fldTitle']."</p>\n";
					echo "\t\t\t\t<p>Author: ".$oneReview['fldAuthor']."</p>\n";
					echo "\t\t\t\t<p>Review Date: ".dateSqlToNice($oneReview['fldReviewDate'])."</p>\n";
					if(!empty($oneReview['fldSource']) ){echo "\t\t\t<p>Source: ".$oneReview['fldSource']."</p>\n"; }
					echo "<br>\t\t\t\t<p>".nl2br($oneReview['fldReview'],false)."</p>\n";
				}
				echo "\t\t\t\t<br><input type='submit' name='btnDeleteReview' id='btnDeleteReview' value='Delete Review'>";
				echo "<input type='submit' name='btnCancel' id='btnCancel' value='Cancel'>\n";
				?>
			</form>
		</article>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>