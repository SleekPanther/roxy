<?php
include	"../php/top.php";
?>
	<article class='movieContainer'>
		<h1>Coming Soon</h1>
		<?php
		//left join tblSynopses to get movie info even if description is empty
		$query="SELECT fldTitle, fldRating, fldRuntime, fldReleaseDate, fldSynopsis, fldDirector, fldTrailerLink, fldImgFilename FROM tblMovies
		 LEFT JOIN tblSynopses ON pmkMovieId=tblSynopses.fnkMovieId
		 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 WHERE ( ( (fldDisplay=?)  ) AND fldReleaseDate>=CURDATE() )
		 ORDER BY fldReleaseDate ";
		$data=array('Current');				//current movies with release date in the future
		$moviesSoon=$thisDatabaseReader->select($query,$data,1,2,0,1);

		foreach($moviesSoon as $movie){
			echo "\t\t<section class='articleBg fullWidth'>\n";

			$imgFile=IMAGE_POSTER_PATH.'ref/placeholder.png';
			if(file_exists(IMAGE_POSTER_PATH.$movie['fldImgFilename'])){
				$imgFile=IMAGE_POSTER_PATH.$movie['fldImgFilename'];
			}
			echo "\t\t\t\t<figure><img alt='".$movie['fldTitle']."' src='".$imgFile."'>\n";
			echo "</figure>\n";

			echo "\t\t\t<div class='fullWidthInfo'>\n";
			echo "\t\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			echo "\t\t\t\t<h3>Starting ".dateSqlToNice($movie['fldReleaseDate'])."</h3>\n";
			echo "\t\t\t\t<p>";
			echo runtimeToHoursMinutes($movie['fldRuntime'])."<br>\n";
			echo $movie['fldRating']."<br>\n";
			if($movie['fldDirector'] !=''){
				echo "Directed by ".$movie['fldDirector']."<br>";
			}
			echo "</p>\n";
			if($movie['fldTrailerLink'] && urlExists($movie['fldTrailerLink'])){
				echo "<p><a href='".$movie['fldTrailerLink']."' target='_blank'>"."<img class='imgTrailerLink' src='".$upFolderPlaceholder.'images/logos/embedded/watch-trailer.png'."'>"."</a></p>\n";
			}
			echo "\t\t\t\t<p><br>".nl2br($movie['fldSynopsis'],false)."</p>\n";
			echo "\t\t\t</div>\n";

			echo "\t\t</section>\n";
		}

		?>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>