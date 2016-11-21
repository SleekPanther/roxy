<?php
session_start();
include	"../php/top.php";

$currentMovieId=-1;
$showtimeId=-1;		//initialize to bad number
if (!( isset($_GET['showtimeId']) && isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$showtimeId=htmlentities($_GET['showtimeId'], ENT_QUOTES, "UTF-8");	//sanitize value from GET array
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");

$query="SELECT fldTitle, pmkShowtimeId, fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension 
	 FROM tblShowtimes
	 JOIN tblMovies ON pmkMovieId=fnkMovieId
	 WHERE pmkShowtimeId=?";
$data=array($showtimeId);
$showtimeInfo=$thisDatabaseReader->select($query,$data,1);

if(empty($showtimeInfo)){		//redirect if showtime doesn't exist
	header('Location: index.php');	
}else{	//query movies table & redirect if movie doesn't exist
	$query="SELECT * FROM tblMovies WHERE pmkMovieId=?";
	$data=array($currentMovieId);
	$movieInfo=$thisDatabaseReader->select($query,$data,1);
	if(empty($movieInfo)){
		header('Location: index.php');	//redirect them if no showtime/movie exists in the database
	}
}

if(isset($_POST['btnDeleteShowtime'])){
	$query="DELETE FROM tblShowtimes WHERE pmkShowtimeId=?";
	$data=array($showtimeId);
	$thisDatabaseWriter->insert($query,$data,1);

	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment

?>		
	<article>
		<form name='frmDeleteShowtime' id='frmDeleteShowtime' action='<?php echo PHP_SELF."?showtimeId=".$showtimeId."&movieId=".$currentMovieId."'";?>' method='post'>
			<h2>Delte Confirmation</h2>
			<h3>Are you sure you want to delete the showtime with the following information?</h3>
			<?php
			foreach($showtimeInfo as $oneShowtime){
				echo "<p>Title: ".$oneShowtime['fldTitle']."</p>\n";
				echo "\t\t\t<p>".$oneShowtime['fldHour'].":".leadingZeros($oneShowtime['fldMinute'],2)." ".$oneShowtime['fldMeridian']." ".$oneShowtime['fldDimension']."\n";
				echo "\t\t\t<p>Showtime Displays: ".dateSqlToNice($oneShowtime['fldShowtimePosts'])." to ".dateSqlToNice($oneShowtime['fldShowtimeExpires'])."\n";
			}
			echo "\t\t\t<br><input type='submit' name='btnDeleteShowtime' id='btnDeleteShowtime' value='Delete Showtime'>\n";
			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>