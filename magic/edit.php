<?php
include	"../php/top.php";

$tabIndex=1;		//print on every form input element & increment

if (!isset($_GET['movieId'])){
	header('Location: index.php');	//redirect to homepage if they accidentally clicked this page & GET isn't set
}
$currentMovieId=htmlentities($_GET['movieId'], ENT_QUOTES, "UTF-8");		//sanitize value from GET array
$currentMovieIdError=false;

$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
fldSynopsis,
fldImgFilename FROM tblMovies
 LEFT JOIN tblSynopses ON pmkMovieId=tblSynopses.fnkMovieId
 LEFT JOIN tblPictures ON pmkMovieId=tblPictures.fnkMovieId
 WHERE pmkMovieId LIKE ?";
$data=array($currentMovieId);
$movieInfo=$thisDatabaseReader->select($query,$data,1);	//query database to see if movie exists
if(empty($movieInfo)){			//redirect them again if the movie doesn't exist
	header('Location: index.php');
}

//variables to hold form input (get from database)
$title=$movieInfo[0]['fldTitle'];
$runtime=$movieInfo[0]['fldRuntime'];
$rating=$movieInfo[0]['fldRating'];
$releaseDate=$movieInfo[0]['fldReleaseDate'];
$display=$movieInfo[0]['fldDisplay'];
$director=$movieInfo[0]['fldDirector'];
$synopsis=$movieInfo[0]['fldSynopsis'];
if($movieInfo[0]['fldImgFilename'] == NULL){	//if no image is selected, then store 'none' so that the correct radio button will be CHECKED
	$poster='none';
}else{
	$poster=$movieInfo[0]['fldImgFilename'];
}

include $upFolderPlaceholder.'php/magic/review-variables.php';

include $upFolderPlaceholder.'php/magic/showtime-variables.php';	//initialize variables in separate file

include $upFolderPlaceholder.'php/magic/add-edit-variables.php';

if(isset($_POST['btnChooseMovie'])){
	$currentMovieId=htmlentities($_POST['lstChooseMovie'], ENT_QUOTES, "UTF-8");

	$query="SELECT pmkMovieId FROM tblMovies WHERE pmkMovieId=?";
	$data=array($currentMovieId);
	$newMovie=$thisDatabaseReader->select($query,$data,1);

	if(empty($newMovie)){
		$errorMsgMovie[]='"Choose Movie" dropdown has Invalid Movie Id';
		$currentMovieIdError=true;
	}

	if(!$errorMsgMovie){		//switch the movie selected using a redirect
		header('Location: edit.php?movieId='.$currentMovieId);
	}
}
elseif(isset($_POST['btnUpdateMovie']) || isset($_POST['btnAddShowtime']) || isset($_POST['btnAddReview'])){
	include $upFolderPlaceholder.'php/magic/add-edit-validation.php';

	if(isset($_POST['btnAddReview'])){
		include $upFolderPlaceholder.'php/magic/review-validation.php';

		if(!$errorMsgMovie){
			$query="INSERT INTO tblReviews (fnkMovieId, fldAuthor, fldReviewDate, fldReviewSource, fldReviewLink, fldReview) VALUES (?,?,?,?,?,?)";
			$data=array($currentMovieId,$_SESSION['reviewAuthor'],$_SESSION['reviewDate'],$_SESSION['reviewSource'],$_SESSION['reviewLink'],$_SESSION['review']);
			$databaseSuccess=$thisDatabaseWriter->insert($query,$data,0);

			//reset values so it doesn't "remember" the last thing entered
			$_SESSION['reviewAuthor']='';
			$_SESSION['reviewDate']=date('Y-m-d', strtotime('today'));
			$_SESSION['reviewSource']='';
			$_SESSION['reviewLink']='';
			$_SESSION['review']='';

			$_SESSION['whatJustHappened']='Review added';
			if(!$databaseSuccess){
				$_SESSION['whatJustHappened']='Error! Failed to add review';
			}
		}
	}
	
	include $upFolderPlaceholder.'php/magic/showtime-validation.php';

	include $upFolderPlaceholder.'php/magic/image-validation.php';

	if(!$errorMsgMovie){
		if(isset($_POST['btnUpdateMovie'])){
			$query="UPDATE tblMovies SET fldTitle=?, fldRuntime=?, fldRating=?, fldReleaseDate=?, fldDisplay=?, fldDirector=? WHERE pmkMovieId LIKE ?";
			$data=array($title,$runtime,$rating,$releaseDate,$display,$director,$currentMovieId);
			$thisDatabaseWriter->insert($query,$data,1);

			if($synopsis !=''){		//if it's not empty, add to the database
				$query="INSERT INTO tblSynopses SET fnkMovieId=?, fldSynopsis=? ON DUPLICATE KEY UPDATE fldSynopsis=?";
				$data=array($currentMovieId,$synopsis,$synopsis);
				$thisDatabaseWriter->insert($query,$data,0);
			}
			else{	//else delete empty entries. This technically attempts to remove even if not in databse, but doesn't matter & is mainly for when they already have a description, but then clear the textarea (This avoids leaving empty synopses left in table)
				$query="DELETE FROM tblSynopses WHERE fnkMovieId LIKE ?";
				$data=array($currentMovieId);
				$thisDatabaseWriter->insert($query,$data,1);
			}
			
			$query="INSERT INTO tblPictures (fnkMovieId, fldImgFilename,fldImgType) VALUES (?,?,?) ON DUPLICATE KEY UPDATE fldImgFilename=?";
			$data=array($currentMovieId,$poster,'Poster',$poster);
			$thisDatabaseWriter->insert($query,$data,0);

			$_SESSION['whatJustHappened']='Movie Info Updated';
		}elseif(isset($_POST['btnAddShowtime'])){
			$query="INSERT INTO tblShowtimes (fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension) VALUES (?,?,?,?,?,?,?)";
			$data=array($currentMovieId,$showtimeHour,$showtimeMinute,$showtimeMeridian,$showtimePosts,$showtimeExpires,$showtimeDimension);
			$databaseSuccess=$thisDatabaseWriter->insert($query,$data,0);

			$_SESSION['whatJustHappened']='Showtime Added';
			if(!$databaseSuccess){
				$_SESSION['whatJustHappened']='Error! Failed to add showtime';
			}
		}
	}
}elseif(isset($_POST['btnDeleteMovie'])){
	header('Location: delete-movie.php?movieId='.$currentMovieId);
}
?>
	<article class='movieContainer'>
	<article class='articleBg'>
	<?php
	printFormErrors($errorMsgMovie);
	?>
		<h1>Edit Movie Info (admin)</h1>
		<form action="<?php echo PHP_SELF.'?movieId='.$currentMovieId;?>" method='post' id='frmAddMovie' name='frmAddMovie' enctype="multipart/form-data">
			<p class='bold'>Make sure to change a movie's "Visibility" to "Current" in order to actually display any showtimes</p>
			<?php
			if(isset($_SESSION['whatJustHappened'])){	//tell user last action the form did & then unset the value
				echo "<p class='whatJustHappened'>".$_SESSION['whatJustHappened']."</p>\n";
				unset($_SESSION['whatJustHappened']);
			}

			//get a list of all possible movies to edit. Must do after form validation, or it doesn't get the update title if they clicked "Update Movie Info"
			$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector FROM tblMovies ORDER BY fldReleaseDate DESC";
			$moviesDropdownList=$thisDatabaseReader->select($query,'',0,1);
			echo "<select name='lstChooseMovie' id='lstChooseMovie'";
			if($currentMovieIdError){echo "class='mistake'";}
			echo ">\n";
			foreach($moviesDropdownList as $oneMovie){
				echo "\t\t\t\t<option value='".$oneMovie['pmkMovieId']."'";
				if($currentMovieId==$oneMovie['pmkMovieId']){
					echo ' selected ';
				}
				echo ">".$oneMovie['fldTitle']."</option>\n";
			}
			echo "\t\t\t</select>\n";
			echo "\t\t\t<input type='submit' name='btnChooseMovie' id='btnChooseMovie' value='Choose Movie to edit'>";
			
			include $upFolderPlaceholder.'php/magic/add-edit-form.php';

			//Print the currently selected image (this basically puts 'checked' )
			if($poster !='none'){
				//print the option to select NO IMAGE
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-none'>No Image</label></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' ></td>\n";
				echo "\t\t\t\t</tr>\n";

				// Print the actual image from the database
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-".$poster."'><strong>".$poster." (CURRENT)</strong></label> <a href='".IMAGE_POSTER_PATH.$poster."' target='_blank'>View Image (new tab)</a></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$poster."' value='".$poster."' checked >";
				echo "\t\t\t\t</tr>\n";
			}
			else{		//else print no image. Similar to above, but this time it's current
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><label for='radImg-none'><strong>No Image (CURRENT)</strong></label></td>\n";
				echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' checked ></td>\n";
				echo "\t\t\t\t</tr>\n";
			}

			foreach($imageList as $image){		//iterate through all possible files in folder (called @ start of this file)
				if(!in_array($image, $pictures)){	//only print picture if it's NOT already in the database
					echo "\t\t\t\t<tr>\n";
					echo "\t\t\t\t\t<td><label for='radImg-".$image."'>".$image."</label> <a href='".IMAGE_POSTER_PATH.$image."' target='_blank'>View Image (new tab)</a></td>\n";
					echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$image."' value='".$image."' ";
					echo "></td>\n";
					echo "\t\t\t\t</tr>\n";
				}
			}

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t<td><br><input type='submit' name='btnUpdateMovie' value='Update Movie Info' tabindex='".$tabIndex++."'></td>";
			echo "<td><br><input type='submit' name='btnDeleteMovie' value='Delete Movie' tabindex='".$tabIndex++."'>";
			echo "</td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><h2>Add Review (optional)</h2></td>\n";
			echo "\t\t\t\t</tr>\n";

			include $upFolderPlaceholder.'php/magic/review-form.php';

			echo "\t\t\t\t\t<tr>\n";
			echo "\t\t\t\t<td><input type='submit' name='btnAddReview' id='btnAddReview' tabindex='".$tabIndex++."' value='Add Review'></td>\n";
			echo "\t\t\t\t</tr>\n";

			//get a list of reviews already in database
			$query="SELECT pmkReviewId, fnkMovieId, fldAuthor, fldReviewDate, fldReviewSource, fldReviewLink, fldReview FROM tblReviews WHERE fnkMovieId=? ORDER BY fldReviewDate, fldAuthor";
			$data=array($currentMovieId);
			$reviewList=$thisDatabaseReader->select($query,$data,1,1);
			if(!empty($reviewList)){
				echo "\t\t\t\t<tr>\n";
				echo "\t\t\t\t\t<td><h3>Reviews for This Movie</h3></td>\n";
				echo "\t\t\t\t</tr>\n";
				foreach($reviewList as $oneReview){
					echo "\t\t\t\t<tr>\n";
					echo "\t\t\t\t\t<td colspan='2'>By ".$oneReview['fldAuthor'];
					if(!empty($oneReview['fldReviewSource'])){
						if(!empty($oneReview['fldReviewLink'])){
							echo " <em><a href='".$oneReview['fldReviewLink']."'>".$oneReview['fldReviewSource']."</a></em>";
						}else{
							echo " <em>".$oneReview['fldReviewSource']."</em>";
						}
					}
					echo " ".dateSqlToNice($oneReview['fldReviewDate']);
					echo " <a class='buttonLink' href='edit-review.php?reviewId=".$oneReview['pmkReviewId']."&movieId=".$currentMovieId."'>Edit Review</a> \n";
					echo "<a class='buttonLink' href='delete-review.php?reviewId=".$oneReview['pmkReviewId']."&movieId=".$currentMovieId."'>Delete Review</a></td>\n";
					echo "\t\t\t\t</tr>\n";

					echo "\t\t\t\t<tr>\n";
					echo "\t\t\t\t\t<td colspan='2'><details><summary>Expand/Collapse Review";
					echo "";
					echo "</summary>\n<p>".nl2br($oneReview['fldReview'], false)."</p></details><br></td>\n";
					echo "\t\t\t\t</tr>\n";
				}
			}

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t\t\t<td><h2>Add Showtime</h2></td>\n";
			echo "\t\t\t\t</tr>\n";

			include $upFolderPlaceholder.'php/magic/showtime-form.php';

			echo "\t\t\t\t<tr>\n";
			echo "\t\t\t<td><br><input type='submit' name='btnAddShowtime' value='Add New Showtime' tabindex='".$tabIndex++."'></td>\n";
			echo "\t\t\t\t</tr>\n";

			echo "\t\t\t</table>\n";

			//get loop boundaries (olest & newest showtime)
			$query="SELECT MIN(fldShowtimePosts) as oldestShowtime FROM tblShowtimes WHERE fnkMovieId=?";
			$data=array($currentMovieId);
			$oldestShowtime=$thisDatabaseReader->select($query,$data,1);

			$query="SELECT MAX(fldShowtimePosts) as newestShowtime FROM tblShowtimes WHERE fnkMovieId=?";
			$newestShowtime=$thisDatabaseReader->select($query,$data,1);	//uses same $data array

			echo "\t\t\t<h3>Current Showtimes</h3>\n";
			echo "\t\t\t<p>Showtimes are only displayed to the public during the specified date range. (No need to delete old ones, they will just be hidden when past the 'showtime expires' date)<br>Since 2D showings are most common the site only prints 3D if a showing is 3D (So it's important to know if a showtime is 2D or 3D, but you will never see 2D on the public site)</p>\n<p class='bold'>These admin showtimes are listed by POST DATE. Look carefully to see when they expire</p>\n";
			echo "\t\t\t<section class='showtimesListAdmin'>\n";
			//loops week by week (fridays) starting @ the nearest friday to the oldest date, up until the nearest friday the newest showtime, increment by 7 days each iteration
			for($friday=nearestDate("friday",$oldestShowtime[0][0]); $friday<=nearestDate("friday",$newestShowtime[0][0]); $friday=date('Y-m-d', strtotime($friday.' +7 days'))){
				//select showtimes in 1 week increments using BETWEEN & only showtimes for the current movie
				$query="SELECT pmkShowtimeId, fnkMovieId, fldHour, fldMinute, fldMeridian, fldShowtimePosts, fldShowtimeExpires, fldDimension FROM tblShowtimes WHERE ( fnkMovieId=? AND (fldShowtimePosts BETWEEN CAST(? AS DATE)  AND CAST(? AS DATE) ) ) ORDER BY fldShowtimePosts";
				$nextThursday=date('Y-m-d', strtotime($friday.' +6 days'));		//plus 6 days so week is friday-thursday, not friday-friday (this would duplicate showtimes on fridays)
				$data=array($currentMovieId,$friday,$nextThursday);
				$weekOfShowtimes=$thisDatabaseReader->select($query,$data,1,3);

				if(!empty($weekOfShowtimes)){
					echo "\t\t\t<section>\n";
					echo "\t\t\t\t<h4>Week: ".dateSqlToNice($friday)." (Friday to Thursday)</h4>\n";
				}


				foreach($weekOfShowtimes as $oneShowtime){
					echo "\t\t\t\t\t<section>\n";
					echo "\t\t\t\t\t\t<p>".$oneShowtime['fldHour'].":".leadingZeros($oneShowtime['fldMinute'],2)." ".$oneShowtime['fldMeridian']." ".$oneShowtime['fldDimension']." (<strong>Displayed: </strong>".dateSqlToNice($oneShowtime['fldShowtimePosts'])." to ".dateSqlToNice($oneShowtime['fldShowtimeExpires']).") ";
					echo "<a href='edit-showtime.php?showtimeId=".$oneShowtime['pmkShowtimeId']."&movieId=".$oneShowtime['fnkMovieId']."' class='buttonLink'>Edit Showtime</a>\n";
					echo "<a href='delete-showtime.php?showtimeId=".$oneShowtime['pmkShowtimeId']."&movieId=".$oneShowtime['fnkMovieId']."' class='buttonLink'>Delete Showtime</a></p>\n";
					echo "\t\t\t\t\t</section>\n";
				}
				if(!empty($weekOfShowtimes)){
					echo "\t\t\t</section>\n";
				}
			}
			echo "\t\t\t</section>\n";
			?>
		</form>
	</article>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>