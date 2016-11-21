<?php
//validation for showtime variables

$showtimeHour=htmlentities($_POST['lstHour'], ENT_QUOTES, "UTF-8");
$showtimeMinute=htmlentities($_POST['lstMinute'], ENT_QUOTES, "UTF-8");
$showtimeMeridian=strtoupper(htmlentities($_POST['lstMeridian'], ENT_QUOTES, "UTF-8"));

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



?>