<?php
include	"../php/top.php";

if (!( isset($_GET['reviewId']) && isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$reviewId=htmlentities($_GET['reviewId'], ENT_QUOTES, "UTF-8");	//sanitize value from GET array
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");

$query="SELECT fldTitle, pmkReviewId, fnkMovieId, fldAuthor, fldReviewDate, fldReviewSource, fldReviewLink, fldReview 
	 FROM tblReviews
	 JOIN tblMovies ON pmkMovieId=fnkMovieId
	 WHERE pmkReviewId=? AND fnkMovieId=?";
$data=array($reviewId,$currentMovieId);
$reviewInfo=$thisDatabaseReader->select($query,$data,1,1);

if(empty($reviewInfo)){		//redirect if review/movie doesn't exist
	header('Location: index.php');	
}

include '../php/magic/review-variables.php';		//initialize variables
$errorMsg=array();

$_SESSION['reviewAuthor']=$reviewInfo[0]['fldAuthor'];
$_SESSION['reviewDate']=$reviewInfo[0]['fldReviewDate'];
$_SESSION['reviewSource']=$reviewInfo[0]['fldReviewSource'];
$_SESSION['reviewLink']=$reviewInfo[0]['fldReviewLink'];
$_SESSION['review']=$reviewInfo[0]['fldReview'];


if(isset($_POST['btnUpdateReview'])){
	include "../php/magic/review-validation.php";

	if(!$errorMsg){
		$query="UPDATE tblReviews SET fldAuthor=?, fldReviewDate=?, fldReviewSource=?, fldReviewLink=?, fldReview=? WHERE pmkReviewId=?";
		$data=array($_SESSION['reviewAuthor'],$_SESSION['reviewDate'],$_SESSION['reviewSource'],$_SESSION['reviewLink'],$_SESSION['review'],$reviewInfo[0]['pmkReviewId']);
		$databaseSuccess=$thisDatabaseWriter->insert($query,$data,1);

		//reset values
		$_SESSION['reviewAuthor']='';
		$_SESSION['reviewDate']=date('Y-m-d', strtotime('today'));
		$_SESSION['reviewSource']='';
		$_SESSION['reviewLink']='';
		$_SESSION['review']='';

		$_SESSION['whatJustHappened']='Review Updated';
		if(!$databaseSuccess){
			$_SESSION['whatJustHappened']='Error! Failed to update review';
		}
		header('Location: edit.php?movieId='.$currentMovieId);
	}
}elseif(isset($_POST['btnCancel'])){
	$_SESSION['whatJustHappened']='Canceled Updating Review';
	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment
?>		
	<article class='movieContainer'>
		<article class='articleBg'>
			<?php
			include $upFolderPlaceholder.'php/lib/display-form-errors.php';
			?>
			<form name='frmEditReview' id='frmEditReview' action='<?php echo PHP_SELF."?reviewId=".$reviewId."&movieId=".$currentMovieId;?>' method='post'>
				<?php
				echo "\t\t\t\t\t\t<h2>Edit Review</h2>\n";

				echo "\t\t\t\t<table>\n";

				include '../php/magic/review-form.php';

				echo "\t\t\t\t</table>\n";
				
				echo "\t\t\t\t<br><input type='submit' name='btnUpdateReview' id='btnUpdateReview' value='Update Review'>";
				echo "<input type='submit' name='btnCancel' id='btnCancel' value='Cancel'>\n";
				?>
			</form>
		</article>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>