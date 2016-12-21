<?php
include	"php/top.php";
?>
	<article class='movieContainer'>
		<h1>Reviews</h1>
		<?php
		$query="SELECT DISTINCT pmkMovieId, fldTitle, fldImgFilename FROM tblMovies
		 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 JOIN tblReviews ON pmkMovieId=tblReviews.fnkMovieId
		 WHERE ( (fldDisplay=? ) AND (fldReleaseDate<=CURDATE()) )
		 ORDER BY fldReleaseDate DESC";		//the 2nd join for Reviews limits it so only movies that actually have reviews are displayed
	 	//must be distinct since don't want to repeat a movie infor here, review printed later
		$data=array('Current');
		$movies=$thisDatabaseReader->select($query,$data,1,2,0,1);

		foreach($movies as $movie){
			echo "\t\t<section class='articleBg fullWidth'>\n";

			$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
			if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
				$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
			}
			echo "\t\t\t\t<figure><img alt='".$movie['fldTitle']."' src='".$imgFile."'></figure>\n";

			echo "\t\t\t<div class='fullWidthInfo'>\n";
			echo "\t\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			$query="SELECT fldAuthor, fldReviewDate, fldReviewSource, fldReviewLink, fldReview FROM tblReviews WHERE fnkMovieId=? ORDER BY fldReviewDate";
			$data=array($movie['pmkMovieId']);
			$reviews=$thisDatabaseReader->select($query,$data,1,1);
			foreach($reviews as $review){
				echo "\t\t\t\t\t<article class='reviewSection'>\n";
				echo "\t\t\t\t\t\t<p class='reviewMetaInfo'>by ".$review['fldAuthor']." &nbsp;&nbsp; ".dateSqlToNice($review['fldReviewDate']);
				if(!empty($review['fldReviewSource'])){
					if(!empty($review['fldReviewLink'])){
						echo " <em><a href='".$review['fldReviewLink']."'>".$review['fldReviewSource']."</a></em>";
					}else{
						echo " &nbsp;&nbsp; <em>".$review['fldReviewSource']."</em>";
					}
				}
				echo "</p>\n";
				echo "\t\t\t\t\t\t<p>".nl2br($review['fldReview'],false)."</p>\n";	//use nl2br to print on new lines
				echo "\t\t\t\t\t</article>\n";
			}
			echo "\t\t\t</div>\n";

			echo "\t\t</section>\n";
		}
		?>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>