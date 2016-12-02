<?php
session_start();
include	"php/top.php";
?>
	<article class='movieContainer'>
		<h1>Prices</h1>
		<article class='halfCol articleBg'>
			<h2>Matinee Rates (before 6 pm)</h2>
			<table>
				<tr>
					<td>GeneralAdmission</td>
					<td>$7.50</td>
				</tr>
				<tr>
					<td>Students (with valid ID)</td>
					<td>$6.75</td>
				</tr>
				<tr>
					<td>Seniors</td>
					<td>$6.50</td>
				</tr>
				<tr>
					<td>Children (Under 12)</td>
					<td>$6.50</td>
				</tr>
			</table>

			<section class="threeDColor">
				<br>
				<table>
					<tr>
						<th colspan="2">3D</th>
					</tr>
					<tr>
						<td>GeneralAdmission</td>
						<td>$9.75</td>
					</tr>
					<tr>
						<td>Students (with valid ID)</td>
						<td>$8.75</td>
					</tr>
					<tr>
						<td>Seniors</td>
						<td>$8.75</td>
					</tr>
					<tr>
						<td>Children (Under 12)</td>
						<td>$8.75</td>
					</tr>
				</table>
			</section> 
		</article>

		<article class='halfCol articleBg'>
			<h2>Evening Rates (after 6 pm)</h2>
			<table>
				<tr>
					<th>GeneralAdmission</th>
					<th>$9.75</th>
				</tr>
				<tr>
					<td>Students (with valid ID)</td>
					<td>$8.75</td>
				</tr>
				<tr>
					<td>Seniors</td>
					<td>$7.75</td>
				</tr>
				<tr>
					<td>Children (Under 12)</td>
					<td>$6.50</td>
				</tr>
			</table>

			<section class="threeDColor">
				<br>
				<table>
					<tr>
						<th colspan="2">3D</th>
					</tr>
					<tr>
						<th>GeneralAdmission</th>
						<th>$11.75</th>
					</tr>
					<tr>
						<td>Students (with valid ID)</td>
						<td>$10.75</td>
					</tr>
					<tr>
						<td>Seniors</td>
						<td>$8.75</td>
					</tr>
					<tr>
						<td>Children (Under 12)</td>
						<td>$8.75</td>
					</tr>
				</table>
			</section>
		</article>

		<?php

		// for($repeat=true, $iterations=0, $date=date("Y-m-d", strtotime('last friday')); $repeat && $iterations<2; $iterations++, $date=date("Y-m-d", strtotime($date.' +7 days'))){
		// 	$dateWeekEnds=date("Y-m-d", strtotime($date.'+6 days'));

		// 	echo "<section class='tCent clear'>\n";
		// 	echo "\t\t\t<h2>Showtimes for</h2>\n";
		// 	echo "\t\t\t<h3>".dateSqlToNice($date)." to ".dateSqlToNice( $dateWeekEnds )."</h3>\n";
		// 	echo "\t\t</section>\n";
		// 	foreach($movies as $movie){
		// 		echo "\t\t<article class='movie'>\n";
		// 		$imgFile=$upFolderPlaceholder.'images/posters/ref/placeholder.png';
		// 		if(file_exists($upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'])){
		// 			$imgFile=$upFolderPlaceholder.'images/posters/'.$movie['fldImgFilename'];
		// 		}
		// 		echo "\t\t\t<figure><img alt='' src='".$imgFile."'></figure>\n";
		// 		echo "\t\t\t<h2>".$movie['fldTitle']."</h2>\n";
		// 		echo "\t\t\t<p>(".$movie['fldRating'].') '.runtimeToHoursMinutes($movie['fldRuntime'])."</p>\n";
		// 		echo "\t\t\t<section class='showtimesDetail'>\n";

		// 		// $query="SELECT fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
		// 		//  WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires <= CAST('2016-11-27' AS DATE) ) AND  fnkMovieId=41)
		// 		//   ORDER BY fldMeridian, fldHour, fldMinute";
		// 		//WHERE ((fldShowtimePosts >= CAST('2016-11-25' AS DATE) ) AND (fldShowtimeExpires >= CURDATE() ) AND  fnkMovieId=41)
		// 		$query="SELECT pmkShowtimeId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes
		// 		 WHERE ( (fldShowtimePosts <= ? ) AND (fldShowtimeExpires >= ? ) AND fnkMovieId=?)
		// 		 ORDER BY fldDimension, fldMeridian, fldHour, fldMinute";
		// 		$data=array($dateWeekEnds, $date, $movie['pmkMovieId']);
		// 		$showtimes=$thisDatabaseReader->select($query,$data,1,3,0,2);

		// 		$showtimes2D=array();
		// 		$showtimes3D=array();
		// 		foreach($showtimes as $showtime){
		// 			//IF AM, then print AM
					
		// 			if($showtime['fldDimension']=='3D'){
		// 				$showtimes3D[]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2).' '.$showtime['fldDimension'];
		// 			}else{
		// 				$showtimes2D[]= $showtime['fldHour'].":".leadingZeros($showtime['fldMinute'],2);
		// 			}
		// 		}
		// 		if(!empty($showtimes2D)){
		// 			echo "\t\t\t\t<p>";
		// 			foreach($showtimes2D as $time){
		// 				echo $time.' ';
		// 			}
		// 			echo "</p>\n";
		// 		}

		// 		if(!empty($showtimes3D)){
		// 			echo "\t\t\t\t<p>";
		// 			foreach($showtimes3D as $time){
		// 				echo $time." &nbsp;";
		// 			}
		// 			echo "</p>\n";
		// 		}

		// 		echo "\t\t\t</section>\n";
		// 		echo "\t\t</article>\n";
		// 	}

		// 	$repeat=false;	//set false after each iteration
		// 	if(date("l", strtotime('today')) == 'Thursday'){		//check if it's 1 day before a new week & then we want to say YES DO REPEAT (to print next week's schedule)
		//         $repeat=true;
		//     }
		// }

		?>
	</article>
<?php
include	"php/footer.php";
?>