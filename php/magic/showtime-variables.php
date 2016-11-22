<?php
$showtimeHour=6;
$showtimeMinute=05;
$showtimeMeridian="PM";
$showtimePosts=date('Y-m-d', strtotime('this friday'));
$showtimeExpires=date('Y-m-d', strtotime('this friday +6 days'));	//expires 6 days after (default)
$showtimeDimension='2D';

$showtimeHourError=false;
$showtimeMinuteError=false;
$showtimeMeridianError=false;
$showtimePostsError=false;
$showtimeExpiresError=false;
$showtimeDimensionError=false;
?>