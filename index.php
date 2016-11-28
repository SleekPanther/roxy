<?php
session_start();
include	"php/top.php";
?>
	<article>
		<?php
		// $query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
		//  fldImgFilename FROM tblMovies
		//  JOIN tblPictures ON pmkMovieId=fnkMovieId
		//  WHERE ( (fldDisplay='Current' OR fldDisplay='Coming Soon') AND (fldReleaseDate<=CURDATE()) )
		//   ORDER BY fldReleaseDate DESC";

		//get current movies. & only if the release date is BEFORE today
		$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
		 fldImgFilename FROM tblMovies
		 JOIN tblPictures ON pmkMovieId=fnkMovieId
		 WHERE ( (fldDisplay=? OR fldDisplay=?) AND (fldReleaseDate<=CURDATE()) )
		 ORDER BY fldReleaseDate DESC";

		$data=array('Current','Coming Soon');
		$movies=$thisDatabaseReader->select($query,$data,1,3,0,1);
		echo "<pre>";
		print_r($movies);
		echo "</pre>";
		?>
	</article>
<?php
include	"php/footer.php";
?>