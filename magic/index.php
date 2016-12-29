<?php
include	"../php/top.php";

$tabIndex=1;		//print on every form input element & increment

$title='';			
$runtime='';
$rating='PG-13';				//most common rating
$releaseDate=date('Y-m-d', strtotime('this friday'));		//initialize to the upcoming friday
$display='Current';				//default is hidden (since don't want 2 display movie without showtimes)
$director='';
$synopsis='';
$poster='none';

include $upFolderPlaceholder.'php/magic/add-edit-variables.php';

$errorMsgDisplayFilter=array();

$validDisplayFilters=array('All','Current','Hidden');
$displayFilter='All';		//default is to show all movies
$displayFilterError=false;

if(isset($_POST['btnAddMovie'])){
	include $upFolderPlaceholder.'php/magic/add-edit-validation.php';

	if(!$errorMsgMovie){
		$query="INSERT INTO tblMovies (fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector) VALUES (?,?,?,?,?,?)";
		$data=array($title,$runtime,$rating,$releaseDate,$display,$director);
		$thisDatabaseWriter->insert($query,$data,0);
		$lastMovieId=$thisDatabaseWriter->lastInsert();		//get id of movie just added so for synopsis

		if($synopsis !=''){		//only add to table if NOT empty
			$query="INSERT INTO tblSynopses (fnkMovieId, fldSynopsis) VALUES (?,?)";
			$data=array($lastMovieId,$synopsis);
			$thisDatabaseWriter->insert($query,$data,0);
		}

		//only insert into table if they selected a radio button image filename
		if($poster !='none'){
			$query="INSERT INTO tblPictures (fnkMovieId, fldImgFilename) VALUES (?,?)";
			$data=array($lastMovieId,$poster);
			$thisDatabaseWriter->insert($query,$data,0);
		}

		header('Location: edit.php?movieId='.$lastMovieId);		//redirect to Edit page
	}
}elseif(isset($_POST['btnFilterMovieVisibility']) ){
	$displayFilter=htmlentities($_POST['lstVisibilityFilter'], ENT_QUOTES, "UTF-8");
	if($displayFilter==''){
		$errorMsgDisplayFilter[]='Display Filter option must NOT be empty';
		$displayFilterError=true;
	}elseif (!in_array($displayFilter, $validDisplayFilters)) {
		$errorMsgDisplayFilter[]='Display Filter option must be a valid choice from dropdown';
		$displayFilterError=true;
	}
	//the effect of this button is seen later when actually querying the database & adding a where clause
}
?>
	<article class='movieContainer'>
		<h1>Add Movie (admin)</h1>
		<article class='articleBg'>
	<?php
	printFormErrors($errorMsgMovie);
	?>
		<form action="<?php echo PHP_SELF;?>" method='post' id='frmAddMovie' name='frmAddMovie' >
			<?php
			if(isset($_SESSION['whatJustHappened'])){	//tell user last action the form did & then unset the value
				echo "<p class='whatJustHappened'>".$_SESSION['whatJustHappened']."</p>\n";
				unset($_SESSION['whatJustHappened']);
			}

			include $upFolderPlaceholder.'php/magic/add-edit-form.php';

			//always print the 1st row so that they can choose NO IMAGE
			echo "\t\t\t\t<tr>\n";
			echo "<td><label for='radImg-none'>No Image</label></td>\n";
			echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-none' value='none' ";
			if($poster=='none'){echo ' checked ';}
			echo "></td>\n";
			echo "\t\t\t\t</tr>\n";
			foreach($imageList as $image){		//iterate through all possible files in folder (called @ start of this file)
				if(!in_array($image, $pictures)){	//only print picture if it's NOT already in the database
					echo "\t\t\t\t<tr>\n";
					echo "<td><label for='radImg-".$image."'>".$image."</label> <a href='".IMAGE_POSTER_PATH.$image."' target='_blank'>View Image (new tab)</a></td>\n";
					echo "\t\t\t\t<td><input type='radio' name='radImageChoose' id='radImg-".$image."' value='".$image."' ";
					if($poster==$image){echo ' checked ';}
					echo "></td>\n";
					echo "\t\t\t\t</tr>\n";
				}
			}

			echo "\t\t\t</table>\n";

			echo "\t\t\t<br><input type='submit' name='btnAddMovie' value='Add Movie' tabindex='".$tabIndex++."'><br>\n";
			echo "\t\t</article>\n";

			$query="SELECT pmkMovieId, fldTitle, fldRuntime, fldRating, fldReleaseDate, fldDisplay, fldDirector,
			 fldSynopsis FROM tblMovies 
			  LEFT JOIN tblSynopses ON pmkMovieId=fnkMovieId";		//need left join since I DO want to have movie info even if there's no synopsis
			if(!$displayFilterError && $displayFilter !='All'){
				$query.=" WHERE fldDisplay LIKE ?";
				$data=array($displayFilter);
				$movies=$thisDatabaseReader->select($query,$data,1);
			}else{
				$movies=$thisDatabaseReader->select($query,'',0);
			}

			if(!empty($movies)){	//only print if there are movies to show
				echo "\n\t\t\t<h3>Movies in Database</h3>\n";
				printFormErrors($errorMsgDisplayFilter);
				echo "\t\t\t<select name='lstVisibilityFilter' id='lstVisibilityFilter'";
				if($displayFilterError){
					echo ' class="mistake" ';
				}
				echo ">\n";
				foreach($validDisplayFilters as $filter){
					echo "\t\t\t\t<option value='".$filter."' ";
					if($filter==$displayFilter){
						echo " selected ";
					}
					echo ">".$filter."</option>\n";
				}
				echo "\t\t\t<select>\n";
				echo "\t\t\t<input type='submit' name='btnFilterMovieVisibility' value='Filter Movies by Visibility' tabindex='".$tabIndex++."'>\n";

				echo "\t\t\t<section class='admin-movie-list'>\n";
				foreach($movies as $movie){
					echo "\t\t\t\t<article class='articleBg'>\n";
					echo "\t\t\t\t\t<p>Title: ".$movie['fldTitle']."</p>\n";
					echo "\t\t\t\t\t<p>Runtime: ".$movie['fldRuntime']." minutes</p>\n";
					echo "\t\t\t\t\t<p>Rating: ".$movie['fldRating']."</p>\n";
					echo "\t\t\t\t\t<p>Release Date: ".dateSqlToNice($movie['fldReleaseDate'])."</p>\n";
					echo "\t\t\t\t\t<p>Visibility (show or hide): ".$movie['fldDisplay']."</p>\n";
					echo "\t\t\t\t\t<p>Director: ".$movie['fldDirector']."</p>\n";
					echo "\t\t\t\t\t<p>Synopsis:<br> ".$movie['fldSynopsis']."</p>\n";

					echo "\t\t\t\t\t<a class='buttonLink' href='edit.php?movieId=".$movie['pmkMovieId']."'>Edit Info</a> (add showtimes & reviews)\n";
					echo "\t\t\t\t</article >\n";
				}
				echo "\t\t\t</section>\n";
			}else{
				echo "\n\t\t\t<h3>No Movies to Show</h3>\n";
			}
			?>
		</form>
	</article>
<?php
include	$upFolderPlaceholder."php/footer.php";
?>