<?php
session_start();
include	"../php/top.php";

if (!( isset($_GET['showtimeId']) && isset($_GET['movieId']) )){
	header('Location: index.php');	//redirect if they accidentally clicked this page & GET isn't set
}
$showtimeId=htmlentities($_GET['showtimeId'], ENT_QUOTES, "UTF-8");	//sanitize value from GET array
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");

$query="SELECT fldTitle, pmkShowtimeId, fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension 
	 FROM tblShowtimes
	 JOIN tblMovies ON pmkMovieId=fnkMovieId
	 WHERE pmkShowtimeId=? AND fnkMovieId=?";
$data=array($showtimeId,$currentMovieId);
$showtimeInfo=$thisDatabaseReader->select($query,$data,1,1);

if(empty($showtimeInfo)){		//redirect if showtime doesn't exist
	header('Location: index.php');	
}

include '../php/magic/showtime-variables.php';		//initialize variables
$errorMsg=array();

$showtimeHour=$showtimeInfo[0]['fldHour'];		//fill out form with data from table, not the original php include
$showtimeMinute=$showtimeInfo[0]['fldMinute'];
$showtimeMeridian=$showtimeInfo[0]['fldMeridian'];
$showtimePosts=$showtimeInfo[0]['fldShowtimePosts'];
$showtimeExpires=$showtimeInfo[0]['fldShowtimeExpires'];
$showtimeDimension=$showtimeInfo[0]['fldDimension'];

if(isset($_POST['btnUpdateShowtime'])){
	include "../php/magic/showtime-validation.php";

	if(!$errorMsg){
		$query="UPDATE tblShowtimes SET fldHour=?, fldMinute=?, fldMeridian=?, fldShowtimePosts=?, fldShowtimeExpires=?, fldDimension=? WHERE pmkShowtimeId=?";
		$data=array($showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension,$showtimeInfo[0]['pmkShowtimeId']);
		$thisDatabaseWriter->insert($query,$data,1);

		$_SESSION['whatJustHappened']='Showtime Updated';
		header('Location: edit.php?movieId='.$currentMovieId);
	}
}elseif(isset($_POST['btnCancel'])){
	$_SESSION['whatJustHappened']='Canceled Updating Showtime';
	header('Location: edit.php?movieId='.$currentMovieId);
}

$tabIndex=1;		//print on every form input element & increment
?>		
	<article>
	<?php
	include $upFolderPlaceholder.'php/lib/display-form-errors.php';
	?>
		<form name='frmDeleteShowtime' id='frmDeleteShowtime' action='<?php echo PHP_SELF."?showtimeId=".$showtimeId."&movieId=".$currentMovieId;?>' method='post'>
			<?php
			echo "\t\t\t\t\t<h2>Edit Showtime</h2>\n";

			echo "\t\t\t<table>\n";

			include '../php/magic/showtime-form.php';

			echo "\t\t\t</table>\n";
			
			echo "\t\t\t<br><input type='submit' name='btnUpdateShowtime' id='btnUpdateShowtime' value='Update Showtime'>";
			echo "<input type='submit' name='btnCancel' id='btnCancel' value='Cancel'>\n";
			?>
		</form>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>