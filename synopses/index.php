<?php
include	"../php/top.php";
?>
	<article class='movieContainer'>
		<h1>Synopses</h1>
		<?php
		//INNER JOIN instead of LEFT so that even gets movies without pictures
		$query="SELECT fldTitle, fldRating, fldRuntime, fldSynopsis, fldDirector, fldImgFilename FROM tblSynopses
		 JOIN tblMovies ON pmkMovieId=tblSynopses.fnkMovieId
		 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 WHERE ( ( (fldDisplay=?)  ) AND fldReleaseDate<=CURDATE() )
		 ORDER BY fldReleaseDate DESC";
		$data=array('Current');		//only get current
		$synopsesList=$thisDatabaseReader->select($query,$data,1,2,0,1);

		foreach($synopsesList as $movie){
			echo "\t\t<section class='articleBg fullWidth'>\n";
			$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
			if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
				$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
			}
			echo "\t\t\t\t<figure><img alt='".$movie['fldTitle']."' src='".$imgFile."'></figure>\n";
			
			echo "\t\t\t<div class='fullWidthInfo'>\n";
			echo "\t\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			echo "\t\t\t\t<p>";
			echo runtimeToHoursMinutes($movie['fldRuntime'])."<br>\n";
			echo $movie['fldRating']."<br>\n";
			if($movie['fldDirector'] !=''){echo "Directed by ".$movie['fldDirector']."<br>"; }
			echo "</p>\n";
			echo "\t\t\t\t<p><br>".$movie['fldSynopsis']."</p>\n";		//put in pre tags so that newlines display from the database. Also print <br> to put more space before the synopsis
			echo "\t\t\t</div>\n";

			echo "\t\t</section>\n";
		}

		?>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>