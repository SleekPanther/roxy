<?php
session_start();
include	"php/top.php";
?>
	<article class='movieContainer'>
		<?php
		$query="SELECT fldTitle, fldRating, fldRuntime, fldSynopsis, fldDirector, fldImgFilename FROM tblSynopses
		 JOIN tblMovies ON pmkMovieId=tblSynopses.fnkMovieId
		 JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 WHERE ( ( (fldDisplay=?) OR (fldDisplay=?) ) AND fldReleaseDate<=CURDATE() )
		 ORDER BY fldReleaseDate DESC";
		$data=array('Current','Coming Soon');
		$synopsesList=$thisDatabaseReader->select($query,$data,1,3,0,1);

		echo "<pre>";
		print_r($synopsesList);
		echo "</pre>";


		?>
	</article>
<?php
include	"php/footer.php";
?>