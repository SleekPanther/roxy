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

include '../php/magic/showtime-variables.php';		//initialize variables

$showtimeHour=$showtimeInfo[0]['fldHour'];		//fill out form with data from table, not the original php include
$showtimeMinute=$showtimeInfo[0]['fldMinute'];
$showtimeMeridian=$showtimeInfo[0]['fldMeridian'];
$showtimePosts=$showtimeInfo[0]['fldShowtimePosts'];
$showtimeExpires=$showtimeInfo[0]['fldShowtimeExpires'];
$showtimeDimension=$showtimeInfo[0]['fldDimension'];

if(isset($_POST['btnUpdateShowtime'])){
	include "../php/magic/showtime-validation.php";

	$query="UPDATE tblShowtimes SET fldHour=?, fldMinute=?, fldMeridian=?, fldShowtimePosts=?, fldShowtimeExpires=?, fldDimension=? WHERE pmkShowtimeId=?";
	$data=array($showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension,$showtimeInfo[0]['pmkShowtimeId']);
	$thisDatabaseWriter->insert($query,$data,1);

	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment

?>		
	<article>
		<form name='frmDeleteShowtime' id='frmDeleteShowtime' action='<?php echo PHP_SELF."?showtimeId=".$showtimeId."&movieId=".$currentMovieId."'";?>' method='post'>
			<?php
			echo "\t\t\t<table>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><h2>Edit Showtime</h2></td>\n";
			echo "\t\t\t\t</tr>\n";

			include '../php/magic/showtime-form.php';

			echo "\t\t\t</table>\n";
			
			echo "\t\t\t<br><input type='submit' name='btnUpdateShowtime' id='btnUpdateShowtime' value='Update Showtime'>\n";
			?>
		</form>
	</article>
<?php
include	"../php/footer.php";
?>