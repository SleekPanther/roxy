<?php
//validation for showtime variables
$showtimeHour=htmlentities($_POST['lstHour'], ENT_QUOTES, "UTF-8");
$showtimeMinute=htmlentities($_POST['lstMinute'], ENT_QUOTES, "UTF-8");
$showtimeMeridian=strtoupper(htmlentities($_POST['lstMeridian'], ENT_QUOTES, "UTF-8"));
$showtimePosts=htmlentities($_POST['datShowtimePosts'], ENT_QUOTES, "UTF-8");
$showtimeExpires=htmlentities($_POST['datShowtimeExpires'], ENT_QUOTES, "UTF-8");
$showtimeDimension=strtoupper(htmlentities($_POST['lstDimension'], ENT_QUOTES, "UTF-8"));

if($showtimeHour==''){
	$errorMsg[]='Showtime Hour cannot be empty';
	$showtimeHourError=true;
}elseif(!verifyNumeric($showtimeHour)) {
	$errorMsg[]='Showtime Hour must be a number';
	$showtimeHourError=true;
}elseif($showtimeHour<1 || $showtimeHour >12) {
	$errorMsg[]='Showtime Hour must be in the range 1-12';
	$showtimeHourError=true;
}

if($showtimeMinute==''){
	$errorMsg[]='Showtime Minute cannot be empty';
	$showtimeMinuteError=true;
}elseif(!verifyNumeric($showtimeMinute)) {
	$errorMsg[]='Showtime Minute must be a number';
	$showtimeMinuteError=true;
}elseif($showtimeMinute<0 || $showtimeMinute >59) {
	$errorMsg[]='Showtime Minute must be in the range 00-59';
	$showtimeMinuteError=true;
}

if($showtimeMeridian==''){
	$errorMsg[]='Showtime AM or PM cannot be empty';
	$showtimeMeridianError=true;
}elseif(!($showtimeMeridian=="AM" || $showtimeMeridian=="PM")) {
	$errorMsg[]='Showtime must be AM or PM';
	$showtimeMeridianError=true;
}

if($showtimePosts==''){
	$errorMsg[]='Showtime Post date cannot be empty';
	$showtimePostsError=true;
}elseif(!validateSqlDate($showtimePosts)){
	$errorMsg[]='Showtime Post date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$showtimePostsError=true;
}

if($showtimeExpires==''){
	$errorMsg[]='Showtime Expiration date cannot be empty';
	$showtimeExpiresError=true;
}elseif(!validateSqlDate($showtimeExpires)){
	$errorMsg[]='Showtime Expiration date must be in the form YYYY-MM-DD (January 15, 2016 is 2016-15-01)';
	$showtimeExpiresError=true;
}

if(!$showtimePostsError && !$showtimeExpiresError){		//if no errors, make sure expiration is AFTER posting
	if(!validateDateRange($showtimePosts, $showtimeExpires)){
		$errorMsg[]='Showtime Expiration must be AFTER post date';
		$showtimeExpiresError=true;
	}
}
	
if($showtimeDimension==''){
	$errorMsg[]='Showtime 2D or 3D cannot be empty';
	$showtimeDimensionError=true;
}elseif(!($showtimeDimension=="2D" || $showtimeDimension=="3D")) {
	$errorMsg[]='Showtime must be 2D or 3D';
	$showtimeDimensionError=true;
}

?>