<?php
session_start();
include	"php/top.php";
?>
	<article>
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
		$date=date("Y-m-d", strtotime('last friday'));
		//$date2=date("Y-m-d", strtotime($date.'+6 days'));
		foreach($movies as $movie){
			echo "\t\t<article class='movie'>\n";
			//pictures are ratio 8.5:12 
			echo "\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
			$runtime=runtimeToHoursMinutes($movie['fldRuntime']);
			echo "\t\t\t<p> (".$movie['fldRating'].') '.$runtime[0].' h '.$runtime[1].' m'."</p>\n";
			echo "\t\t\t<section class='showtimesDetail'>\n";

			// $query="SELECT fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
			//  WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires <= CAST('2016-11-27' AS DATE) ) AND  fnkMovieId=41)
			//   ORDER BY fldMeridian, fldHour, fldMinute";
			//WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires >= CURDATE() ) AND  fnkMovieId=41)
			$query="SELECT pmkShowtimeId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
			 WHERE ((fldShowtimePosts >= ? ) AND (fldShowtimeExpires >= CURDATE() ) AND fnkMovieId=?)
			 ORDER BY fldDimension, fldMeridian, fldHour, fldMinute";
			$data=array($date,$movie['pmkMovieId']);
			// $thisDatabaseReader->testquery($query,$data,1,3,0,2);
			// $showtimes=$thisDatabaseReader->testquery($query,$data,1,3,0,2);
			$showtimes=$thisDatabaseReader->select($query,$data,1,3,0,2);
			// echo "<pre>";
			// print_r($showtimes);
			// echo "</pre>";

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
			echo "\t\t\t\t<p>";
			foreach($showtimes2D as $time){
				echo $time.' ';
			}
			echo "<p>\n";

			if(!empty($showtimes3D)){
				echo "\t\t\t\t<p>";
				foreach($showtimes3D as $time){
					echo $time." &nbsp;";
				}
				echo "<p>\n";
			}

			echo "\t\t\t</section>\n";
			echo "\t\t</article>\n";
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