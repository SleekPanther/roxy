<?php
// $query="INSERT INTO tblShowtimes (fnkMovieId, fldHour, fldMinute, fldShowtimePosts, fldShowtimeExpires, fldDimension) VALUES (?,?,?,?,?,?) ON DUPLICATE KEY UPDATE ";
$query="INSERT INTO tblShowtimes (fnkMovieId, fldHour, fldMinute, fldMeridian) VALUES (?,?,?,?)";
$data=array($currentMovieId,$showtimeHour,$showtimeMinute, $showtimeMeridian);
$thisDatabaseWriter->testquery($query,$data,0);
$thisDatabaseWriter->insert($query,$data,0);
?>