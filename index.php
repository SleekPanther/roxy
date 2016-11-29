<?php
session_start();
include	"php/top.php";
?>
	<article class='movieContainer'>
		<?php

		//get current movies. & only if the release date is BEFORE today
		$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
		 fldImgFilename FROM tblMovies
		 JOIN tblPictures ON pmkMovieId=fnkMovieId
		 WHERE ( (fldDisplay=? OR fldDisplay=?) AND (fldReleaseDate<=CURDATE()) )
		 ORDER BY fldReleaseDate DESC";
		$data=array('Current','Coming Soon');
		$movies=$thisDatabaseReader->select($query,$data,1,3,0,1);
		

		// $counter=0;
		// for($date=date("Y-m-d", strtotime('last sunday')); $counter<1; $date2=date("Y-m-d", strtotime($date.'+7 days'))){
		//$date=date("Y-m-d", strtotime('last friday'));
		

		//$repeat controls if print 1 or 2 copies of showtimes. Start true so always happens once, then print next week's schedule if it's thursday
		//$date is a friday, the beginning of a 'week'
		//iterates while $repeat==true & only 1 or 2 iterations (no more)
		//increments increase $iterations & add 7 days to the week's start date
		for($repeat=true, $iterations=0, $date=date("Y-m-d", strtotime('last friday')); $repeat && $iterations<2; $iterations++, $date=date("Y-m-d", strtotime($date.' +7 days'))){
			$dateWeekEnds=date("Y-m-d", strtotime($date.'+6 days'));

			echo "<section class='tCent clear'>\n";
			echo "\t\t\t<h2>Showtimes for</h2>\n";
			echo "\t\t\t<h3>".dateSqlToNice($date)." to ".dateSqlToNice( $dateWeekEnds )."</h3>\n";
			echo "\t\t</section>\n";
			foreach($movies as $movie){
				echo "\t\t<article class='movie'>\n";
				$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
				if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
					$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
				}
				echo "\t\t\t<figure><img alt='' src='".$imgFile."'></figure>\n";
				echo "\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
				$runtime=runtimeToHoursMinutes($movie['fldRuntime']);
				echo "\t\t\t<p>(".$movie['fldRating'].') '.$runtime[0].' h '.$runtime[1].' m'."</p>\n";
				echo "\t\t\t<section class='showtimesDetail'>\n";

				// $query="SELECT fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
				//  WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires <= CAST('2016-11-27' AS DATE) ) AND  fnkMovieId=41)
				//   ORDER BY fldMeridian, fldHour, fldMinute";
				//WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires >= CURDATE() ) AND  fnkMovieId=41)
				$query="SELECT pmkShowtimeId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
				 WHERE ((fldShowtimePosts >= ? AND fldShowtimePosts <= ? ) AND (fldShowtimeExpires >= CURDATE() ) AND fnkMovieId=?)
				 ORDER BY fldDimension, fldMeridian, fldHour, fldMinute";
				$data=array($date,$dateWeekEnds, $movie['pmkMovieId']);
				$showtimes=$thisDatabaseReader->select($query,$data,1,4,0,3);

				$showtimes2D=array();
				$showtimes3D=array();
				foreach($showtimes as $showtime){
					//IF AM, then print AM
					
					if($showtime['fldDimension']=='3D'){
						$showtimes3D[]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2).' '.$showtime['fldDimension'];
					}else{
						$showtimes2D[]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2);
					}
				}
				if(!empty($showtimes2D)){
					echo "\t\t\t\t<p>";
					foreach($showtimes2D as $time){
						echo $time.' ';
					}
					echo "</p>\n";
				}

				if(!empty($showtimes3D)){
					echo "\t\t\t\t<p>";
					foreach($showtimes3D as $time){
						echo $time." &nbsp;";
					}
					echo "</p>\n";
				}

				echo "\t\t\t</section>\n";
				echo "\t\t</article>\n";
			}

			$repeat=false;	//set false after each iteration
			if(date("l", strtotime('today')) == 'Tuesday'){		//check if it's 1 day before a new week & then we want to say YES DO REPEAT (to print next week's schedule)
		        $repeat=true;
		    }
		}

		// $a=0;
		// for($date=date("Y-m-d", strtotime('last sunday'));$a<3;$date=date("Y-m-d", strtotime($date.'+7 days'))){
		// 	echo $date."<br>";
		// 	$a++;
		// }
		?>
	</article>
<?php
include	"php/footer.php";
?>