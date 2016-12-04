<?php
session_start();
include	"php/top.php";
?>
	<article class='movieContainer'>
		<h1>Coming Soon</h1>
		<?php
		//left join tblSynopses to get movie info even if description is empty
		$query="SELECT fldTitle, fldRating, fldRuntime, fldReleaseDate, fldSynopsis, fldDirector, fldImgFilename FROM tblMovies
		 LEFT JOIN tblSynopses ON pmkMovieId=tblSynopses.fnkMovieId
		 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
		 WHERE ( ( (fldDisplay=?)  ) AND fldReleaseDate>=CURDATE() )
		 ORDER BY fldReleaseDate ";
		$data=array('Coming Soon');		//only get 'Coming soon'
		$moviesSoon=$thisDatabaseReader->select($query,$data,1,2,0,1);

		foreach($moviesSoon as $movie){
			echo "\t\t<section class='articleBg fullWidth'>\n";

			$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
			if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
				$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
			}
			echo "\t\t\t\t<figure><img alt='".$movie['fldTitle']."' src='".$imgFile."'></figure>\n";

			echo "\t\t\t<div class='fullWidthInfo'>\n";
			echo "\t\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			echo "\t\t\t\t<h3>Starting ".dateSqlToNice($movie['fldReleaseDate'])."</h3>\n";
			echo "\t\t\t\t<p>";
			echo runtimeToHoursMinutes($movie['fldRuntime'])."<br>\n";
			echo $movie['fldRating']."<br>\n";
			if($movie['fldDirector'] !=''){echo "Directed by ".$movie['fldDirector']."<br>"; }
			echo "</p>\n";
			echo "\t\t\t\t<p><br>".$movie['fldSynopsis']."</p>\n";
			echo "\t\t\t</div>\n";

			echo "\t\t</section>\n";
		}

		?>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>