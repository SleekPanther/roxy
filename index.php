<?php
include	"php/top.php";
?>
	<article class='movieContainer'>
		<?php
		//$repeat controls if print 1 or 2 copies of showtimes. Start true so always happens once, then print next week's schedule if it's thursday
		//$dateWeekStarts is a friday, the beginning of a 'week'
		//iterates while $repeat==true & only 1 or 2 iterations (no more)
		//increments increase $iterations & add 7 days to the week's start date
		for($repeat=true, $iterations=0, $dateWeekStarts=date("Y-m-d", strtotime('last friday', strtotime('tomorrow')) ); $repeat && $iterations<2; $iterations++, $dateWeekStarts=date("Y-m-d", strtotime($dateWeekStarts.' +7 days'))){
			$dateWeekEnds=date("Y-m-d", strtotime($dateWeekStarts.'+6 days'));

			//get current movies: Left join pictures in case non-existant. Inner join showtimes 
			//where conditions: 1)must be 'Current', 2)released before the end of the week 3)showtimes exist 3a)showtime posts before the end of the week 3b)showtime expires AFTER the start of the week
			//DISTINCT might be optional, but leave it just in case
			$query="SELECT DISTINCT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
			 fldImgFilename FROM tblMovies
			 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
			 JOIN tblShowtimes ON pmkmovieId=tblShowtimes.fnkMovieId
			 WHERE ( (fldDisplay=? ) AND (fldReleaseDate<=?) ) AND ( (fldShowtimePosts <= ? ) AND (fldShowtimeExpires >= ? ) )
			 ORDER BY fldReleaseDate DESC";
			$data=array('Current',$dateWeekEnds,$dateWeekEnds,$dateWeekStarts);
			$movies=$thisDatabaseReader->select($query,$data,1,4,0,3);
			$thisDatabaseReader->testquery($query,$data,1,4,0,3);

			echo "<section class='tCent clear'>\n";
			echo "\t\t\t<h2>Showtimes for</h2>\n";
			echo "\t\t\t<h3>".dateSqlToNice($dateWeekStarts)." to ".dateSqlToNice($dateWeekEnds)."</h3>\n";
			echo "\t\t</section>\n";
			foreach($movies as $movie){
				echo "\t\t<div class='articleContainer halfCol'>\n";
				echo "\t\t<article class='movie'>\n";
				$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
				if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
					$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
				}
				echo "\t\t\t<figure><img alt='' src='".$imgFile."'></figure>\n";
				echo "\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
				echo "\t\t\t<p>(".$movie['fldRating'].') '.runtimeToHoursMinutes($movie['fldRuntime'])."</p>\n";
				echo "\t\t\t<section class='showtimesDetail'>\n";

				$query="SELECT pmkShowtimeId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
				 WHERE ( (fldShowtimePosts <= ? ) AND (fldShowtimeExpires >= ? ) AND fnkMovieId=?)
				 ORDER BY fldDimension, fldMeridian, fldHour, fldMinute";
				$data=array($dateWeekEnds, $dateWeekStarts, $movie['pmkMovieId']);
				$showtimes=$thisDatabaseReader->select($query,$data,1,3,0,2);

				$showtimesDimensionArray=array(array(),array());	//0th index=2D showtimes, 1st=3D showtimes
				foreach($showtimes as $showtime){		//loop through & add 3D to each showtimes if it is 3D
					if($showtime['fldDimension']=='3D'){
						$meridian='';					//IF AM, then print AM, else just print nothing
						if($showtime['fldMeridian']=='AM'){
							$meridian='AM';
						}
						$showtimesDimensionArray[1][]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2).' '.$meridian;
					}else{
						$meridian='';
						if($showtime['fldMeridian']=='AM'){
							$meridian='AM';
						}
						$showtimesDimensionArray[0][]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2).' '.$meridian;
					}
				}

				$whichDimensionLabels=array('',"<span class='dimension-label-highlight'>".'3D'.":</span> &nbsp;&nbsp;");
				for($i=0; $i<count($showtimesDimensionArray); $i++){
					if($showtimesDimensionArray[$i]){	
						if($showtimesDimensionArray[1]){	//if there are 3d showtimes, we print labels for both
							$whichDimensionLabels[0]="<span class='dimension-label-highlight'>".'2D'.":</span> &nbsp;&nbsp;";
						}
						echo "\t\t\t\t<p>".$whichDimensionLabels[$i];
						foreach($showtimesDimensionArray[$i] as $time){
							echo $time.' &nbsp;&nbsp;&nbsp;';
						}
						echo "</p>\n";
					}
				}

				echo "\t\t\t</section>\n";
				echo "\t\t</article>\n";
				echo "\t\t</div>\n";
			}

			$repeat=false;	//set false after each iteration
			if(date("l", strtotime('today')) == 'Thursday'){		//check if it's 1 day before a new week & then we want to say YES DO REPEAT (to print next week's schedule)
		        $repeat=true;
		    }
		}
		?>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>