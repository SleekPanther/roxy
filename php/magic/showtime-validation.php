<?php
//validation for showtime variables

$showtimeHour=htmlentities($_POST['lstHour'], ENT_QUOTES, "UTF-8");

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

?>