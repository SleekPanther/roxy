<?php
session_start();
include	"php/top.php";
?>
	<article class='movieContainer'>
		<h1>Synopses</h1>
		<?php
	// 	SELECT fldTitle, fldRating, fldRuntime, fldSynopsis, fldDirector, fldImgFilename FROM tblSynopses
 // JOIN tblMovies ON pmkMovieId=tblSynopses.fnkMovieId
 // JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
 // WHERE ( ( (fldDisplay='Current') OR (fldDisplay='Coming Soon') ) AND fldReleaseDate<=CURDATE() )
 // ORDER BY fldReleaseDate DESC
		$query="SELECT fldTitle, fldRating, fldRuntime, fldSynopsis, fldDirector, fldImgFilename FROM tblSynopses
		 JOIN tblMovies ON pmkMovieId=tblSynopses.fnkMovieId
		 JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 WHERE ( ( (fldDisplay=?) OR (fldDisplay=?) ) AND fldReleaseDate<=CURDATE() )
		 ORDER BY fldReleaseDate DESC";
		$data=array('Current','Coming Soon');
		$synopsesList=$thisDatabaseReader->select($query,$data,1,3,0,1);

		foreach($synopsesList as $movie){
			echo "\t\t<section class='articleBg'>\n";
			echo "\t\t\t<div>\n";
			echo "\t\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			echo "\t\t\t\t<p>";
			echo runtimeToHoursMinutes($movie['fldRuntime'])."<br>\n";
			echo $movie['fldRating']."<br>\n";
			if($movie['fldDirector'] !=''){echo "Directed by ".$movie['fldDirector']."<br>"; }
			echo "</p>\n";
			echo "\t\t\t\t<pre><br>".$movie['fldSynopsis']."</pre>\n";		//put in pre tags so that newlines display from the database. Also print <br> to put more space before the synopsis 
			echo "\t\t\t</div>\n";

			echo "\t\t\t<div>\n";
			$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
			if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
				$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
			}
			echo "\t\t\t\t<figure><img alt='".$movie['fldTitle']."' src='".$imgFile."'></figure>\n";
			echo "\t\t\t</div>\n";
			echo "\t\t</section>\n";
		}

		?>
	</article>
<?php
include	"php/footer.php";
?>